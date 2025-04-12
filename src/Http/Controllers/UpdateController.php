<?php

namespace Ssda1\proxies\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{
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
            // Путь к рабочей директории проекта
            $workingDirectory = base_path();
            
            // Выполняем команду composer update
            $command = 'cd ' . $workingDirectory . ' && composer update ssda-1/proxies';
            
            if (PHP_OS_FAMILY === 'Windows') {
                $process = popen('start /B cmd /C "' . $command . ' 2>&1"', 'r');
                pclose($process);
            } else {
                \exec($command . ' > /dev/null 2>&1 &');
            }
            
            return response()->json(['success' => true, 'message' => 'Обновление запущено']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при обновлении: ' . $e->getMessage()], 500);
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
