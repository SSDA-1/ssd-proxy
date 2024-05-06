<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\WithdrawalRequest;

use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WithdrawalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdrawalRequest = WithdrawalRequest::all();
        return view('proxies::admin.referrals.index', compact('withdrawalRequest'));
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
     * @param  \App\Models\WithdrawalRequest  $withdrawalrequest
     * @return Application|Factory|View
     */
    public function show(WithdrawalRequest $withdrawalrequest): View|Factory|Application
    {
        $withdrawalrequest->status = $withdrawalrequest->status == 2 ? $withdrawalrequest->status : 1;
        $withdrawalrequest->save();
        return view('proxies::admin.referrals.show', compact('withdrawalrequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WithdrawalRequest  $withdrawalRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(WithdrawalRequest $withdrawalrequest)
    {
        $withdrawalrequest->status = 2;
        $withdrawalrequest->save();

        return redirect()->route('withdrawalrequest.index')
            ->with('success', 'Заявка на вывод успешно обновлена' /*. json_encode($input)*/);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WithdrawalRequest  $withdrawalrequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WithdrawalRequest $withdrawalrequest)
    {
        $withdrawalrequest->status = 2;
        $withdrawalrequest->save();

        return redirect()->route('withdrawalrequest.index')
            ->with('success', 'Заявка на вывод успешно обновлена' /*. json_encode($input)*/);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WithdrawalRequest  $withdrawalRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(WithdrawalRequest $withdrawalRequest)
    {
        //
    }
}
