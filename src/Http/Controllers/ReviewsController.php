<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\Reviews;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewsController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:reviews-list|reviews-create|reviews-edit|reviews-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:reviews-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:reviews-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:reviews-delete', ['only' => ['destroy']]);
    }

    /**
     * Отобразить список ресурсов в админке.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $reviews = Reviews::latest()->paginate(10);
        return view('proxies::admin.reviews-adm.index', compact('reviews'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Отобразить список ресурсов На сайте.
     *
     * @return Application|Factory|View
     */
    public function reviews(): Application|Factory|View
    {
        $reviewss = Reviews::latest()->paginate(10);
        return view('proxies::templates.'. (new TemplateController())->getUserTemplateDirectory() .'.pages.reviews.index', compact('reviewss'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::admin.reviews-adm.create');
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
            'description' => 'required',
        ]);
        $reviews_adm = new Reviews;
        $input = $request->all();
        if (!empty($input['avatar'])) {
            // $filename = $input['logo']->getClientOriginalName();
            $filename = $request->file('avatar')->hashName();
            // $hashedName = hash_file('md5', $filename);
            // $filenameLogo = $request->file('avatar')->$hashedName;
            // Storage::putFileAs('/assets/img/', $request->file('avatar'), $hashedName);
            //Сохраняем оригинальную картинку
            // $input['icon']->move(Storage::path('/public/assets/img/'),$filename);
            Storage::putFileAs('/assets/img/reviews/', $request->file('avatar'), $filename);
            $reviews_adm->avatar = '/assets/img/reviews/' . $filename;
            $reviews_adm->name = $input['name'];
            $reviews_adm->link = $input['link'];
            $reviews_adm->linkName = $input['linkName'];
            $reviews_adm->description = $input['description'];
            $reviews_adm->name_en = $input['name_en'];
            $reviews_adm->description_en = $input['description_en'];
        } else {
            $reviews_adm->name = $input['name'];
            $reviews_adm->link = $input['link'];
            $reviews_adm->linkName = $input['linkName'];
            $reviews_adm->description = $input['description'];
            $reviews_adm->name_en = $input['name_en'];
            $reviews_adm->description_en = $input['description_en'];
        }

        try {
            $reviews_adm->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление отзыва',
                "Ошибка! Отзыв не добавлен",
                'Addition of a review',
                "Error! No review added"
            );
        }

        $this->log(
            'Добавление отзыва',
            "Успешно! Отзыв $reviews_adm->id добавлен",
            'Addition of a review',
            "Successful! Review $reviews_adm->id added"
        );

        // Reviews::create($request->all());

        return redirect()->route('reviews-adm.index')
            ->with('success', 'Отзыв успешно добавлен.');
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Reviews $reviews_adm
     * @return Application|Factory|View
     */
    public function edit(Reviews $reviews_adm): View|Factory|Application
    {
        return view('proxies::admin.reviews-adm.edit', compact('reviews_adm'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param Request $request
     * @param Reviews $reviews_adm
     * @return RedirectResponse
     */
    public function update(Request $request, Reviews $reviews_adm): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        // $reviews_adm = new Reviews;

        $input = $request->all();
        if (!empty($input['avatar'])) {
            $filename = $request->file('avatar')->hashName();
            Storage::putFileAs('/assets/img/reviews/', $request->file('avatar'), $filename);
            $reviews_adm->avatar = '/assets/img/reviews/' . $filename;
            $reviews_adm->name = $input['name'];
            $reviews_adm->link = $input['link'];
            $reviews_adm->linkName = $input['linkName'];
            $reviews_adm->description = $input['description'];
            $reviews_adm->name_en = $input['name_en'];
            $reviews_adm->description_en = $input['description_en'];

        } else {
            $reviews_adm->name = $input['name'];
            $reviews_adm->link = $input['link'];
            $reviews_adm->linkName = $input['linkName'];
            $reviews_adm->description = $input['description'];
            $reviews_adm->name_en = $input['name_en'];
            $reviews_adm->description_en = $input['description_en'];
        }

        try {
            $reviews_adm->save();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование отзыва',
                "Ошибка! Отзыв $reviews_adm->id не обновлен",
                'Review editing',
                "Error! Review $reviews_adm->id not updated"
            );
        }

        $this->log(
            'Редактирование отзыва',
            "Успешно! Отзыв $reviews_adm->id обновлен",
            'Review editing',
            "Successful! Review $reviews_adm->id updated"
        );

        // $reviews_adm->update($request->all());

        return redirect()->route('reviews-adm.index')
            ->with('success', 'Отзыв успешно обновлен');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param Reviews $reviews_adm
     * @return RedirectResponse
     */
    public function destroy(Reviews $reviews_adm): RedirectResponse
    {
        $id = $reviews_adm->id;

        try {
            $reviews_adm->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление отзыва',
                "Ошибка! Отзыв $id не удален",
                'Review removal',
                "Error! Review $id not deleted"
            );
        }

        $this->log(
            'Удаление отзыва',
            "Успешно! Отзыв $id удален",
            'Review removal',
            "Successful! Review $id removed"
        );

        return redirect()->route('reviews-adm.index')
            ->with('success', 'Отзыв успешно удален');
    }
}
