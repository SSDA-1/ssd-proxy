<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\Project;

class KrakenService
{
    // Получение Токена
    public function getLicenseKey($data)
    {
        $ch = curl_init('https://ssd-p.ru/api/check/key');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
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