<?php

declare(strict_types=1);

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\News;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:news-list|news-create|news-edit|news-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:news-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:news-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news-delete', ['only' => ['destroy']]);
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
        $newss = News::latest()->paginate(10);
        return view('proxies::admin.news.index', compact('newss'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Отобразить список ресурсов.
     *
     * @return Application|Factory|View
     */
    public function blog(): View|Factory|Application
    {
        $newss = News::latest()->paginate(10);
        return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.blog.index', compact('newss'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::admin.news.create');
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
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2056',
        ]);

        $news_adm = new News;

        $input = $request->all();

        if (!empty($input['images'])) {
            $filename = $request->file('images')->hashName();
            Storage::putFileAs('/assets/img/blog/', $request->file('images'), $filename);
            $news_adm->images = '/assets/img/blog/' . $filename;
        }
        $news_adm->name = $input['name'];
        $news_adm->category = $input['category'];
        $news_adm->detail = $input['detail'];
        $news_adm->author = $input['author'];
        $news_adm->name_en = $input['name_en'];
        $news_adm->detail_en = $input['detail_en'];

        try {
            $news_adm->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление новости',
                "Ошибка! Новость не добавлена",
                'Adding news',
                "Error! No news added"
            );
        }

        $this->log(
            'Добавление новости',
            "Успешно! Новость $news_adm->id добавлено",
            'Adding news',
            "Success! News $news_adm->id added"
    );
        // News::create($request->all());

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно создана.');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param News $news
     * @return Application|Factory|View
     */
    public function show(News $news): View|Factory|Application
    {
        return view('proxies::admin.news.show', compact('news'));
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function blogShow(int $id): View|Factory|Application
    {
        $blogNews = News::find($id);

        if (is_null($blogNews)) {
            abort(404);
        }

        return view('proxies::templates.' . (new TemplateController())->getUserTemplateDirectory() . '.pages.blog.show', compact('blogNews'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param News $news
     * @return Application|Factory|View
     */
    public function edit(News $news): View|Factory|Application
    {
        return view('proxies::admin.news.edit', compact('news'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param News $news
     * @return RedirectResponse
     */
    public function update(Request $request, News $news): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:256',
        ]);

        $input = $request->all();
        $name = $request->post('name');
        // $newsUpdatePost = News::find($news->id);

        if (!empty($input['images'])) {
            $filename = $request->file('images')->hashName();
            Storage::putFileAs('/assets/img/blog/', $request->file('images'), $filename);
            $news->images = '/assets/img/blog/' . $filename;
            $news->name = $input['name'];
            $news->category = $input['category'];
            $news->detail = $input['detail'];
            $news->author = $input['author'];
            $news->name_en = $input['name_en'];
            $news->detail_en = $input['detail_en'];
        } else {
            $news->name = $name;
            $news->category = $input['category'];
            $news->detail = $input['detail'];
            $news->name_en = $input['name_en'];
            $news->detail_en = $input['detail_en'];
            // $news->author = $input['author'];
        }

        try {
            $news->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование новости',
                "Ошибка! Новость $news->id не обновлена",
                'News editing',
                "Error! $news->id not updated"
            );
        }

        $this->log(
            'Редактирование новости',
            "Успешно! Новость $news->id обновлена",
            'News editing',
            "Success! $news->id update"
        );

        // $news->update($request->all());

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно обновлена' /*. json_encode($input)*/);
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param News $news
     * @return RedirectResponse
     */
    public function destroy(News $news): RedirectResponse
    {
        $id = $news->id;

        try {
            $news->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление новости',
                "Ошибка! Новость $id не удалена",
                'News deletion',
                "Error! $news->id not deleted"
            );
        }

        $this->log(
            'Удаление новости',
            "Успешно! Новость $id удалена",
            'News deletion',
            "Successful! News $id removed"
        );

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно удалена');
    }
}
