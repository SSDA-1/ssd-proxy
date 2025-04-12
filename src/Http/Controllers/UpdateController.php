<?php

namespace Ssda1\proxies\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{
    /**
     * Путь к маркеру обновления
     */
    protected $updateMarkerPath;
    
    /**
     * Путь к файлу статуса обновления
     */
    protected $updateStatusPath;
    
    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->updateMarkerPath = storage_path('app/update_marker.txt');
        $this->updateStatusPath = storage_path('app/update_status.json');
    }
    
    /**
     * Проверяет наличие обновлений для пакета
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdates()
    {
        try {
            // Получаем информацию о последнем релизе с GitHub
            $response = Http::get('https://api.github.com/repos/SSDA-1/ssd-proxy/releases/latest');
            
            if ($response->successful()) {
                $latestRelease = $response->json();
                $latestVersion = ltrim($latestRelease['tag_name'], 'v');
                
                // Получаем текущую версию из composer.json
                $composerJson = json_decode(File::get(base_path('composer.json')), true);
                $currentVersion = '';
                
                // Ищем пакет SSDA-1/ssd-proxy в require или require-dev
                foreach (['require', 'require-dev'] as $requireType) {
                    if (isset($composerJson[$requireType]['ssda-1/proxies'])) {
                        $currentVersion = preg_replace('/[^0-9.]/', '', $composerJson[$requireType]['ssda-1/proxies']);
                        break;
                    }
                }
                
                // Если нет версии или новая версия больше текущей
                if (empty($currentVersion) || $this->isGreaterVersion($latestVersion, $currentVersion)) {
                    return response()->json([
                        'hasUpdate' => true,
                        'currentVersion' => $currentVersion,
                        'latestVersion' => $latestVersion,
                        'releaseNotes' => $latestRelease['body'],
                        'publishedAt' => $latestRelease['published_at']
                    ]);
                }
                
                return response()->json(['hasUpdate' => false]);
            }
            
            return response()->json(['error' => 'Не удалось получить информацию о релизах'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Произошла ошибка: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Выполняет обновление пакета через composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        try {
            // Сохраняем маркер для обновления
            $updateData = [
                'requested_at' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'message' => 'Обновление в очереди'
            ];
            
            // Создаем директорию, если её нет
            if (!File::exists(dirname($this->updateMarkerPath))) {
                File::makeDirectory(dirname($this->updateMarkerPath), 0755, true);
            }
            
            // Создаем маркер обновления
            File::put($this->updateMarkerPath, date('Y-m-d H:i:s'));
            
            // Сохраняем статус обновления
            File::put($this->updateStatusPath, json_encode($updateData));
            
            // Пытаемся запустить обновление через Artisan, если это возможно
            try {
                // Регистрируем команду маршрута для обновления
                $this->registerUpdateRoute();
                
                // Отправляем HTTP запрос к маршруту обновления
                $baseUrl = request()->getSchemeAndHttpHost();
                Http::get($baseUrl . '/run-proxies-update');
            } catch (\Exception $e) {
                // Игнорируем ошибки, так как обновление может быть запущено другим способом
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Обновление запущено. Проверьте статус через несколько минут.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при обновлении: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Проверяет статус обновления
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdateStatus()
    {
        try {
            if (File::exists($this->updateStatusPath)) {
                $status = json_decode(File::get($this->updateStatusPath), true);
                return response()->json($status);
            }
            
            return response()->json([
                'status' => 'unknown',
                'message' => 'Статус обновления не найден'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при проверке статуса: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Фактически выполняет обновление (вызывается через специальный маршрут)
     *
     * @return string
     */
    public function executeUpdate()
    {
        try {
            // Проверяем наличие маркера обновления
            if (!File::exists($this->updateMarkerPath)) {
                return 'Обновление не запрошено';
            }
            
            // Обновляем статус
            $updateData = [
                'started_at' => date('Y-m-d H:i:s'),
                'status' => 'running',
                'message' => 'Обновление запущено'
            ];
            File::put($this->updateStatusPath, json_encode($updateData));
            
            // Запускаем composer update
            $composerPath = base_path('composer.phar');
            
            try {
                if (file_exists($composerPath)) {
                    // Если есть локальный composer.phar, используем его
                    $output = shell_exec('php ' . $composerPath . ' update ssda-1/proxies --no-interaction 2>&1');
                } else {
                    // Иначе используем глобальный composer
                    $output = shell_exec('composer update ssda-1/proxies --no-interaction 2>&1');
                }
                
                // Обновляем статус
                $updateData = [
                    'completed_at' => date('Y-m-d H:i:s'),
                    'status' => 'completed',
                    'message' => 'Обновление успешно завершено',
                    'output' => $output
                ];
            } catch (\Exception $e) {
                // В случае ошибки
                $updateData = [
                    'completed_at' => date('Y-m-d H:i:s'),
                    'status' => 'failed',
                    'message' => 'Ошибка при обновлении: ' . $e->getMessage()
                ];
            }
            
            // Сохраняем финальный статус
            File::put($this->updateStatusPath, json_encode($updateData));
            
            // Удаляем маркер обновления
            File::delete($this->updateMarkerPath);
            
            return 'Обновление выполнено';
        } catch (\Exception $e) {
            return 'Ошибка при выполнении обновления: ' . $e->getMessage();
        }
    }
    
    /**
     * Регистрирует временный маршрут для обновления
     */
    private function registerUpdateRoute()
    {
        if (!app('router')->getRoutes()->getByName('run-proxies-update')) {
            app('router')->get('/run-proxies-update', function () {
                return app()->call([app(UpdateController::class), 'executeUpdate']);
            })->name('run-proxies-update')->withoutMiddleware(['web', 'auth']);
        }
    }
    
    /**
     * Сравнивает версии в формате семантического версионирования
     * 
     * @param string $version1 Первая версия
     * @param string $version2 Вторая версия
     * @return bool true, если $version1 > $version2
     */
    private function isGreaterVersion($version1, $version2)
    {
        $v1Parts = explode('.', $version1);
        $v2Parts = explode('.', $version2);
        
        // Дополняем массивы нулями до одинаковой длины
        $maxLength = max(count($v1Parts), count($v2Parts));
        for ($i = count($v1Parts); $i < $maxLength; $i++) {
            $v1Parts[$i] = 0;
        }
        for ($i = count($v2Parts); $i < $maxLength; $i++) {
            $v2Parts[$i] = 0;
        }
        
        // Сравниваем компоненты версий
        for ($i = 0; $i < $maxLength; $i++) {
            $part1 = (int) $v1Parts[$i];
            $part2 = (int) $v2Parts[$i];
            
            if ($part1 > $part2) {
                return true;
            }
            if ($part1 < $part2) {
                return false;
            }
        }
        
        // Версии равны
        return false;
    }
}
