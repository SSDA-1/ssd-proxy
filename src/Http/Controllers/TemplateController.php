<?php

declare(strict_types=1);

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\Template;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function userTemplates()
    {
        return auth()->user()->templates;
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    public function index(): Application|Factory|View
    {
        $allTemplates = Template::select()->orderBy('type', 'desc')->take(50)->get();
        $user_templates = $this->userTemplates();

        $sortedTemplates = $user_templates->merge($allTemplates);

        return view('proxies::admin.template-management.index', compact('user_templates', 'sortedTemplates'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function createTemplate(): View|Factory|Application
    {
        return view('proxies::admin.template-management.create');
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'directory' => 'required',
            'type' => 'required',
            'cost' => 'required'
        ]);

        try {
            Template::create($request->all());
        } catch (\Exception $exception) {
            $this->log(
                'Создание шаблона',
                "Ошибка! Шаблон не создан",
                'Template Creation',
                "Error! Template not created"
            );
        }

        $this->log(
            'Создание шаблона',
            "Успешно! Шаблон создан",
            'Template Creation',
            "Successful! Template created"
        );

        return redirect()->route('template-management')
            ->with('success', 'Шаблон успешно создан.');
    }

    public function buyTemplate(int $templateId): Application|Factory|View
    {
        $user = auth()->user();

        $template = Template::find($templateId);
        $user->templates()->attach($template);//пока не привязана покупка, идет автоматическая привязка шаблона к пользователю

        return view('proxies::admin.template-management.buy')->with('success', 'Шаблон куплен');
    }

    public function changeTemplate(int $templateId): RedirectResponse
    {
        $user = auth()->user()->id;

        try {
            DB::table('template_user')
                ->where('user_id', '=', $user)
                ->where('is_active', '=', '1')
                ->update(['is_active' => 0]);
            DB::table('template_user')
                ->where('template_id', '=', $templateId)
                ->where('user_id', '=', $user)
                ->update(['is_active' => 1]);
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование шаблона',
                "Ошибка! Шаблон $templateId не обновлен",
                'Template editing',
                "Error! $templateId template not updated"
            );
        }

        $this->log(
            'Редактирование шаблона',
            "Успешно! Шаблон $templateId обновлен",
            'Template editing',
            "Successful! $templateId template updated"
        );

        return redirect()->route('template-management')
            ->with('success', 'Шаблон изменен');
    }

    public function showTemplate(int $templateId): View|Factory|Application
    {
        $template = Template::find($templateId);

        if (is_null($template)) {
            abort(404);
        }

        return view('proxies::admin.template-management.show', compact('template'));
    }

    /**
     * Ищет активный шаблон. Если таковых нет, то выбирает базовый шаблон
     * @return string
     */
    public function getUserTemplateDirectory(): string
    {
        if (Schema::hasTable('templates')) {
            $template = Template::where('is_active', 1)->first();
            if ($template) {
                return $template->directory;
            }
        }
        return 'basic.basic-1';
    }
}
