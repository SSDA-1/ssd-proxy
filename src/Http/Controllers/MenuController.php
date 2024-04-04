<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\Menu;
use ssda1\proxies\Models\ProcessLog;
use ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class MenuController extends Controller
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
        $this->middleware('permission:menu-list|menu-create|menu-edit|menu-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:menu-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $menu = Menu::latest()->paginate(100);
        return view('proxies::admin.menu.index', compact('menu'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    // /**
    //  * Отобразить список ресурсов.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function menuSite()
    // {
    //     $menus = Menu::latest();
    //     return view('proxies::layouts.head',compact('menus'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::admin.menu.create');
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
            'name' => 'required',
            'link' => 'required',
            'type_menu' => 'required',
        ]);
        $input = $request->all();
        $menu = new Menu;

        $menu->name = $input['name'];
        $menu->name_en = $input['name_en'];
        $menu->link = $input['link'];
        $menu->type_menu = $input['type_menu'];
        if (!empty($input['header'])) {
            $menu->top_botton = 1;
        } elseif (!empty($input['footer'])) {
            $menu->top_botton = 2;
        }

        try {
            $menu->save();
        } catch (\Exception $exception) {
            $this->log(
                'Добавление пункта меню',
                "Ошибка! Пункт меню не добавлено",
                'Adding a menu item',
                "Error! No menu item added"
            );
        }

        $this->log(
            'Добавление пункта меню',
            "Пункт меню $menu->id добавлено",
            'Adding a menu item',
            "Menu item $menu->id added"
        );
        // Menu::create($request->all());

        return redirect()->route('menu.index')
            ->with('success', 'Пункт меню успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param Menu $menu
     * @return Application|Factory|View
     */
    public function show(Menu $menu): View|Factory|Application
    {
        return view('proxies::admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Menu $menu
     * @return Application|Factory|View
     */
    public function edit(Menu $menu): View|Factory|Application
    {
        return view('proxies::admin.menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'link' => 'required',
            'type_menu' => 'required',
        ]);

        $input = $request->all();

        $menu->name = $input['name'];
        $menu->name_en = $input['name_en'];
        $menu->link = $input['link'];
        $menu->type_menu = $input['type_menu'];
        if (!empty($input['header'])) {
            $menu->top_botton = 1;
        } elseif (!empty($input['footer'])) {
            $menu->top_botton = 2;
        }

        try {
            $menu->update();
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование пункта меню',
                "Ошибка! Пункт меню $menu->id не обновлено",
                'Menu item editing',
                "Error! Menu item $menu->id not updated"
            );
        }

        $this->log(
            'Редактирование пункта меню',
            "Успешно! Пункт меню $menu->id обновлено",
            'Menu item editing',
            "Successful! Menu item $menu->id updated"
        );
        // $menu->update($request->all());

        return redirect()->route('menu.index')
            ->with('success', 'Пункт меню успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $id = $menu->id;

        try {
            $menu->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление пункта меню',
                "Ошибка! Пункт меню $id не удалено",
                'Removing a menu item',
                "Error! The $id menu item is not deleted"
            );
        }

        $this->log(
            'Удаление пункта меню',
            "Успешно! Пункт меню $id удалено",
            'Removing a menu item',
            "Successful! The $id menu item is removed"
        );

        return redirect()->route('menu.index')
            ->with('success', 'Пункт меню успешно удален');
    }
}
