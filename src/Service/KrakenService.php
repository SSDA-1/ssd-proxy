<?php

namespace Ssda1\proxies\Service;

use Ssda1\proxies\Models\Project;

class KrakenService
{
    // Получение Токена
    public function getLicenseKey($data)
    {
        $url = 'https://ssd-p.ru/api/check/key';
        $postData = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        // Используем только file_get_contents
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\nAccept: application/json\r\n",
                'method'  => 'POST',
                'content' => $postData,
                'timeout' => 10,
                'ignore_errors' => true, // Получать контент даже при ошибках HTTP
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        
        $context = stream_context_create($options);
        
        // Обработка ошибок
        set_error_handler(function($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
        
        try {
            $result = file_get_contents($url, false, $context);
            restore_error_handler();
            
            if ($result === false) {
                return json_encode([
                    'error' => true,
                    'message' => 'Ошибка при отправке запроса через file_get_contents'
                ]);
            }
            
            // Проверяем, получили ли мы валидный JSON
            json_decode($result);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return json_encode([
                    'error' => true,
                    'message' => 'Ответ не является валидным JSON: ' . json_last_error_msg()
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            restore_error_handler();
            return json_encode([
                'error' => true,
                'message' => 'Исключение: ' . $e->getMessage()
            ]);
        }
    }

    // Получение Токена
    public function getAuthLogin($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/auth/login';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Получение авторизованных
    public function getProxyAuthList($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/auth/list';
        return $this->makeRequest($url, $data, 'GET');
    }

    // Добавление авторизованным
    public function getProxyAuthAdd($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/auth/add';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Редактирование Прокси
    public function getProxyEdit($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/edit';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Блокирование Прокси
    public function getProxyActive($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/active';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Создание Прокси
    function getProxyAdd($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/add';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Создание Прокси
    function getProxyDel($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/proxy/del';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Получение Модемов
    function getDevicesModemType($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/devices/modem/type';
        return $this->makeRequest($url, $data, 'GET');
    }

    // Создание Порта
    public function getDevicesModemAdd($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/devices/modem/add';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Редактирование Порта
    public function getDevicesModemEdit($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/devices/modem/edit';
        return $this->makeRequest($url, $data, 'POST');
    }

    // Получение сетевых интерфейсов
    function getDevicesInterfaceList($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/devices/interface/list';
        return $this->makeRequest($url, $data, 'GET');
    }

    // Получение отпечатков
    function getDevicesOsfpList($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/devices/osfp/list';
        return $this->makeRequest($url, $data, 'GET');
    }

    // Добавление пользователям
    function getUsersAdd($data)
    {
        $url = 'https://ssd-p.ru/api/kraken/users/add';
        return $this->makeRequest($url, $data, 'POST');
    }
    
    // Общий метод для всех HTTP-запросов
    private function makeRequest($url, $data, $method = 'POST')
    {
        $postData = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        $headers = "Content-type: application/json\r\n" .
                   "Accept: application/json\r\n";
                   
        $options = [
            'http' => [
                'header'  => $headers,
                'method'  => $method,
                'content' => $method === 'GET' ? null : $postData,
                'timeout' => 30,
                'ignore_errors' => true, // Получать контент даже при ошибках HTTP
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        
        $context = stream_context_create($options);
        
        set_error_handler(function($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
        
        try {
            $result = file_get_contents($url, false, $context);
            restore_error_handler();
            
            if ($result === false) {
                return json_encode([
                    'error' => true,
                    'message' => 'Не удалось выполнить запрос'
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            restore_error_handler();
            return json_encode([
                'error' => true,
                'message' => 'Исключение при запросе: ' . $e->getMessage()
            ]);
        }
    }
}
