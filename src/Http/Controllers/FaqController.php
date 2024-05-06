<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\Faq;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class FaqController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:faq-list|faq-create|faq-edit|faq-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:faq-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:faq-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faq-delete', ['only' => ['destroy']]);
    }
    /**
     * Отобразить список ресурсов в админке.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $faq = Faq::latest()->paginate(40);
        return view('proxies::admin.faq-adm.index', compact('faq'))
            ->with('i', (request()->input('page', 1) - 1) * 40);
    }

    /**
     * Отобразить список ресурсов На сайте.
     *
     * @return Application|Factory|View
     */
    public function faq(): View|Factory|Application
    {
        $faqs = Faq::latest()->paginate(40);
        return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.faq', compact('faqs'))
            ->with('i', (request()->input('page', 1) - 1) * 40);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): Application|Factory|View
    {
        return view('proxies::admin.faq-adm.create');
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
            'question' => 'required',
            'answer' => 'required',
        ]);

        $input = $request->all();
        $faq = new Faq;
        $faq->question = $input['question'];
        $faq->answer = $input['answer'];
        $faq->question_en = $input['question_en'];
        $faq->answer_en = $input['answer_en'];

        try {
            $faq->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление вопроса',
                "Ошибка! Вопрос не добавлен",
                'Adding a question',
                "Error! No question added"
            );
        }

        $this->log(
            'Добавление вопроса',
            "Успешно!",
            'Adding a question',
            "Successfully!"
        );

        return redirect()->route('faq-adm.index')
            ->with('success', 'Вопрос успешно добавлен');
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Faq $faq_adm
     * @return Application|Factory|View
     */
    public function edit(Faq $faq_adm): View|Factory|Application
    {
        return view('proxies::admin.faq-adm.edit', compact('faq_adm'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param Faq $faq_adm
     * @return RedirectResponse
     */
    public function update(Request $request, Faq $faq_adm): RedirectResponse
    {
        request()->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $input = $request->all();
        $faq_adm->question = $input['question'];
        $faq_adm->answer = $input['answer'];
        $faq_adm->question_en = $input['question_en'];
        $faq_adm->answer_en = $input['answer_en'];

        try {
            $faq_adm->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование вопроса',
                "Ошибка! Вопрос $faq_adm->id не обновлен",
                'Question editing',
                "Error! Question $faq_adm->id not updated"
            );
        }

        $this->log(
            'Редактирование вопроса',
            "Успешно! Вопрос $faq_adm->id обновлен",
            'Question editing',
            "Successful! Question $faq_adm->id updated"
        );

        return redirect()->route('faq-adm.index')
            ->with('success', 'Вопрос успешно обновлен');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param Faq $faq_adm
     * @return RedirectResponse
     */
    public function destroy(Faq $faq_adm): RedirectResponse
    {
        $id = $faq_adm->id;

        try {
            $faq_adm->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление вопроса',
                "Ошибка! Вопрос $id не удален",
                'Deletion',
                "Error! $id question not deleted"
            );
        }

        $this->log(
            'Удаление вопроса',
            "Успешно! Вопрос $id удален",
            'Deletion',
            "Successful! Question $id removed"
        );

        return redirect()->route('faq-adm.index')
            ->with('success', 'Вопрос успешно удален');
    }
}
