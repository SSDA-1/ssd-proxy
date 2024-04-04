<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\User;
use ssda1\proxies\Models\ProcessLog;
use ssda1\proxies\Models\HistoryOperation;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class HistoryOperationController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Ajax Покупка Прокси.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function balanceChanges(Request $request): RedirectResponse
    {
        if ($request->input('amount') > 0) {
            $this->validate($request, [
                'amount' => 'required|regex:/^[1-9][0-9]*$/|min:1'
            ]);
        }
        if ($request->input('referral_amount') > 0) {
            $this->validate($request, [
                'referral_amount' => 'required|regex:/^[1-9][0-9]*$/|min:1'
            ]);

            $referralAmount = $request->input('referral_amount');
        }

        $returnArray = [];

        $amountReq = $request->input('amount');
        $idUserReq = $request->input('id');
        $typeOperationReq = $request->input('type');
        $notesReq = $request->input('notes');
        $referralAmount = $request->input('referral_amount');

        $user = User::find($idUserReq);
        $balance = $user->balance;
        $referralBalance = $user->referral_balance;
        if ($typeOperationReq === 'plus') {
            $balance = $balance + $amountReq;
            $user->balance = $balance;
            if ($referralAmount > 0) {
                $referralBalance = $referralBalance + $referralAmount;
                $user->referral_balance = $referralBalance;
            }
        } else if ($typeOperationReq === 'minus') {
            $balance = $balance - $amountReq;
            $user->balance = $balance;
            if ($referralAmount > 0) {
                $referralBalance = $referralBalance - $referralAmount;
                $user->referral_balance = $referralBalance;
            }
        }

        $savedBalance = $user->save();

        if ($savedBalance) {
            $returnArray['status'] = true;
            $historyModel = new HistoryOperation;
            $historyModel->type = $typeOperationReq;
            if ($typeOperationReq === 'plus') {
                if ($request->input('amount') > 0) {
                    $historyModel->amount = $amountReq;
                    $historyModel->notes = "Пополнение баланса администратором";
                } elseif ($request->input('referral_amount') > 0) {
                    $historyModel->amount = $referralAmount;
                    $historyModel->notes = "Пополнение реферального баланса администратором";
                }
            } else if ($typeOperationReq === 'minus') {
                if ($request->input('amount') > 0) {
                    $historyModel->amount = $amountReq;
                    $historyModel->notes = "Списание с баланса администратором";
                } elseif ($request->input('referral_amount') > 0) {
                    $historyModel->amount = $referralAmount;
                    $historyModel->notes = "Списание с реферального баланса администратором";
                }
            }

            $historyModel->user_id = $idUserReq;
            $historyModel->save();
        } else {
            $this->log(
                'Изменение баланса',
                "Ошибка! Баланс у пользователя $user->id с почтой $user->email не изменился",
                'Change in the balance',
                "Error! User’s $user->id balance with $user->email has not changed"
            );

            return redirect()->back()
                ->withInput()
                ->withErrors('Что-то пошло не по плану');
        }

        $this->log('Изменение баланса',
            "Успешно! Баланс у пользователя $user->id с почтой $user->email изменился на $amountReq",
            'Change in the balance',
            "Successful! Your $user->id with $user->email changed to $amountReq"
        );

        return redirect()->back()
            ->with('success', 'Успех!');
    }
}
