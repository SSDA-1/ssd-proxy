<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\HistoryOperation;
use ssd\proxies\Models\siteSetting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BetatransferService
{ 
    // Новое аппи
    public function getPayOLD($amount)
    {
        $user = Auth::user();
        $login = 'vxcactus@yahoo.com';
        $url = 'https://Zerocryptopay.com/pay/newtrack/';
        $siteSettingModel = siteSetting::find(1);
        $public = $siteSettingModel->betatransfer_public;
        $token = $siteSettingModel->betatransfer_secret;

        $urlSite = $_SERVER['SERVER_NAME'];
        $urlSucc = $urlSite.'/balancedone/'.$newOperation->id.'/b';
        

        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через Betatransfer',
            'billId' => '',
            'user_id' => $user->id,
        ]);

        $signature = [];

        $data = [
            'login' => $login,
            'amount' => $amount,
            'token' => $token,
            'orderId' => $newOperation->id,
            'signature' => $signature,
        ];

        $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->post($url, $data);

        // $responseArray = $response->json();

            
            if ($response->successful()) {
                $order = $response->json();

                // Получаем модель по идентификатору, который был назначен при создании
                $operationToUpdate = HistoryOperation::find($newOperation->id);

                // Обновляем требуемое поле
                $operationToUpdate->billId = isset($order['id']) ? $order['id'] : null;

                // Сохраняем изменения
                $operationToUpdate->save();

            } else {
                $order = $response->status();
            }
        // dd($response);
        $payUrl = $order;

        return $payUrl;
    }

    public function checkPay($id)
    {
        $user = Auth::user();
        $url = 'https://merchant.betatransfer.io/api/info';
        $siteSettingModel = siteSetting::find(1);
        $public = $siteSettingModel->betatransfer_public;
        $secret = $siteSettingModel->betatransfer_secret;
        $operation = HistoryOperation::find($id);

        $data = [
            'id' => $operation->billId,
        ];

        $data['sign'] = md5(implode("", $data) . $apiSecret);

        $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . $public,
                'token' => $public,
            ])->post($url, $data);

        // $responseArray = $response->json();

            
            if ($response->successful()) {
                $order = $response->json();
                if (isset($order['status']) and $order['status'] == 'success') {
                    $operation->status = 1;
                    // Сохраняем изменения
                    $operation->save();
                    processReferrals($user->id, $operation->amount);
                    $order = true;
                }else{
                    $order = false;
                }
            } else {
                // $order = $response->status();
                $order = false;
            }
        // dd($response);
        $return = $order;

        return $return;
    }

    // Старое аппи
    public function getPayOLD($amount)
    {
        $user = Auth::user();
        $url = 'https://merchant.betatransfer.io/api/payment';
        $siteSettingModel = siteSetting::find(1);
        $public = $siteSettingModel->betatransfer_public;
        $secret = $siteSettingModel->betatransfer_secret;

        $urlSite = $_SERVER['SERVER_NAME'];
        $urlSucc = $urlSite.'/balancedone/'.$newOperation->id.'/b';

        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через Betatransfer',
            'billId' => '',
            'user_id' => $user->id,
        ]);

        $data = [
            'amount' => $amount,
            'currency' => 'USD',
            'orderId' => $newOperation->id,
            'urlSuccess' => $urlSucc,
        ];

        $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . $public,
                'token' => $public,
            ])->post($url, $data);

        // $responseArray = $response->json();

            
            if ($response->successful()) {
                $order = $response->json();

                // Получаем модель по идентификатору, который был назначен при создании
                $operationToUpdate = HistoryOperation::find($newOperation->id);

                // Обновляем требуемое поле
                $operationToUpdate->billId = isset($order['id']) ? $order['id'] : null;

                // Сохраняем изменения
                $operationToUpdate->save();

            } else {
                $order = $response->status();
            }
        // dd($response);
        $payUrl = $order;

        return $payUrl;
    }
}