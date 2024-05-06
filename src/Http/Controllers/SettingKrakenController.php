<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\SettingKraken;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;

class SettingKrakenController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('permission:proxy-admin', ['only' => ['admin']]);
    // }


    /**
     * Ajax Сохранение Интеграции.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function integrationSave(Request $request)
    {
        $loginKraken = $request->input('login_kraken');
        $passwordKraken = $request->input('pass_kraken');
        $ipKraken = $request->input('ip_kraken');

        $settingModel = SettingKraken::find(1);
        $settingModel->integration_login = $loginKraken;
        if ($passwordKraken !== null) {
            $settingModel->integration_password = $passwordKraken;
        }

        $settingModel->integration_ip = $ipKraken;
        try {
            $settingModel->save();
        } catch (\Exception $exception) {
            $this->log(
                'Сохранение интеграции',
                "Ошибка! Интеграция не сохранена",
                'Staying integrated',
                "Error! Integration not saved"
            );
        }

        $this->log(
            'Сохранение интеграции',
            "Успешно! Интеграция $settingModel->id сохранена",
            'Staying integrated',
            "Successful! $settingModel->id integration saved"
        );

        $returnArray = [];
        $returnArray['status'] = true;
        $returnArray['input'] = $request->all();
        // return $returnArray;

        return redirect()->back()->with('success', 'Успешно обновлено');
    }

    /**
     * Ajax Сохранение Основного.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function basicSave(Request $request)
    {
        $allPrice = $request->input('all_price');
        $privatPrice = $request->input('privat_price');
        $mounth = $request->input('mounth');

        $settingModel = SettingKraken::find(1);
        $settingModel->proxy_all_price = $allPrice;
        $settingModel->proxy_privat_price = $privatPrice;
        $settingModel->proxy_mounth = $mounth;

        try {
            $settingModel->save();
        } catch (\Exception $exception) {
            $this->log(
                'Сохранение основного',
                "Ошибка! Основное не сохранено",
                'Retention of primary',
                "Error! Main not saved"
            );
        }

        $this->log(
            'Сохранение основного',
            "Успешно! Основное $settingModel->id сохранено",
            'Retention of primary',
            "Successful! Main $settingModel->id saved"
        );

        $returnArray = [];
        $returnArray['status'] = true;
        // return $returnArray;
        return redirect()->back()->with('success', 'Успешно обновлено');

    }

    /**
     * Ajax Сохранение Скидок.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saleSave(Request $request)
    {
        $twoSelCount = $request->input('two_sel_count');
        $threeSelCount = $request->input('three_sel_count');
        $twoSelPeriod = $request->input('two_sel_period');
        $threeSelPeriod = $request->input('three_sel_period');

        $settingModel = SettingKraken::find(1);
        $settingModel->proxy_two_sel_count = $twoSelCount;
        $settingModel->proxy_three_sel_count = $threeSelCount;
        $settingModel->proxy_two_sel_period = $twoSelPeriod;
        $settingModel->proxy_three_sel_period = $threeSelPeriod;

        try {
            $settingModel->save();

        } catch (\Exception $exception) {
            $this->log(
                'Сохранение скидок',
                "Ошибка! Скидки не сохранены",
                'Keeping discounts',
                "Error! Discounts not saved"
            );
        }

        $this->log(
            'Сохранение скидок',
            "Успешно! Скидки $settingModel->id сохранены",
            'Keeping discounts',
            "Success! Discounts $settingModel->id saved"
        );

        $returnArray = [];
        $returnArray['status'] = true;
        // return $returnArray;
        return redirect()->back()->with('success', 'Успешно обновлено');

    }

    // public function getCountKraken(): int
    // {
    //     return SettingKraken::all()->count();
    // }
}
