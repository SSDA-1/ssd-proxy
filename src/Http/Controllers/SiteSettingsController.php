<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\siteSetting;
use Ssda1\proxies\Service\ProcessLogService;
use Ssda1\proxies\Models\SettingKraken;
use Ssda1\proxies\Models\SettingNotices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{

    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:proxy-admin', ['only' => ['admin']]);
    }

    private function log($name, $description)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingModel = SettingKraken::find(1);
        $siteSettingModel = siteSetting::find(1);
        $settingNotice = SettingNotices::find(1);
        // $settingNotice = '';
        return view('proxies::admin.allsettings', compact('settingModel', 'siteSettingModel', 'settingNotice'));
    }

    /**
     * Ajax Сохранение Общих настроек сайта.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allSettingsSiteSave(Request $request)
    {


        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $returnArray = [];

        $input = $request->all();
        $siteSettingModel = siteSetting::find(1);

        if (!empty($input['name_site'])) {
            $siteSettingModel->name = $input['name_site'];
        }
        if (!empty($input['icon'])) {
            $filename = $input['icon']->getClientOriginalName();
            Storage::putFileAs('/assets/img/', $request->file('icon'), $filename);
            $siteSettingModel->icon = '/assets/img/' . $filename;
        }

        if (!empty($input['logo'])) {
            $filename =  $request->file('logo')->hashName();
            Storage::putFileAs('/assets/img/logo/', $request->file('logo'), $filename);
            $siteSettingModel->logo = '/assets/img/logo/' . $filename;
        }
        $siteSettingModel->telegram = $input['telegram'];
        $siteSettingModel->address = $input['address'];
        //$siteSettingModel->phone = $input['phone'];
        $siteSettingModel->email = $input['email'];
        $siteSettingModel->skype = $input['skype'];
        $siteSettingModel->cooperation_tel = $input['cooperation_tel'];
        $siteSettingModel->cooperation_tg = $input['cooperation_tg'];
        $siteSettingModel->cooperation_email = $input['cooperation_email'];

        try {
            $siteSettingModel->save();
        } catch (\Exception $exception) {
            $this->log('Сохранение общих настроек сайта',"Ошибка! Общие настройки сайта не сохранены");
        }

        $this->log('Сохранение общих настроек сайта',"Успешно! Общие настройки сайта $siteSettingModel->id сохранены");

        $returnArray['status'] = true;

        return redirect()->route('allSettingSite')
            ->with('success', 'Основные настройки изменены.');

    }

    /**
     * Ajax Сохранение Настроек Реферальной системы.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function referallSettingsSiteSave(Request $request)
    {


        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $returnArray = [];

        $input = $request->all();
        $siteSettingModel = siteSetting::find(1);

        if (!empty($input['deposit_percentage'])) {
            $siteSettingModel->deposit_percentage = $input['deposit_percentage'];
        }
        if (!empty($input['minimum_withdrawal_amount'])) {
            $siteSettingModel->minimum_withdrawal_amount = $input['minimum_withdrawal_amount'];
        }

        $siteSettingModel->card_output = !empty($input['card_output']) ? true : false;
        $siteSettingModel->ecash_output = !empty($input['ecash_output']) ? true : false;
        $siteSettingModel->usdt_trc_20_output = !empty($input['usdt_trc_20_output']) ? true : false;
        $siteSettingModel->capitalist_output = !empty($input['capitalist_output']) ? true : false;
        $siteSettingModel->referral_balance_enabled = !empty($input['referral_balance_enabled']) ? true : false;

        try {
            $siteSettingModel->save();
        } catch (\Exception $exception) {
            $this->log('Сохранение общих настроек рефералов сайта',"Ошибка! Общие настройки рефералов сайта не сохранены");
        }

        $this->log('Сохранение общих настроек рефералов сайта',"Успешно! Общие настройки рефералов сайта $siteSettingModel->id сохранены");

        $returnArray['status'] = true;

        return redirect()->route('allSettingSite')
            ->with('success', 'Настройки реферальной системы изменены.');

    }

    /**
     * Ajax Сохранение CEO настроек сайта.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ceoSettingsSiteSave(Request $request)
    {

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $returnArray = [];

        $input = $request->all();

        $siteSettingModel = siteSetting::find(1);

        try {
            $siteSettingModel->update($input);
        } catch (\Exception $exception) {
            $this->log('Сохранение общих настроек СЕО сайта',"Ошибка! Общие настройки СЕО сайта не сохранены");
        }

        $this->log('Сохранение общих настроек СЕО сайта',"Успешно! Общие настройки СЕО сайта $siteSettingModel->id сохранены");

        $returnArray['status'] = true;

        return redirect()->route('allSettingSite')
            ->with('success', 'Настройки СЕО успешно изменены.');

    }

    /**
     * Ajax Сохранение CEO настроек сайта.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delimageSetting(Request $request)
    {

        $returnArray = [];

        $input = $request->all();
        $siteSettingModel = siteSetting::find(1);

        if ($input['type'] == 'icon') {
            Storage::delete($siteSettingModel->icon);
            $siteSettingModel->icon = '';
        } else {
            Storage::delete($siteSettingModel->logo);
            $siteSettingModel->logo = '';
        }

        try {
            $siteSettingModel->save();
        } catch (\Exception $exception) {
            $this->log('Сохранение общих настроек лого сайта',"Ошибка! Общие настройки лого сайта не сохранены");
        }

        $this->log('Сохранение общих настроек лого сайта',"Успешно! Общие настройки лого сайта $siteSettingModel->id сохранены");

        $returnArray['status'] = true;
        $returnArray['filesettingssite'] = $input['type'];

        return redirect()->route('allSettingSite')
            ->with('success', 'Настройки успешно изменены.');
    }

    /**
     * * Ajax Сохранение настроек Уведомлений.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function noticeSettings(Request $request)
    {
        $returnArray = [];
        $settingNotices = SettingNotices::find(1);
        $input = $request->all();
        $telegramCheck = !empty($input['telegram_check']) ? true : false;
        $emailCheck = !empty($input['email_check']) ? true : false;
        $thirdEmail = !empty($input['third_email']) ? true : false;

        $settingNotices->telegram_token = $input['telegram_token'];
        $settingNotices->telegram_link = $input['telegram_link'];
        $settingNotices->third_email_host = $input['third_email_host'];
        $settingNotices->third_email_username = $input['third_email_username'];
        $settingNotices->third_email_password = $input['third_email_password'];
        $settingNotices->third_email_encryption = $input['third_email_encryption'];
        $settingNotices->third_email_address = $input['third_email_address'];
        $settingNotices->telegram_check = $telegramCheck;
        $settingNotices->email_check = $emailCheck;
        $settingNotices->third_email = $thirdEmail;

        try {
            $settingNotices->update();
        } catch (\Exception $exception) {
            $this->log('Сохранение общих настроек уведомлений сайта',"Ошибка! Общие настройки уведомлений сайта не сохранены");
        }

        $this->log('Сохранение общих настроек уведомлений сайта',"Успешно! Общие настройки уведомлений сайта $settingNotices->id сохранены");

        $returnArray['status'] = true;

        return redirect()->route('allSettingSite')
            ->with('success', 'Настройки успешно изменены.');
    }

}
