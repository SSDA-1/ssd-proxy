<?php

namespace ssda1\proxies\Service;

use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Models\siteSetting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QiwiService
{
    /** @var \Qiwi\Api\BillPayments $billPayments */
    public function qiwiData($userId, $email, $rateName, $rateId, $rateCost)
    {
        $siteSettingModel = siteSetting::find(1);
        $billPayments = new \Qiwi\Api\BillPayments($siteSettingModel->qiwi_public);
        $lifetime = $billPayments->getLifetimeByDay(0.5);
        $billId = $billPayments->generateId();
        $newOperation = HistoryOperation::create([
            'type' => 'buySub',
            'status' => 0,
            'amount' => $rateCost,
            'notes' => 'Пополнение счёта через Qiwi',
            'billId' => $billId,
            'user_id' => $userId,
        ]);
        $urlSite = $_SERVER['SERVER_NAME'];
        $urlSucc = $urlSite.'/balancedone/'.$newOperation->id.'/q';
        $fields = [
            'amount' => $rateCost,
            'currency' => 'USDT',
            'comment' => 'Тариф ' . $rateName,
            'expirationDateTime' => $lifetime,
            'email' => $email,
            'account' => $userId,
            'successUrl' => $urlSucc,
        ];

        $response = $billPayments->createBill($billId, $fields);
        // dd($response);
        $payUrl = $response['payUrl'];

        return $payUrl;
    }
}
