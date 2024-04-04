<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\Rules;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class RulesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = Rules::paginate(10);
        return view('proxies::admin.rules.index', compact('rules'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proxies::admin.rules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'text' => 'required',
        ]);

        $rules = new Rules;

        $input = $request->all();

        $rules->text = $input['text'];
        $rules->text_en = $input['text_en'];


        try {
            $rules->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление правила',
                "Ошибка! Правило $rules->id не добавлено",
                'Add Rule',
                "Error! No $rules->id rule added"
            );
        }

        $this->log(
            'Добавление правила',
            "Успешно! Правило $rules->id добавлено",
            'Add Rule',
            "Successful! Add a $rules->id rule"
        );

        return redirect()->route('rules.index')
            ->with('success', 'Правило успешно добавлено.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function show(Rules $rules)
    {
        $rules = Rules::find(1);
        return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.rules', compact('rules'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function edit(Rules $rule)
    {
        return view('proxies::admin.rules.edit', compact('rule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rules $rule)
    {
        request()->validate([
            'text' => 'required',
        ]);

        $input = $request->all();

        $rule->text = $input['text'];
        $rule->text_en = $input['text_en'];

        try {
            $rule->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование правила',
                "Ошибка! Правило $rule->id не обновлено",
                'Rule editing',
                "Error! The $rule->id has not been updated"
            );
        }

        $this->log(
            'Редактирование правила',
            "Успешно! Правило $rule->id обновлено",
            'Rule editing',
            "Successful! Updated $rule->id"
        );

        // $news->update($request->all());

        return redirect()->route('rules.index')
            ->with('success', 'Правила обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rules $rules)
    {
        //
    }
}
