<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\Referral;
use ssd\proxies\Models\HistoryOperation;
use ssd\proxies\Models\Advantag;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\siteSetting;
use ssd\proxies\Models\User;
use ssd\proxies\Models\WithdrawalRequest;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $referrerReferralsAll = Referral::where('referred_by', $user->id)->count();
        $referrerReferralsTodey = Referral::where('referred_by', $user->id)->whereDate('created_at', Carbon::today())->count();
        $referrerReferralsWeek = Referral::where('referred_by', $user->id)->whereDate('created_at', '>=', Carbon::now()->subDays(7))->count();
        $referrerReferralsMontch = Referral::where('referred_by', $user->id)->whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
        if (!$user->referral_code) {
            $referralCode = Str::random(8);
            $user->referral_code = $referralCode;
            $user->save();
        }
        $withdrawalRequest = WithdrawalRequest::where('user_id', $user->id)->get();
        $referrerHistoryOperation = HistoryOperation::where('user_id', $user->id)->whereNotNull('referred_by')->get();
        $siteSettingModel = siteSetting::find(1);
        return view('proxies::admin.lk.referral', compact('referrerReferralsAll','referrerReferralsTodey','referrerReferralsWeek','referrerReferralsMontch','withdrawalRequest','referrerHistoryOperation','siteSettingModel'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistic()
    {
        $users = User::all();
        // $referrerReferralsAll = Referral::where('referred_by', $user->id)->count();
        // $referrerReferralsTodey = Referral::where('referred_by', $user->id)->whereDate('created_at', Carbon::today())->count();
        // $referrerReferralsWeek = Referral::where('referred_by', $user->id)->whereDate('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        $data = [];
        foreach ($users as $user) {
            // запросы к таблице Referral для каждого пользователя
            $referrerReferralsMonth = Referral::where('referred_by', $user->id)->whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
            $countPlusMoney = HistoryOperation::where('user_id', '=', $user->id)
                    ->where('type', '=', 'plus')
                    ->count();
                                
            $countPlusMoneyMonth = HistoryOperation::where('user_id', '=', $user->id)
            ->where('type', '=', 'plus')
            ->whereMonth('created_at', '>=', Carbon::now()->subMonth())
            ->count();
            $withdrawalRequestsAll = WithdrawalRequest::where('user_id', $user->id)->count();
            $withdrawalRequestsAmountSum = WithdrawalRequest::where('user_id', $user->id)->sum('amount');
            $lastWithdrawalRequestDate = WithdrawalRequest::where('user_id', $user->id)->where('status', 2)->latest('created_at')->value('updated_at');
            // ...
        
            $data[] = [
                'user' => $user,
                'countPlusMoney' => $countPlusMoney,
                'countPlusMoneyMonth' => $countPlusMoneyMonth,
                'referrerReferralsMonth' => $referrerReferralsMonth,
                'withdrawalRequestsAll' => $withdrawalRequestsAll,
                'withdrawalRequestsAmountSum' => $withdrawalRequestsAmountSum,
                'lastWithdrawalRequestDate' => $lastWithdrawalRequestDate
            ];
        }
        $referrerHistoryOperation = HistoryOperation::where('user_id', $user->id)->where('type', 'ref')->get();
        $siteSettingModel = siteSetting::find(1);
        $collection = new Collection($data);
        return view('proxies::admin.referrals.stat',compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function show(Referral $referral)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function edit(Referral $referral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Referral $referral)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referral $referral)
    {
        //
    }
}
