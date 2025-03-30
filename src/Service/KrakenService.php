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
        $ch = curl_init('https://ssd-p.ru/api/kraken/auth/login');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        // Установить тайм-аут в 5 секунд
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Получение авторизованных
    public function getProxyAuthList($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/auth/list');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Добавление авторизованным
    public function getProxyAuthAdd($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/auth/add');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Редактирование Прокси
    public function getProxyEdit($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/edit');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Блокирование Прокси
    public function getProxyActive($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/active');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Создание Прокси
    function getProxyAdd($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/add');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Создание Прокси
    function getProxyDel($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/proxy/del');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Получение Модемов
    function getDevicesModemType($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/devices/modem/type');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Создание Порта
    public function getDevicesModemAdd($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/devices/modem/add');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Редактирование Порта
    public function getDevicesModemEdit($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/devices/modem/edit');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Получение сетевых интерфейсов
    function getDevicesInterfaceList($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/devices/interface/list');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Получение отпечатков
    function getDevicesOsfpList($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/devices/osfp/list');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Добавление пользователям
    function getUsersAdd($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/kraken/users/add');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json',));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
