<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\Partner;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:partners-list|partners-create|partners-edit|partners-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:partners-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:partners-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:partners-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $partners = Partner::paginate(10);

        return view('proxies::admin.partners.index', compact('partners'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('proxies::admin.partners.create');
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
            'discount' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512',
        ]);

        $partner = new Partner();

        $input = $request->all();

        if (!empty($input['logo'])) {
            $filename = $request->file('logo')->hashName();
            Storage::putFileAs('/assets/img/partners/', $request->file('logo'), $filename);
            $partner->logo = '/assets/img/partners/' . $filename;
        }

        $partner->name = $input['name'];
        $partner->discount = $input['discount'];
        $partner->promo = $input['promo'];
        $partner->link = $input['link'];
        $partner->save();

        return redirect()->route('partners.index')
            ->with('success', 'Партнер успешно добавлено');
    }


    /**
     * Display the specified resource.

     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Partner $partner
     * @return Application|Factory|View
     */
    public function edit(Partner $partner): View|Factory|Application
    {
        return view('proxies::admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Partner $partner
     * @return RedirectResponse
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'discount' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512',
        ]);

        $input = $request->all();

        if (!empty($input['logo'])) {
            if (!empty($partner->logo)) {
                Storage::delete($partner->logo);
            }

            $filename = $request->file('logo')->hashName();
            Storage::putFileAs('/assets/img/partners/', $request->file('logo'), $filename);
            $partner->logo = '/assets/img/partners/' . $filename;
        }

        $partner->name = $input['name'];
        $partner->discount = $input['discount'];
        $partner->promo = $input['promo'];
        $partner->link = $input['link'];
        $partner->save();

        return redirect()->route('partners.index')
            ->with('success', 'Партнер успешно обновлен');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Partner $partner
     * @return RedirectResponse
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        Storage::delete($partner->logo);
        $partner->delete();

        return redirect()->route('partners.index')
            ->with('success', 'Успешно удалено');
    }
}
