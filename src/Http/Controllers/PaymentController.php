<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\WithdrawalRequest;
use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Models\User;
use ssda1\proxies\Models\Referral;
use ssda1\proxies\Models\SettingKraken;
use ssda1\proxies\Models\siteSetting;
use ssda1\proxies\Service\QiwiService;
use ssda1\proxies\Service\UsdtcheckerService;
use ssda1\proxies\Service\BetatransferService;
use ssda1\proxies\Service\CapitalistService;
use ssda1\proxies\Service\FreeKassaService;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class PaymentController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Ajax Сохранение настроек.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @return array
     */
    public function PaymentSettingsAdmin(Request $request)
    {

        $returnArray = [];
        $input = $request->all();
        $qiwiPay = !empty($input['qiwi_pay']) ? true : false;
        $youmoneyPay = !empty($input['youmoney_pay']) ? true : false;
        $demoPay = !empty($input['demo_pay']) ? true : false;
        $freekassa_pay = !empty($input['freekassa_pay']) ? true : false;
        $betatransfer_pay = !empty($input['betatransfer_pay']) ? true : false;
        $capitalist_pay = !empty($input['capitalist_pay']) ? true : false;
        $usdtchecker_pay = !empty($input['usdtchecker_pay']) ? true : false;

        $siteSettingModel = siteSetting::find(1);

        $siteSettingModel->qiwi_pay = $qiwiPay;
        $siteSettingModel->qiwi_public = $input['qiwi_public'];
        $siteSettingModel->qiwi_private = $input['qiwi_private'];
        $siteSettingModel->youmoney_pay = $youmoneyPay;
        $siteSettingModel->youmoney_public = $input['youmoney_public'];
        $siteSettingModel->youmoney_private = $input['youmoney_private'];

        $siteSettingModel->freekassa_pay = $freekassa_pay;
        $siteSettingModel->freekassa_id = $input['freekassa_id'];
        $siteSettingModel->freekassa_secret = $input['freekassa_secret'];

        $siteSettingModel->betatransfer_pay = $betatransfer_pay;
        $siteSettingModel->betatransfer_public = $input['betatransfer_public'];
        $siteSettingModel->betatransfer_secret = $input['betatransfer_secret'];

        $siteSettingModel->capitalist_pay = $capitalist_pay;
        $siteSettingModel->capitalist_id = $input['capitalist_id'];
        $siteSettingModel->capitalist_secret = $input['capitalist_secret'];

        $siteSettingModel->usdtchecker_pay = $usdtchecker_pay;
        $siteSettingModel->usdtchecker_token = $input['usdtchecker_token'];
        $siteSettingModel->usdtchecker_secret = $input['usdtchecker_secret'];

        $siteSettingModel->min_replenishment_amount = $input['min_replenishment_amount'];

        $siteSettingModel->demo_pay = $demoPay;

        try {
            $siteSettingModel->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование настройки платежных систем',
                "Ошибка! Настройки платежных систем $siteSettingModel->id не изменены",
                'Edit Payment System Settings',
                "Error! $siteSettingModel->id settings are unchanged"
            );
        }

        $this->log(
            'Редактирование настройки платежных систем',
            "Успешно! Настройки платежных систем $siteSettingModel->id изменены",
            'Edit Payment System Settings',
            "Successful! Settings for the $siteSettingModel->id payment systems changed"
        );

        $returnArray['status'] = $qiwiPay;

        // return $returnArray;
        return redirect()->route('allSettingSite')
            ->with('success', 'Настройки платёжных систем изменены.');
    }

    /**
     * Ajax Создание заявки на вывод денежных средств.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function sendPayment(Request $request)
    {
        // $balanceUser = Auth::user()->balance;
        // $validator = Validator::make($request->all(), [
        //     'sum' => 'required|numeric|between:50,{$balanceUser}',
        // ]);

        // if ($validator->fails()) {
        //     // Обработка ошибок валидации
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        // Получаем данные из формы
        $derivation = $request->input('derivation');
        $cardNumber = $request->input('card') ?: null;
        $walletNumber = $request->input('wallet') ?: null;
        $walletName = $request->input('name-wallet') ?: null;
        $usdtNumber = $request->input('usdt') ?: null;
        $capitalistNumber = $request->input('capitalist') ?: null;
        $sum = $request->input('sum') ?: null;
        $message = $request->input('message') ?: null;

        // Создание заявки
        $withdrawalRequest = new WithdrawalRequest();

        if ($cardNumber) {
            $withdrawalRequest->card_number = $cardNumber;
            $withdrawalRequest->name_ecash = 'Карта';
        }elseif ($walletNumber) {
            $withdrawalRequest->card_number = $walletNumber;
            if ($walletName) {
                $withdrawalRequest->name_ecash = $walletName;
            }
        }elseif ($usdtNumber) {
            $withdrawalRequest->card_number = $usdtNumber;
            $withdrawalRequest->name_ecash = 'USDT';
        }elseif ($capitalistNumber) {
            $withdrawalRequest->card_number = $capitalistNumber;
            $withdrawalRequest->name_ecash = 'Capitalist';
        }

        $withdrawalRequest->amount = $sum;
        $withdrawalRequest->massage = $message;
        $withdrawalRequest->user_id = auth()->id();
        $withdrawalRequest->status = 0;

        try {
            $withdrawalRequest->save();
        } catch (\Exception $exception) {
            $this->log(
                'Создание заявки на вывод денежных средств',
                "Ошибка! Заявка на вывод денежных средств $withdrawalRequest->id не создана",
                'Creating a request for withdrawals',
                "Error! Application for a $withdrawalRequest->id not created"
            );
        }

        $this->log(
            'Создание заявки на вывод денежных средств',
            "Успешно! Заявка на вывод денежных средств $withdrawalRequest->id создана",
            'Creating a request for withdrawals',
            "Succeed! Application for a $withdrawalRequest->id created"
        );

        // Возвращаем ответ клиенту
        return response()->json(['success' => true]);
    }

    /**
     * Ajax Проверка статуса USDT.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function chackUSDTStatus(Request $request,$id)
    {
        // Получаем данные из формы
        $operation = $id;
        $historyOperation = HistoryOperation::find($operation);
        // Возвращаем ответ клиенту
        if ($historyOperation->status >= 1) {
            $this->log(
                'Проверка статуса USDT',
                "Успешно! Статус $historyOperation->id - $historyOperation->status",
                'USDT status check',
                "Success! Status $historyOperation->id - $historyOperation->status"
            );

            return response()->json(['success' => true]);
        } else {
            $this->log(
                'Проверка статуса USDT',
                "Ошибка! Статус $historyOperation->id - $historyOperation->status",
                'USDT status check',
                "Error! Status $historyOperation->id"
            );

            return response()->json(['success' => false]);
        }
    }

    /**
     * Ajax Пополнение.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function PaymentPlusMoney(Request $request): array
    {
        // $this->validate($request, [
        //     'amount' => 'required|regex:/^[1-9][0-9]*$/'
        // ]);

        $returnArray = [];
        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        function processReferrals($userId, $amountReq) {
            $siteSettingModel = siteSetting::find(1);
            // Выполняем запрос на выборку соответствующих записей из таблицы referrals
            $referrals = Referral::where('user_id', $userId)->get();

            // Получаем пользователей для каждой записи и выводим их ID
            foreach ($referrals as $referral) {
                $userIdRef = $referral->referral->id;
                $userRef = User::find($userIdRef);
                $percent = $siteSettingModel->deposit_percentage; // Пример: 10% (десять процентов)
                $amount = $amountReq; // Пример: 1000 рублей
                $fee = $amount * ($percent / 100); // Высчитываем комиссию: 1000 * (10 / 100) = 100
                $balanceBefore = $userRef->referral_balance;
                if ($siteSettingModel->referral_balance_enabled == 1) {
                    $userRef->referral_balance += $fee;
                }else{
                    $userRef->balance += $fee;
                }
                $userRef->save();
                $balanceAfter = $userRef->referral_balance;
                $historyModelRef = new HistoryOperation;
                $historyModelRef->type = 'plus';
                $historyModelRef->amount = $fee;
                $historyModelRef->notes = 'Процент с реферала';
                $historyModelRef->balance_before = $balanceBefore;
                $historyModelRef->balance_after = $balanceAfter;
                $historyModelRef->user_id = $userRef->id;
                $historyModelRef->referred_by = $userId;
                $historyModelRef->save();
            }
        }

        $input = $request->all();
        $amountReq = $input['balance'];
        if ($input['gateway'] == "demo") {
            $balance = $balance + $amountReq;
            $user->balance = $balance;
            $savedBalance = $user->save();
            $notesReq = 'Пополнение баланса с Демо сайта';
            if ($savedBalance) {
                $returnArray['status'] = true;
                $returnArray['balance'] = $balance;
                $returnArray['amount'] = $amountReq;
                $returnArray['operation'] = 'payment';
                $current = Carbon::now();
                $currentOne = $current->format('m.d.Y');
                $currentTwo = $current->format('h:s');
                $returnArray['date'] = '<span>' . $currentOne . '</span><span>в ' . $currentTwo . '</span>';
                $historyModel = new HistoryOperation;
                $historyModel->type = 'plus';
                $historyModel->amount = $amountReq;
                $historyModel->notes = $notesReq;
                $historyModel->user_id = $userID;


                try {
                    $historyModel->save();
                    processReferrals($userID, $amountReq);
                } catch (\Exception $exception) {
                    $this->log(
                        'Пополнение реферальное',
                        "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                        'Referral replenishment',
                        "Error! (User $user->id with $user->email) No balance replenished"
                    );

                }

                $this->log(
                    'Пополнение реферальное',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Referral replenishment',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );


                // // Выполняем запрос на выборку соответствующих записей из таблицы referrals
                // $referrals = Referral::where('user_id', $user->id)->get();
                // // Получаем пользователей для каждой записи и выводим их ID
                // foreach ($referrals as $referral) {
                //     $userIdRef = $referral->referral->id;
                //     $userRef = User::find($userIdRef);
                //     $percent = 10; // Пример: 10% (десять процентов)
                //     $amount = $amountReq; // Пример: 1000 рублей
                //     $fee = $amount * ($percent / 100); // Высчитываем комиссию: 1000 * (10 / 100) = 100
                //     $userRef->balance += $fee;
                //     $userRef->save();
                //     $historyModelRef = new HistoryOperation;
                //     $historyModelRef->type = 'ref';
                //     $historyModelRef->amount = $fee;
                //     $historyModelRef->notes = 'Процент с реферала';
                //     $historyModelRef->user_id = $userRef->id;
                //     $historyModelRef->referred_by = $userID;
                //     $historyModelRef->save();
                // }

            } else {
                $this->log(
                    'Пополнение реферальное',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Referral replenishment',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                $returnArray['status'] = false;
            }
        } elseif ($input['gateway'] == "yoomoney") {
            $this->log(
                'Пополнение yoomoney',
                "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                'Adding yoomoney',
                "Error! (User $user->id with $user->email) No balance replenished"
            );

            $returnArray['status'] = false;
            $returnArray['modal'] = true;
            $returnArray['massage'] = 'Произошла ошибка, Платёжная система не активирована. И выведена только для демонстрации';
            $returnArray['title'] = 'Ошибка пополнения';
            // processReferrals($userId, $amountReq);
        } elseif ($input['gateway'] == "qiwi") {
            $userId = Auth::user()->id;
            $email = Auth::user()->email;
            $rateName = 'Пополнение баланса';
            $rateId = $userId;
            $rateCost = $amountReq;
            // $rateCost = 1;

                $qiwi = new QiwiService();
                $qiwi = $qiwi->qiwiData($userId, $email, $rateName, $rateId, $rateCost);

                // processReferrals($userId, $amountReq);

            if($qiwi != null){
                $this->log(
                    'Пополнение qiwi',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Qiwi replenishment',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );

                return redirect($qiwi);
            }else{
                $this->log(
                    'Пополнение qiwi',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Qiwi replenishment',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка, Платёжная система не активирована. И выведена только для демонстрации';
                $returnArray['title'] = 'Ошибка пополнения';
            }
        } elseif ($input['gateway'] == "freekassa") {
            $ip = $request->ip();
            $freeKassa = new FreeKassaService();
            $freeKassa = $freeKassa->getPaySCI($amountReq);
            // if (isset($freeKassa['type']) && $freeKassa['type'] !== 'success') {
            if(isset($freeKassa)){
                $this->log(
                    'Пополнение freeKassa',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Refill FreeKassa',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );

                $returnArray['status'] = true;
                $returnArray['modal'] = true;
                $returnArray['operation'] = 'freekassa';
                $returnArray['url'] = $freeKassa;
            } else {
                $this->log(
                    'Пополнение freeKassa',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Refill FreeKassa',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                $returnArray['data'] = $freeKassa;
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка.';
                $returnArray['title'] = 'Ошибка пополнения';
            }
        } elseif ($input['gateway'] == "capitalist") {
            $capitalist = new CapitalistService();
            $capitalist = $capitalist->getPay($amountReq);
            if (!isset($capitalist['errors'])) {
                // return redirect($capitalist['order']['paymentUrl']);
                $this->log(
                    'Пополнение capitalist',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Capitalist Replenishment',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );

                $returnArray['status'] = true;
                $returnArray['modal'] = true;
                $returnArray['operation'] = 'capitalist';
                $returnArray['url'] = $capitalist['order']['paymentUrl'];
            } else {
                $this->log(
                    'Пополнение capitalist',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Capitalist Replenishment',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                $returnArray['data'] = $capitalist;
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка.';
                $returnArray['title'] = 'Ошибка пополнения';
            }
        } elseif ($input['gateway'] == "betatransfer") {
            $betatransfer = new BetatransferService();
            $betatransfer = $betatransfer->getPay($amountReq);
            if (isset($betatransfer['status']) && $betatransfer['status'] !== 'success') {
                $this->log(
                    'Пополнение betatransfer',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Depositing betatransfer',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );

                return redirect($betatransfer['urlPayment']);
            } else {
                $this->log(
                    'Пополнение betatransfer',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Depositing betatransfer',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                $returnArray['data'] = $betatransfer;
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка.';
                $returnArray['title'] = 'Ошибка пополнения';
            }

        } elseif ($input['gateway'] == "usdtchecker") {
            $usdtchecker = new UsdtcheckerService();
            $usdtchecker = $usdtchecker->getPay($amountReq);
            $returnArray['data'] = json_decode($usdtchecker);
            if (isset($returnArray['data']->status) == true) {
                // $user->order_id = $returnArray['data']->order_id;
                // $returnArray['data'] = $usdtchecker;

                $this->log(
                    'Пополнение usdtchecker',
                    "Успешно! (Пользователь $user->id с почтой $user->email) Баланс пополнен",
                    'Add usdtchecker',
                    "Successful! (User $user->id with $user->email) Balance replenished"
                );

                $returnArray['status'] = true;
                $returnArray['modal'] = true;
                $returnArray['operation'] = 'usdtchecker';
                $returnArray['url'] = $returnArray['data']->url_to_pay;
                // return redirect($returnArray['data']->url_to_pay);

            } else {
                $this->log(
                    'Пополнение usdtchecker',
                    "Ошибка! (Пользователь $user->id с почтой $user->email) Баланс не пополнен",
                    'Add usdtchecker',
                    "Error! (User $user->id with $user->email) No balance replenished"
                );

                if (isset($returnArray['data']->error_code) == 10) {
                    $returnArray['title'] = 'Ошибка пополнения. Проблемы в балансе';
                }else{
                    $returnArray['title'] = 'Ошибка пополнения. Проблемы в балансе';
                }

                $returnArray['data'] = json_decode($usdtchecker);
                // $returnArray['id'] = $historyModelRef->id;
                $returnArray['status'] = false;
                $returnArray['modal'] = true;
                $returnArray['massage'] = 'Произошла ошибка.-'.$usdtchecker;
            }
        }

        // $returnArray['status'] = true;
        // $returnArray['modal'] = true;
        // $returnArray['operation'] = 'usdtchecker';

        $returnArray['input'] = $input;

        return $returnArray;
    }

    /**
     * Make an order (record the project in the database)
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @var \Qiwi\Api\BillPayments $billPayments
     */
    public function PaymentBalanceDone(Request $request,int $id,$payment)
    {
        $userEmail = Auth::user()->email;
        $userId = Auth::user()->id;
        if ($payment == 'q') {
            $siteSettingModel = siteSetting::find(1);
            $qiwi_public = $siteSettingModel->qiwi_public;// qiwi_public
            $billPayments = new \Qiwi\Api\BillPayments($qiwi_public);
            $operation = HistoryOperation::find($id);
            if ($userId == $operation->user->id) {
                $billId = $operation->billId;

                $response = $billPayments->getBillInfo($billId);
                // dd($response);
                if ($response->status->value == 'PAID') {
                    processReferrals($userId, $operation->amount);

                    $this->log(
                        'Оплата реферальная',
                        "Успешно! (Пользователь $userId с почтой $userEmail) Совершил покупку",
                        'Referral payment',
                        "Succeed! (User $userId with $userEmail) Made a purchase"
                    );

                    return redirect('userLK')->with('success', 'Вы успешно совершили покупку!');
                }else{
                    $this->log(
                        'Оплата реферальная',
                        "Успешно! (Пользователь $userId с почтой $userEmail) Ожидаем подтверждение",
                        'Referral payment',
                        "Succeed! ($userId user with $userEmail) Pending confirmation"
                    );

                    return redirect('userLK')->with('success', 'Ожидаем подтверждения оплаты!');
                }

            }else{
                $this->log(
                    'Оплата реферальная',
                    "Ошибка! (Пользователь $userId с почтой $userEmail) Оплата не прошла",
                    'Referral payment',
                    "Error! ($userId user with $userEmail mail) Payment failed"
                );

                return redirect('userLK')->with('error', 'Оплата не прошла!');
            }
        }elseif ($payment == 'b') {
            $betatransfer = new BetatransferService();
            $betatransfer = $betatransfer->checkPay($id);
            if ($betatransfer == true) {
                $this->log(
                    'Оплата betatransfer',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Совершил покупку",
                    'Betatransfer payment',
                    "Succeed! (User $userId with $userEmail) Made a purchase"
                );

                return redirect('userLK')->with('success', 'Вы успешно совершили покупку!');
            }else{
                $this->log(
                    'Оплата betatransfer',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Ожидаем подтверждение",
                    'Betatransfer payment',
                    "Succeed! ($userId user with $userEmail) Pending confirmation"
                );

                return redirect('userLK')->with('success', 'Ожидаем подтверждения оплаты!');
            }
        }elseif ($payment == 'fk') {
            $freeKassa = new FreeKassaService();
            $freeKassa = $freeKassa->checkPay($id);
            if ($freeKassa == true) {
                $this->log(
                    'Оплата freeKassa',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Совершил покупку",
                    'FreeKassa payment',
                    "Succeed! (User $userId with $userEmail) Made a purchase"
                );

                return redirect('userLK')->with('success', 'Вы успешно совершили покупку!');
            }else{
                $this->log(
                    'Оплата freeKassa',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Ожидаем подтверждение",
                    'FreeKassa payment',
                    "Succeed! ($userId user with $userEmail) Pending confirmation"
                );

                return redirect('userLK')->with('success', 'Ожидаем подтверждения оплаты!');
            }
        }elseif ($payment == 'c') {
            $capitalist = new CapitalistService();
            $capitalist = $capitalist->checkPay($id);
            if ($capitalist == true) {
                $this->log(
                    'Оплата capitalist',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Совершил покупку",
                    'Paying capitalist',
                    "Succeed! (User $userId with $userEmail) Made a purchase"
                );

                return redirect('userLK')->with('success', 'Вы успешно совершили покупку!');
            }else{
                $this->log(
                    'Оплата capitalist',
                    "Успешно! (Пользователь $userId с почтой $userEmail) Ожидаем подтверждение",
                    'Paying capitalist',
                    "Succeed! ($userId user with $userEmail) Pending confirmation"
                );

                return redirect('userLK')->with('success', 'Ожидаем подтверждения оплаты!');
            }
        }

        // return redirect('personal')->with('success', $response);
    }

    public function interactionСapitalist(Request $request)
    {
        function processReferrals($userId, $amountReq) {
            $siteSettingModel = siteSetting::find(1);
            // Выполняем запрос на выборку соответствующих записей из таблицы referrals
            $referrals = Referral::where('user_id', $userId)->get();
            // $user = User::find($userId);
            // $user->balance += $amountReq;
            // $user->save();

            // Получаем пользователей для каждой записи и выводим их ID
            foreach ($referrals as $referral) {
                $userIdRef = $referral->referral->id;
                $userRef = User::find($userIdRef);
                $percent = $siteSettingModel->deposit_percentage; // Пример: 10% (десять процентов)
                $amount = $amountReq; // Пример: 1000 рублей
                $fee = $amount * ($percent / 100); // Высчитываем комиссию: 1000 * (10 / 100) = 100
                $balanceBefore = $userRef->referral_balance;
                if ($siteSettingModel->referral_balance_enabled == 1) {
                    $userRef->referral_balance += $fee;
                }else{
                    $userRef->balance += $fee;
                }
                $userRef->save();
                $balanceAfter = $userRef->referral_balance;
                $historyModelRef = new HistoryOperation;
                $historyModelRef->type = 'plus';
                $historyModelRef->amount = $fee;
                $historyModelRef->notes = 'Процент с реферала';
                $historyModelRef->balance_before = $balanceBefore;
                $historyModelRef->balance_after = $balanceAfter;
                $historyModelRef->user_id = $userRef->id;
                $historyModelRef->referred_by = $userId;
                $historyModelRef->save();
            }
        }

        $order = $request->all();
        file_put_contents('СapitalistLogWebhoock.txt', $order);
        if (isset($order['merchant_id'])) {
            $operation = HistoryOperation::find($order['number']);
            if ($operation->status != 1) {
                $operation->status = 1;
                // Сохраняем изменения
                $operation->save();
                $user = $operation->user;
                $user->balance += $operation->amount;
                $user->save();
                processReferrals($operation->user->id, $operation->amount);
            }
            $order = true;
        }else{
            $order = false;
        }

        $return = $order;

        file_put_contents('СapitalistLogWebhoock.txt', $content);

        return response()->json([
            'status' => true,
        ]);
    }

    public function PaymentBalanceDoneWebhook(Request $request)
    {
        function processReferrals($userId, $amountReq) {
            $siteSettingModel = siteSetting::find(1);
            // Выполняем запрос на выборку соответствующих записей из таблицы referrals
            $referrals = Referral::where('user_id', $userId)->get();

            // Получаем пользователей для каждой записи и выводим их ID
            foreach ($referrals as $referral) {
                $userIdRef = $referral->referral->id;
                $userRef = User::find($userIdRef);
                $percent = $siteSettingModel->deposit_percentage; // Пример: 10% (десять процентов)
                $amount = $amountReq; // Пример: 1000 рублей
                $fee = $amount * ($percent / 100); // Высчитываем комиссию: 1000 * (10 / 100) = 100
                $balanceBefore = $userRef->referral_balance;
                if ($siteSettingModel->referral_balance_enabled == 1) {
                    $userRef->referral_balance += $fee;
                }else{
                    $userRef->balance += $fee;
                }
                $userRef->save();
                $balanceAfter = $userRef->referral_balance;

                $historyModelRef = new HistoryOperation;
                $historyModelRef->type = 'plus';
                $historyModelRef->amount = $fee;
                $historyModelRef->notes = 'Процент с реферала';
                $historyModelRef->balance_before = $balanceBefore;
                $historyModelRef->balance_after = $balanceAfter;
                $historyModelRef->user_id = $userRef->id;
                $historyModelRef->referred_by = $userId;
                $historyModelRef->save();
            }
        }

        $order = $request->all();
        file_put_contents('freekassaLog.txt', $order['MERCHANT_ORDER_ID']);
        if (isset($order['MERCHANT_ORDER_ID'])) {
            $operation = HistoryOperation::find($order['MERCHANT_ORDER_ID']);
            if ($operation->status != 1) {
                $operation->status = 1;
                // Сохраняем изменения
                $operation->save();
                $user = User::find($operation->user->id);
                $user->balance += $operation->amount;
                $user->save();

                processReferrals($operation->user->id, $operation->amount);
                $order = true;
            }
        }else{
            $order = false;
        }

        $return = $order;

        return $return;

        // Получаем данные из запроса
        $content = json_encode($request->all());

        file_put_contents('freekassaLog.txt', $content);

        return response()->json([
            'status' => true,
        ]);
    }



    public function webhookUSDTchecker(Request $request)
    {
        // Получаем данные из запроса
        $content = json_encode($request->all());
        try {
            file_put_contents('example.txt', $content);
            function processReferrals($userId, $amountReq) {
                $siteSettingModel = siteSetting::find(1);
                // Выполняем запрос на выборку соответствующих записей из таблицы referrals
                $referrals = Referral::where('user_id', $userId)->get();
                $user = User::find($userId);
                // $user->balance += $amountReq;
                // $user->save();

                // Получаем пользователей для каждой записи и выводим их ID
                foreach ($referrals as $referral) {
                    $userIdRef = $referral->referral->id;
                    $userRef = User::find($userIdRef);
                    $percent = $siteSettingModel->deposit_percentage; // Пример: 10% (десять процентов)
                    $amount = $amountReq; // Пример: 1000 рублей
                    $fee = $amount * ($percent / 100); // Высчитываем комиссию: 1000 * (10 / 100) = 100
                    $balanceBefore = $userRef->referral_balance;
                    if ($siteSettingModel->referral_balance_enabled == 1) {
                        $userRef->referral_balance += $fee;
                    }else{
                        $userRef->balance += $fee;
                    }
                    $userRef->save();
                    $balanceAfter = $userRef->referral_balance;
                    $historyModelRef = new HistoryOperation;
                    $historyModelRef->type = 'plus';
                    $historyModelRef->amount = $fee;
                    $historyModelRef->notes = 'Процент с реферала';
                    $historyModelRef->balance_before = $balanceBefore;
                    $historyModelRef->balance_after = $balanceAfter;
                    $historyModelRef->user_id = $userRef->id;
                    $historyModelRef->referred_by = $userId;
                    $historyModelRef->save();
                }
            }

            $id_contract = $request->input('id_contract');
            $amount = $request->input('amount');
            $amount_for_pay = $request->input('amount_for_pay');
            $hash_trans = $request->input('hash_trans');
            $unixtime = $request->input('unixtime');
            $signature = $request->input('signature');
            $order_id = $request->input('order_id');
            $historyOperation = HistoryOperation::find($order_id);

            // Возвращаем ответ клиенту
            // file_put_contents('example.txt', $request->input('id_track'));
            if ($request->input('status') == 'paid') {

            if ($historyOperation->status != 1) {
                $historyOperation->status = 1;
                $historyOperation->user->increment('balance', $historyOperation->amount);
                $historyOperation->save();
            }
                // $user = $transaction->user;
                // $user->balance += $historyOperation->amount;
                // $user->save();
                return response()->json([
                    'id_track' => $request->input('id_track'),
                ]);
            }
        } catch (\Throwable $e) {
            // Записываем ошибку в файл
            file_put_contents('errorPayUSDT.txt', 'Синтаксическая ошибка: ' . $e->getMessage());

            // Дополнительная обработка ошибки, если нужно
            // ...
        }
        // Проверяем подпись
        // $token = 'YOUR_TOKEN'; // сюда введите свой токен
        // $secret_key = 'YOUR_SECRET_KEY'; // сюда введите свой секретный ключ
        // $expected_signature = md5($token . $amount_for_pay . $secret_key . $hash_trans);

        // if ($signature === $expected_signature) {
        //     // Подпись верна, можно продолжать обработку

        //     // ... здесь ваш код обработки вебхука ...

        //     // Возвращаем ответ
        //     return response()->json(['id_contract' => $id_contract]);
        // } else {
        //     // Подпись неверна, лучше не делать ничего и вернуть ошибку
        //     return response()->json(['error' => 'Invalid signature'], 400);
        // }
    }
    public function chackUSDTSuccess(Request $request)
    {
        // $content = json_encode($request->all());
        // $content .= '-- Success';
        // file_put_contents('example.txt', $content);
        // return response()->json([
        //     'id_track' => $request->input('id_track'),
        // ]);

        // file_put_contents('example.txt', $content);
        $status = $request->input('status');

        if ($status === 'paid') {
            return redirect()->to('/lk')->with('success', 'Оплата прошла успешно!');
        }else{
            return redirect()->to('/lk')->withErrors(['error' => 'Ошибка при обработке оплаты']);
        }
    }



    public function checkUSDTfail(Request $request)
    {
        $status = $request->input('status');

        if ($status === 'paid') {
            return redirect()->to('/lk')->with('success', 'Оплата прошла успешно!');
        }else{
            return redirect()->to('/lk')->withErrors(['error' => 'Ошибка при обработке оплаты']);
        }
    }
}
