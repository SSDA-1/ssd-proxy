<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\Advantag;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvantagsResurceController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:advantags-list|advantags-create|advantags-edit|advantags-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:advantags-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:advantags-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:advantags-delete', ['only' => ['destroy']]);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $advantags = Advantag::paginate(10);

        return view('proxies::admin.advantags.index', compact('advantags'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::admin.advantags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512',
        ]);

        $advantags_adm = new Advantag;

        $input = $request->all();

        if (!empty($input['image'])) {
            $filename = $request->file('image')->hashName();
            Storage::putFileAs('/assets/img/blog/', $request->file('image'), $filename);
            $advantags_adm->image = '/assets/img/blog/' . $filename;
        }
        $advantags_adm->title = $input['title'];
        $advantags_adm->description = $input['description'];

        try {
            $advantags_adm->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление перимущества',
                "Ошибка! Преимущество не добавлено",
                'Adding an advantage',
                "Error! No advantage added"
            );
        }

        $this->log(
            'Добавление перимущества',
            "Успешно! Преимущество $advantags_adm->id добавлено",
            'Adding an advantage',
            "Successfully! $advantags_adm->id advantage added"
        );

        // News::create($request->all());

        return redirect()->route('proxies::advantag.index')
            ->with('success', 'Преимущество успешно добавлено.');
    }


    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        return view('proxies::advantag.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Advantag $advantag
     * @return Application|Factory|View
     */
    public function edit(Advantag $advantag): View|Factory|Application
    {
        return view('proxies::admin.advantags.edit', compact('advantag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Advantag $advantag
     * @return RedirectResponse
     */
    public function update(Request $request, Advantag $advantag): RedirectResponse
    {

        request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $input = $request->all();
        if (!empty($input['image'])) {
            $filename = $request->file('image')->hashName();
            Storage::putFileAs('/assets/img/blog/', $request->file('image'), $filename);
            $advantag->image = '/assets/img/blog/' . $filename;
        }

        $advantag->title = $input['title'];
        $advantag->description = $input['description'];
        $advantag->title_en = $input['title_en'];
        $advantag->description_en = $input['description_en'];

        try {
            $advantag->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование перимущества',
                "Ошибка! Преимущество $advantag->id не обновлено",
                'Editing an advantage',
                "Error! Advantage $advantag->id not updated"
            );
        }

        $this->log(
            'Editing перимущества',
            "Успешно! Преимущество $advantag->id обновлено",
            'Adding an advantage',
            "Successfully! Advantage $advantag->id updated"
        );

        return redirect()->route('proxies::advantag.index')
            ->with('success', 'Преимущество успешно обновлено');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Advantag $advantag
     * @return RedirectResponse
     */
    public function destroy(Advantag $advantag): RedirectResponse
    {
        $id = $advantag->id;

        try {
            $advantag->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление перимущества',
                "Ошибка! Преимущество $id не удалено",
                'Removal an advantage',
                "Error! $id advantage not removed"
            );
        }

        $this->log(
            'Удаление перимущества',
            "Успешно! Преимущество $id удалено",
            'Removal an advantage',
            "Successfully! $id advantage removed"
        );

        return redirect()->route('proxies::advantag.index')
            ->with('success', 'Успешно удалено');
    }
}
