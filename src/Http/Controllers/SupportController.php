<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\Support;
use ssda1\proxies\Models\SupportMassages;
use ssda1\proxies\Models\User;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:support-list|support-create|support-edit|support-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:support-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:support-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:support-delete', ['only' => ['destroy']]);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Отобразить список ресурсов.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $supports = Support::orderBy('status')->paginate(10);
        return view('proxies::admin.support.index', compact('supports'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Ajax Сохранение Общих настроек сайта.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function closeSupp(Request $request): RedirectResponse
    {

        $returnArray = [];
        $input = $request->all();

        $support = Support::find($input['id']);
        $support->status = true;

        try {
            $support->save();
        } catch (\Exception $exception) {
            $this->log(
                'Закрытие обращения',
                "Ошибка! Обращение $support->id не закрыто",
                'Inlet closure',
                "Error! $support->id is not closed"
            );
        }

        $this->log(
            'Закрытие обращения',
            "Успешно! Обращение $support->id закрыто",
            'Inlet closure',
            "Successful! $support->id is closed"
        );

        // $returnArray['status'] = true;
        // $returnArray['action'] = 'closeSupp';
        return redirect()->route('support.index')
            ->with('success', 'Обращение#' . $input['id'] . ' закрыто');
    }

    /**
     * Ajax Сохранение Общих настроек сайта.
     *
     * @param Request $request
     * @return Response|array
     */
    public function sendSupportMassAdmin(Request $request): Response|array
    {

        $link = mysqli_connect("app.comet-server.ru", "3820", "QmmMSy3gzDmKIKqIzL0ATEaYSMgGDk0T4JSCNvd0kUYywzBSA9UsaaTCrhad4R3d", "CometQL_v1");

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя

        $returnArray = [];

        $input = $request->all();
        $id = $input['id'];
        $text = $input['text'];
        $newMass = new SupportMassages;
        $newMass->support_id = $id;
        $newMass->massage = $text;
        $newMass->admin = true;
        $newMass->save();
        $returnArray['status'] = true;
        $returnArray['action'] = 'sending';

        /**
         * Отправка данных в канал с именем Pipe_name передаётся сообщение с именем event_name и содержимым указанным в поле message.
         */
        // $result = mysqli_query (  $link, 'INSERT INTO pipes_messages (name, event, message)VALUES("web_'.$id.'", "event_name", "'.$updateHTMLBid.'BYN")' );
        $result = mysqli_query($link, "INSERT INTO pipes_messages (name, event, message)VALUES('web_$id', 'event_name', \"{'text':'$text','date':'','admin': 'yes'}\")");


        return $returnArray;
    }


    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::supports.create');
    }

    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);


        try {
            Support::create($request->all());
        } catch (\Exception $exception) {
            $this->log(
                'Создание обращения',
                "Ошибка! Обращение не создано",
                'Making Treatment',
                "Error! Treatment not created"
            );
        }

        $this->log(
            'Создание обращения',
            "Успешно! Обращение создано",
            'Making Treatment',
            "Successful! Treatment created"
        );

        return redirect()->route('supports.index')
            ->with('success', 'Новость успешно создана.');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param Support $support
     * @return Application|Factory|View
     */
    public function show(Support $support): View|Factory|Application
    {
        return view('proxies::admin.support.show', compact('support'));
    }


    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Support $supports
     * @return Application|Factory|View
     */
    public function edit(Support $supports): View|Factory|Application
    {
        return view('proxies::supports.edit', compact('supports'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function userPageSupports(Request $request): View|Factory|Application
    {
        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя
        $supportsList = Support::where('user_id', '=', $userID)->get();

        return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.support.index', compact('user', 'supportsList'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Request $request
     * @param $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function userPageSupport(Request $request, $id): View|Factory|Application|RedirectResponse
    {

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;
        // Данные пользователя
        $supportsList = Support::where('user_id', '=', $userID)->get();

        if ($id == 'new') {
            $newSupport = new Support;
            $newSupport->user_id = $userID;
            try {
                $newSupport->save();
            } catch (\Exception $exception) {
                $this->log(
                    'Редактирование обращения',
                    "Ошибка! Обращение $newSupport->id не обновлено",
                    'Treatment editing',
                    "Error! Treatment not created"
                );
            }

            $this->log(
                'Редактирование обращения',
                "Успешно! Обращение $newSupport->id обновлено",
                'Treatment editing',
                "Successful! Treatment $newSupport->id updated"
            );

            return redirect()->route('supportSite', $newSupport->id);
        } else {
            try {
                $support = Support::find($id);
            } catch (\Exception $exception) {
                $this->log(
                    'Поиск обращения для редактирования',
                    "Ошибка! Обращение не найдено",
                    'Search for a treatment to edit',
                    "Error! Treatment not found"
                );
            }

            $this->log(
                'Поиск обращения для редактирования',
                "Успешно! Обращение $id найдено",
                'Search for a treatment to edit',
                "Successful! Treatment $id found"
            );

            return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.support.dialogue', compact('user', 'id', 'support', 'supportsList'));
        }
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Request $request
     * @return View|Factory|Application|RedirectResponse
     */
    public function userPageSupportsNew(Request $request): View|Factory|Application|RedirectResponse
    {

        // Данные пользователя
        $user = Auth::user();
        $balance = $user->balance;
        $userID = $user->id;

        $newSupport = new Support;
        $newSupport->user_id = $userID;

        try {
            $newSupport->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление обращения',
                "Ошибка! Обращение не добавлено",
                'Addressing',
                "Error! Treatment not added"
            );
        }

        $this->log(
            'Добавление обращения',
            "Успешно! Обращение $newSupport->id добавлено",
            'Addressing',
            "Successful! Treatment $newSupport->id added"
        );

        return redirect()->route('supportSite', $newSupport->id);
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param Support $supports
     * @return RedirectResponse
     */
    public function update(Request $request, Support $supports): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $id = $supports->id;
        try {
            $supports->update($request->all());
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование обращения',
                "Ошибка! Обращение $id не обновлено",
                'Treatment editing',
                "Error! $id Treatment not updated"
            );
        }

        $this->log(
            'Редактирование обращения',
            "Успешно! Обращение $id обновлено",
            'Treatment editing',
            "Successful! $id Treatment updated"
        );

        return redirect()->route('supports.index')
            ->with('success', 'Новость успешно обновлена');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param Support $support
     * @return RedirectResponse
     */
    public function destroy(Support $support): RedirectResponse
    {
        $supportsMassage = $support->AllSupportMassage;
        foreach ($supportsMassage as $key => $massage) {
            $massage->delete();
        }

        $id = $support->id;
        try {
            $support->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление обращения',
                "Ошибка! Обращение $id не удалено",
                'Treatment removal',
                "Error! Treatment $id not deleted"
            );
        }

        $this->log(
            'Удаление обращения',
            "Успешно! Обращение $id удалено",
            'Treatment removal',
            "Successful! Treatment $id removed"
        );

        return redirect()->route('support.index')
            ->with('success', 'Обращение успешно удалено');
    }
}
