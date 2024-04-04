<?php

namespace ssda1\proxies\Service;

use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Models\siteSetting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class UsdtcheckerService
{
    public function getPay($amount)
    {
        $user = Auth::user();
        $order = '';
        $siteSettingModel = siteSetting::find(1);
        $token = '9fCX4Ri9B8le6C8VI66QIznOAY99sWiR6YR';//$siteSettingModel->usdtchecker_token;//'gVUZiIU5uAi5zbii';//
        $apiKey = '';//$siteSettingModel->usdtchecker_secret;//'k0xUOx3veZ5s6j2d';//
        $secretKey = 'c4kH66C1P8MgD28JdeN6w16i11f1655I7Kx';
        $login = 'vxcactus@yahoo.com';
        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $amount,
            'notes' => 'Пополнение счёта через Usdtchecker',
            'billId' => '',
            'user_id' => $user->id,
        ]);
        $order_id = $newOperation->id;
        // $payload = [
        //     'amount' => $amount,
        //     'token' => $token,
        //     'order_id' => $newOperation->id,
        //     'sign' => md5($amount.$apiKey.$newOperation->id), // рассчитываем сигнатуру
        // ];


        // Calculate the signature
        $signature = hash('sha256', "{$amount}{$secretKey}{$order_id}{$login}");

        // Construct the POST request
        $data = [
            'login' => $login,
            'amount' => $amount,
            'token' => $token,
            'order_id' => $order_id,
            'signature' => $signature,
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'content' => http_build_query($data),
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
            ]
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents('https://zerocryptopay.com/pay/newtrack/', false, $context);
        // $payload = [
        //     'login' => 'vxcactus@yahoo.com',
        //     'amount' => $amount,
        //     'token' => $token,
        //     'order_id' => $newOperation->id,
        //     'sign' => hash('sha256', $amount . $secretKey . $newOperation->id . $login),//md5($amount.$apiKey.$newOperation->id), // рассчитываем сигнатуру
        // ];

        // session_start();
        // $sessionId = session_id();
        // $cookie = 'PHPSESSID=' . $sessionId;

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://zerocryptopay.com/pay/newtrack/',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => http_build_query($payload),
        // CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/x-www-form-urlencoded',
        // ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        //


            // if ($response) {
            //     $order = $response->body();
            //     // Получаем модель по идентификатору, который был назначен при создании
            //     $operationToUpdate = HistoryOperation::find($newOperation->id);

            //     // Обновляем требуемое поле
            //     $operationToUpdate->billId = isset($order['id']) ? $order['id'] : null;

            //     // Сохраняем изменения
            //     $operationToUpdate->save();
            // } else {
            //     $order = $response->body();
            // }
        // dd($response);
        // $response['idtransaction'] = $newOperation->id;

        // декодирование json-строки в ассоциативный массив
        $data = json_decode($response, true);

        // добавление нового поля
        $data['idtransaction'] = $newOperation->id;

        // преобразование обратно в JSON-формат
        $new_response = json_encode($data);
        $payUrl = $response;

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
