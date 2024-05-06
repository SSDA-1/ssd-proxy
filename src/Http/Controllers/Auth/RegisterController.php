<?php

namespace Ssda1\proxies\Http\Controllers\Auth;

use Ssda1\proxies\Http\Controllers\Controller;
use Ssda1\proxies\Models\Template;
use Ssda1\proxies\Providers\RouteServiceProvider;
use Ssda1\proxies\Models\SettingKraken;
use Ssda1\proxies\Models\User;
use Ssda1\proxies\Models\Referral;
use Ssda1\proxies\Models\Server;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ],
        ]);
    }

    /**
     * Flatten an array.
     *
     * @param array $array
     * @return array
     */
    function flattenArray($array)
    {
        $result = [];

        foreach ($array as $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value));
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        try {
            $referrerCode = Str::random(8);
            $referralCode = null;

            $settingModel = SettingKraken::find(1);
            // OLD
            // $ipSetting = $settingModel->integration_ip;
            // $loginSetting = $settingModel->integration_login;
            // $passwordSetting = $settingModel->integration_password;

            $newUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $servers = Server::all();
            foreach ($servers as $key => $value) {
                // Данные сервера
                $ipSetting = $value->data['url'];
                $loginSetting = $value->data['login'];
                $passwordSetting = $value->data['password'];
                // Получение аппи ключа
                $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);
                $userID = $newUser->id;
                $getUserAddApi = getUserAddApi($userID, $ipSetting, $apiKey, $data['name'], $data['password'], $data['email']);

                // Дополнение: Проверка значения переменной $getUserAddApi

            }
            if ($getUserAddApi !== true) {
                $newUser->delete(); // Удаляем созданного пользователя
                throw new \Exception(serialize($getUserAddApi));
                // return $getUserAddApi;
            }
        } catch (\Exception $e) {
            // Верните сообщение об ошибке
            $flattenedArray = $this->flattenArray($getUserAddApi);
            $errors = implode(', ', $flattenedArray);

            throw new \Exception($errors . ': ' . $e->getMessage());
        }




        if (isset($data['ref'])) {
            $referrer = User::where('referral_code', $data['ref'])->firstOrFail();
            $referrerCode = $referrer->referral_code;

            $referral = new Referral();
            $referral->user_id = $newUser->id;
            $referral->referred_by = $referrer->id;
            $referral->referrer_code = $referrer->referral_code;

            // Update referred user's referral code to avoid conflicts
            $referralCode = Str::random(8);
            $newUser->referral_code = $referralCode;
            $newUser->save();

            $referral->referral_code = $referralCode;

            $referral->save();

            // Update referrer's stats
            $referrerReferrals = Referral::where('referred_by', $referrer->id)->count();
            $referrer->referrals_count = $referrerReferrals;
            $referrer->save();
        }

        // try {

        // }catch (\Exception){

        // }

        return $newUser;
    }
}
