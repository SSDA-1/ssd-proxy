<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\Template;
use ssd\proxies\Models\Referral;
use ssd\proxies\Models\User;
use ssd\proxies\Models\ProcessLog;
use ssd\proxies\Models\SettingKraken;
use ssd\proxies\Models\Server;
use ssd\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Отобразить список ресурсов
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $data = User::orderBy('id', 'DESC')->paginate(50);
        return view('proxies::admin.users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 50);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orWhere('telegram_name', 'like', "%$query%")
            ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('proxies::admin.users.create', compact('roles'));
    }

    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            // 'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        if ($request->input('roles')) {
            $user->assignRole($request->input('roles'));
        }

        $this->log(
            'Создание пользователя',
            "Пользователь $user->id c почтой $user->email успешно создан",
            'User creation',
            "Пользователь $user->id with $user->email successfully created"
        );

        // $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', 'Пользователь успешно создан');
    }

    public function mode(Request $request): JsonResponse
    {
        $id = $request->id;
        $user = User::find($id);

        $user->mode = $request->mode;

        $user->save();

        return response()->json(['success' => 'Form is successfully submitted!']);
    }

    public function sidebarMode(Request $request): JsonResponse
    {
        $id = $request->id;
        $user = User::find($id);

        $user->sidebarMode = $request->sidebarMode;

        $user->save();

        return response()->json(['success' => 'Form is successfully submitted!']);
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id): View|Factory|Application
    {
        $user = User::find($id);
        if (is_null($user)) {
            abort(404);
        }

        return view('proxies::admin.users.show', compact('user'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id): View|Factory|Application
    {
        $user = User::find($id);

        if (is_null($user)) {
            abort(404);
        }

        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $userHistoryOperation = $user->historyOperation;
        $settingModel = SettingKraken::find(1);

        return view('proxies::admin.users.edit', compact('user', 'roles', 'userRole', 'userHistoryOperation', 'settingModel'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            // 'kraken_password' => 'required',
            // 'kraken_username' => 'required',
            // 'id_kraken' => 'required',
            // 'email' => 'required|email|unique:users,email,' . $id,
            // 'password' => 'same:confirm-password',
            // 'roles' => 'required'
        ]);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);

        if (is_null($user)) {
            abort(404);
        }

        $user->update($input);
        try {

            DB::table('model_has_roles')->where('model_id', $id)->delete();

            if ($request->input('roles')) {
                DB::table('roles')->where('name', $request->input('roles'));
                $user->assignRole($request->input('roles'));
            }

            $this->log(
                'Редактирование пользователя',
                "Пользователь $user->id c почтой $user->email успешно обновлен",
                'User editing',
                "User $user->id with $user->email successfully updated"
            );

            return redirect()->route('users.index')
                ->with('success', 'Пользователь успешно обновлен');
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование пользователя',
                "Ошибка при редактировании пользователя",
                'User editing',
                "Error editing the user"
            );
            return redirect()->back()
                ->withInput()
                ->withErrors('The roles field is required'); //Чтобы не возникло желание подбирать роли. Поэтому однообразная ошибка с валидатором
        }
    }

    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param  \App\User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $id = $user->id;
        $email = $user->email;
        $user->historyOperation()->delete();
        $user->templates()->delete();
        $supportsUser = $user->support;
        foreach ($supportsUser as $key => $support) {
            $supportsMassage = $support->AllSupportMassage;
            foreach ($supportsMassage as $key => $massage) {
                $massage->delete();
            }
            $support->delete();
        }
        $user = $user->delete();
        if (is_null($user)) {
            $this->log(
                'Удаление пользователя',
                "Ошибка при удалении пользователя",
                'User deletion',
                "User Delete Error"
            );
            abort(404);
        }

        $this->log(
            'Удаление пользователя',
            "Пользователь $id c почтой $email успешно удален",
            'User deletion',
            "User $id with $email successfully uninstalled"
        );

        return redirect()->route('users.index')
            ->with('success', 'Пользователь успешно удален');
    }



    /**
     * Авторизация телеграмм.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function processTelegrammAuth(Request $request, $code)
    {
        $id = $request->query('id');
        // $first_name = $request->query('first_name');
        $first_name = str_replace(' ', '', $request->query('first_name'));
        $first_name = str_replace("\n", "", $first_name);
        $username = $request->query('username');
        $photo_url = $request->query('photo_url');
        $auth_date = $request->query('auth_date');
        $hash = $request->query('hash');
        $ref = $code != 'nocode' ? $code : null;


        $userExists = User::where('telegram_auth_id', $id)->first();

        if ($userExists) {
            Auth::login($userExists);
        } else {
            // Дальнейшая обработка полученных переменных...
            $newUser = User::create([
                'name' => $first_name,
                'email' => '@' . $username,
                'password' => Hash::make($username),
                'telegram_auth_id' => $id,
                'telegram_name' => '@' . $username,
                'created_at' => $auth_date
            ]);

            $referrerCode = Str::random(8);
            $referralCode = null;

            if (isset($ref)) {
                $referrer = User::where('referral_code', $ref)->firstOrFail();
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
            // $servers = Server::all();
            // foreach ($servers as $key => $value) {
            //     // Данные сервера
            //     $ipSetting = $value->data['url'];
            //     $loginSetting = $value->data['login'];
            //     $passwordSetting = $value->data['password'];
            //     // Получение аппи ключа
            //     $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);
            //     $userID = $newUser->id;
            //     $getUserAddApi = getUserAddApi($userID, $ipSetting, $apiKey, $first_name, Hash::make($username), '@'.$username);

            //     // Дополнение: Проверка значения переменной $getUserAddApi

            // }

            // return response()->json([
            //     'id' => $id,
            //     'first_name' => $first_name,
            //     'username' => $username,
            //     'photo_url' => $photo_url,
            //     'auth_date' => $auth_date,
            //     'hash' => $hash
            // ]);

            Auth::login($newUser);

            $this->log(
                'Авторизация telegram',
                "Пользователь $newUser->id c почтой $newUser->email успешно создан",
                'Telegram authorization',
                "User $newUser->id with $newUser->email successfully created"
            );

        }

        return redirect()->route('lk')
            ->with('success', 'Пользователь успешно Создан');
    }


    /**
     * Сохранение при покупке.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function saveEmail(Request $request)
    {
        $user = Auth::user();
        if (!empty($request->input('email'))) {
            $email = $request->input('email');
            // Проверяем существование пользователя с таким email
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                $this->log(
                    'Сохранение при покупке',
                    "Пользователь с таким email уже существует",
                    'Save on Purchase',
                    "User with such email already exists"
                );
                return response()->json(['error' => 'Пользователь с таким email уже существует'], 400);
            }

            // Если пользователя с таким email не существует, сохраняем его в Auth::user
            $user->email = $email;
        }
        if (!empty($request->input('telegram_chat_id'))) {
            $telegram_chat_id = $request->input('telegram_chat_id');
            $this->validate($request, [
                'telegram_chat_id' => 'required|numeric',
            ]);

            // Если пользователя с таким email не существует, сохраняем его в Auth::user
            $user->telegram_chat_id = $telegram_chat_id;
        }

        $user->save();
        $password = Str::random(8, 'alnum');

        $userID = $user->id;
        // Создаём в Кракене
        $servers = Server::all();
        foreach ($servers as $key => $value) {
            // Данные сервера
            $ipSetting = $value->data['url'];
            $loginSetting = $value->data['login'];
            $passwordSetting = $value->data['password'];
            // Получение аппи ключа
            $apiKey = getToken($ipSetting, $loginSetting, $passwordSetting);

            $getUserAddApi = getUserAddApi($userID, $ipSetting, $apiKey, $user->name, $password, $email);

            // Дополнение: Проверка значения переменной $getUserAddApi

        }

        $this->log(
            'Сохранение при покупке',
            "Email успешно сохранен",
            'Save on Purchase',
            "Email was successfully saved"
        );

        // return response()->json(['message' => 'Email успешно сохранен'], 200);
        return $getUserAddApi;
    }
}
