<?php

declare(strict_types=1);

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\ProjectStatus;
use Ssda1\proxies\Service\KrakenService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class SsdaController extends Controller
{
    public string|null $startOfSubscription = null;
    public string|null $endOfSubscription = null;
    public string|null $totalAmountKraken = null;
    public string|null $nameOfSubscription = null;
    public string|null $app_key = null;

   public function index(Request $request)
    {
        $projectStatus = ProjectStatus::select($request->status)->where('id', '=', 1)->get();
        if ($projectStatus->isEmpty()) {
            $projectStatus = new ProjectStatus();
            $projectStatus->is_domain_active = 1;
            $projectStatus->is_archive = null;
            $projectStatus->to_delete = null;
            $projectStatus->save();
        }
        if ($request->status == 'is_domain_active' && $request->value == 1) {
            return ProjectStatus::select($request->status)->where('id', '=', 1)->update([$request->status => $request->value, 'is_archive' => 0, 'to_delete' => 0]);
        }
        return ProjectStatus::select($request->status)->where('id', '=', 1)->update([$request->status => $request->value]);
    }

    public function getInfoFromSsda(): static
    {
        //TODO APP_KEY
        $endpoint = "https://ssd-p.ru/api/project";
        $client = new \GuzzleHttp\Client();
        $key = 'base64:4g7d+lFw/AQOejKNfc/uEJzcnFdz/zKfBgl/LSdXc8s=';
        $response = $client->request('POST', $endpoint, ['query' => [
            'key' => $key,
        ]]);

        $content = json_decode((string)$response->getBody(), true);

        foreach ($content as $items) {

            foreach ($items as $item) {

                if (isset($item['end_of_subscription'])) {
                    $this->startOfSubscription = $item['created_at'];
                    $this->endOfSubscription = $item['end_of_subscription'];
                    $this->app_key = $item['app_key'];
                } else {
                    $this->nameOfSubscription = $item['name'];
                    $subscriptionInfo = json_decode($item['description']);

                    foreach ($subscriptionInfo->children as $desc) {
                        if (mb_stripos($desc->html, 'Количество серверов')) {

                            $this->totalAmountKraken = mb_strcut($desc->html, 43); //обрезаем "\r\n  Количество серверов - "
                        }
                    }
                }
            }
        }
        return $this;
    }

    public function getKey(): ?string
    {
        return $this->app_key;
    }

    public function getAmountKraken(): ?string
    {
        return $this->totalAmountKraken;
    }

    public function getStartOfSubscription(): ?string
    {
        $time = new Carbon($this->startOfSubscription);
        return $this->startOfSubscription = $time->format('d.m.Y');
    }

    public function getEndOfSubscriptionFormatted(): ?string
    {
        $time = new Carbon($this->endOfSubscription);
        return $this->endOfSubscription = $time->format('d.m.Y');
    }

    public function getEndOfSubscription(): ?string
    {
        return $this->endOfSubscription;
    }

    public function getNameOfSubscription(): ?string
    {
        return $this->nameOfSubscription;
    }

    public function licenseKey(Request $request)
    {
        $data = [
            'key' => $request['key']
        ];

        try {
            $krakenService = new KrakenService();
            $response = $krakenService->getLicenseKey($data);
            
            // Вывод диагностической информации
            \Log::info('Ответ от сервиса лицензий: ' . $response);
            
            // Проверяем, является ли ответ валидным JSON
            $result = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Ошибка декодирования JSON: ' . json_last_error_msg());
                return response()->json([
                    'error' => true,
                    'message' => 'Ошибка при обработке ответа: ' . json_last_error_msg(),
                    'raw_response' => $response
                ], 500);
            }
            
            if (isset($result['status']) && $result['status'] === 'active') {
                // Сохраняем лицензионный ключ
                config(['license.key' => $data['key']]);
                config(['license.status' => 'active']);
                $configPath = config_path('license.php');
                file_put_contents($configPath, '<?php return ' . var_export(config('license'), true) . ';');
                
                // Устанавливаем статус домена в is_domain_active = 1
                $projectStatus = \Ssda1\proxies\Models\ProjectStatus::find(1);
                if ($projectStatus) {
                    $projectStatus->is_domain_active = 1;
                    $projectStatus->is_archive = 0;
                    $projectStatus->to_delete = 0;
                    $projectStatus->save();
                } else {
                    $projectStatus = new \Ssda1\proxies\Models\ProjectStatus();
                    $projectStatus->is_domain_active = 1;
                    $projectStatus->is_archive = 0;
                    $projectStatus->to_delete = 0;
                    $projectStatus->save();
                }
                
                Artisan::call('config:cache');
                
                return response()->json([
                    'error' => false,
                    'message' => 'Лицензионный ключ успешно сохранен'
                ]);
            }
            
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Исключение в licenseKey: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Произошла ошибка: ' . $e->getMessage()
            ], 500);
        }
    }
}
