<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\SettingKraken;

use Illuminate\Contracts\Support\Renderable;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $settingModel = SettingKraken::find(1);
        return view('proxies::home', [
            'settingModel' => $settingModel,
        ]);
    }
}
