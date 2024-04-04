<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\HistoryOperation;
use ssd\proxies\Models\siteSetting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CapitalistService
{
    public function getPay($amount)
    {
        $user = Auth::user();
        $order = '';
        $nonce = time() . rand(); // Добавляем случайное значение для уменьшения вероятности повторения
        $siteSettingModel = siteSetting::find(1);
        $shopId = $siteSettingModel->capitalist_id;
        $api_key = $siteSettingModel->capitalist_secret;
        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через Capitalist',
            'billId' => $nonce,
            'user_id' => $user->id,
        ]);

        $urlSite = $_SERVER['SERVER_NAME'];
        $urlSucc = $urlSite.'/balancedone/'.$newOperation->id.'/c';

            $data = [
                'merchantid' => $shopId,
                'amount' => $amount,
                'number' => $newOperation->id,
                'description' => 'Пополнение баланса',
                'currency' => 'USD',
            ];

            ksort($data);
            
            $sign = hash_hmac('md5', implode(':', $data), $api_key);
            $data['sign'] = strtolower($sign);
            $request = json_encode($data);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->post('https://capitalist.net/merchant/payGate/createorder', $data);
            
            file_put_contents('capitalistLogPost.txt', $response); 
            
            if ($response->successful()) {
                $order = $response->json();
                // Получаем модель по идентификатору, который был назначен при создании
                $operationToUpdate = HistoryOperation::find($newOperation->id);

                // Обновляем требуемое поле
                $operationToUpdate->billId = isset($order['order']['merchant']) ? $order['order']['merchant'] : null;

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
        $order = '';
        $nonce = time() . rand(); // Добавляем случайное значение для уменьшения вероятности повторения
        $siteSettingModel = siteSetting::find(1);
        $shopId = $siteSettingModel->capitalist_id;
        $api_key = $siteSettingModel->capitalist_secret;
        $operation = HistoryOperation::find($id);

            $data = [
                'merchantid' => $shopId,
                'order_number' => $operation->id,
                'amount' => $operation->amount,
                'currency' => 'RUR',
            ];

            ksort($data);
            $sign = hash_hmac('sha256', implode('|', $data), $api_key);
            $data['sign'] = $sign;
            $request = json_encode($data);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->post('https://capitalist.net/merchant/payGate/checkstate', ['json' => $data]);
            
            if ($response->successful()) {
                $order = $response->json();
                if (isset($order['data']) and $order['data']['order']['paid'] == true) {
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
}