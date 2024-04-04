<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Service\ProcessLogService;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Отобразить список ресурсов.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('proxies::admin.roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $permission = Permission::get();
        return view('proxies::admin.roles.create', compact('permission'));
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
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        try {
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
        } catch (\Exception $exception) {
            $this->log(
                'Добавление роли',
                "Ошибка! Роль не создана",
                'Role addition',
                "Error! Role not created"
            );
        }

        $this->log(
            'Добавление роли',
            "Успешно! Роль создана",
            'Role addition',
            "Successful! Role created"
        );

        return redirect()->route('roles.index')
            ->with('success', 'Роль успешно создана');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id): View|Factory|Application
    {
        $role = Role::find($id);

        if (is_null($role)) {
            abort(404);
        }

        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('proxies::admin.roles.show', compact('role', 'rolePermissions'));
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
        $role = Role::find($id);

        if (is_null($role)) {
            abort(404);
        }

        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('proxies::admin.roles.edit', compact('role', 'permission', 'rolePermissions'));
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
            'permission' => 'required',
        ]);

        $role = Role::find($id);

        if (is_null($role)) {
            abort(404);
        }

        $role->name = $request->input('name');

        try {
            $role->save();

            $role->syncPermissions($request->input('permission'));
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование роли',
                "Ошибка! Роль $id не обновлена",
                'Role editing',
                "Error! $id role not updated"
            );
        }

        $this->log(
            'Редактирование роли',
            "Успешно! Роль $id обновлена",
            'Role editing',
            "Successful! The $id role has been updated"
        );

        return redirect()->route('roles.index')
            ->with('success', 'Роль успешно обновлена');
    }

    /**
     * Убрать указанный ресурс из хранилища.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            DB::table("roles")->where('id', $id)->delete();
        } catch (\Exception $exception) {
            $this->log(
                'Удаление роли',
                "Ошибка! Роль $id не удалена",
                'Role removal',
                "Error! $id role not deleted"
            );
        }

        $this->log(
            'Удаление роли',
            "Успешно! Роль $id удалена",
            'Role removal',
            "Successful! Role $id removed"
        );

        return redirect()->route('roles.index')
            ->with('success', 'Роль успешно удалена');
    }
}
