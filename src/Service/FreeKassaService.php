<?php

namespace ssda1\proxies\Service;

use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Models\siteSetting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FreeKassaService
{
    public function getPay($id,$amount)
    {
        $user = Auth::user();
        $ip = '';
        $order = '';
        // Get the last used nonce from the database or any other persistent storage
        // Найдите последнюю запись в таблице HistoryOperation с полем notes равным 'Пополнение счёта через Freekassa'
        $lastRecord = HistoryOperation::where('notes', 'Пополнение счёта через Freekassa')->latest()->first();

        // Generate a new nonce that is greater than the last one
        $nonce = 0;
        if ($lastRecord) {
            $billld = $lastRecord->billId;

            $billld = intval($billld) + 1;

            $nonce = $billld;

        }

        // $nonce = '16940808461673517001'; // Добавляем случайное значение для уменьшения вероятности повторения  . rand()
        $siteSettingModel = siteSetting::find(1);
        $shopId = '39262';//$siteSettingModel->freekassa_id;
        $api_key = 'ade7eb3204ec2ec59fbe3b40f04b98f5';//$siteSettingModel->freekassa_secret;
        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через Freekassa',
            'billId' => $nonce,
            'user_id' => $user->id,
        ]);

        $urlSite = $_SERVER['SERVER_NAME'];
        $urlSucc = $urlSite.'/balancedone/'.$newOperation->id.'/fk';

            $data = [
                'shopId' => 39262,
                'nonce' => $nonce,
                'i' => '15',
                'paymentId' => $newOperation->id,
                'email' => $user->email,
                'ip' => $ip,
                'amount' => $amount,
                'currency' => 'USDT',
                // 'success_url' => $urlSucc,
            ];

            ksort($data);
            $sign = hash_hmac('sha256', implode('|', $data), $api_key);
            $data['signature'] = $sign;
            $request = json_encode($data);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post('https://api.freekassa.ru/v1/orders/create', $data);

            // Получаем данные из запроса
            $content = json_encode($response->json());

            file_put_contents('freekassaLogPost.txt', $content);

            if ($response->successful()) {
                $order = $response->json();
                // Получаем модель по идентификатору, который был назначен при создании
                $operationToUpdate = HistoryOperation::find($newOperation->id);

                // Обновляем требуемое поле
                $operationToUpdate->billId = isset($order['orderId']) ? $order['orderId'] : null;

                // Сохраняем изменения
                $operationToUpdate->save();
            } else {
                $order = $response->json();
            }
        // dd($response);
            file_put_contents('freekassaLogPost.txt', $billld);
        $payUrl = $order;

        return $payUrl;
    }

    public function getPaySCI($amount)
    {
        $user = Auth::user();
        $ip = '';
        $order = '';
        // Получаем значения переменных из вашего контроллера
        $merchant_id = '39262';
        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через FreeKassa',
            'billId' => '',
            'user_id' => $user->id,
        ]);
        $order_id = $newOperation->id;

        $order_amount = $amount;
        $currency = 'USD';
        $secret_word = '?=h5n4)Y2eU_/,^';

        // Создаем подпись
        $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$currency.':'.$order_id);

        // Формируем URL-адрес с параметрами
        $payUrl = 'https://pay.freekassa.ru/?m='.$merchant_id.'&oa='.$order_amount.'&o='.$order_id.'&s='.$sign.'&currency='.$currency.'&i=&lang=ru';

        return $payUrl;
    }
    public function checkPay($ip, $amount)
    {
        $user = Auth::user();
        $order = '';
        $nonce = time() . rand(); // Добавляем случайное значение для уменьшения вероятности повторения
        $siteSettingModel = siteSetting::find(1);
        $shopId = $siteSettingModel->freekassa_id;
        $api_key = $siteSettingModel->freekassa_secret;
        $operation = HistoryOperation::find($id);


            $data = [
                'shopId' => $shopId,
                'nonce' => $nonce,
                'orderId' => $operation->billId,
            ];

            ksort($data);
            $sign = hash_hmac('sha256', implode('|', $data), $api_key);
            $data['signature'] = $sign;
            $request = json_encode($data);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post('https://api.freekassa.ru/v1/orders', ['json' => $data]);

            if ($response->successful()) {
                $order = $response->json();
                if (isset($order['type']) and $order['type'] == 'success') {
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
