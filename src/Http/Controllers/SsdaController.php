<?php

declare(strict_types=1);

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\ProjectStatus;
use ssda1\proxies\Service\KrakenService;

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

        $krakenService = new KrakenService();
        $result = json_decode($krakenService->getLicenseKey($data), true);

        if (!$result['error']) {
            config(['license.key' => $data['key']]);
            $configPath = config_path('license.php');
            file_put_contents($configPath, '<?php return ' . var_export(config('license'), true) . ';');
            Artisan::call('config:cache');
        }

        return response()->json(true);
    }
}
