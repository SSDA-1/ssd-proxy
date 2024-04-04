<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\Promocode;
use ssd\proxies\Models\ProcessLog;
use ssd\proxies\Models\TariffSettings;
use ssd\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PromocodeController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    function __construct()
    {
        $this->middleware('permission:promocode-list|promocode-create|promocode-edit|promocode-delete', ['only' => ['store, destroy']]);
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
            'discount' => 'required',
            'name' => 'required'
        ]);

        $data = $request->input();

        try {
            $promocode = Promocode::create([
                'date_end' => Carbon::now()->addDays((int)$data['date_end']),
                'max_activated' => (int)$data['max_activated'],
                'discount' => (float)$data['discount'],
                'min_quantity' => (int)$data['min_quantity'],
                'min_rent' => (int)$data['min_rent'],
                'multi_activating' => isset($data['multi_activating']),
                'name' => $data['name']
            ]);

            $promocodeDiscount = isset($data['promocode_discount']);
            $tariff = TariffSettings::find(1);
            $tariff->promocode_discount = $promocodeDiscount;
            $tariff->save();

            $this->log(
                'Создание промокода',
                "Успешно! Промокод $promocode->id создан",
                'Promocode creation',
                "Successful! Promocode $promocode->id created"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Создание промокода',
                "Ошибка! Промокод не создан",
                'Promocode creation',
                "Error! Promocode not created"
            );
        }


        return redirect()->back()->with('success', 'Промокод создан');
    }

    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param int $id
     * @return array|RedirectResponse
     */
    public function destroy(int $id): array|RedirectResponse
    {
        $promocode = Promocode::find($id);

        if (is_null($promocode)) {
            abort(404);
        }

        try {
            $promocode->delete();

            $this->log(
                'Удаление промокода',
                "Успешно! Промокод $id удален",
                'Promocode removal',
                "Successful! Promocode $id removed"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Удаление промокода',
                "Ошибка! Промокод $id не удален",
                'Promocode removal',
                "Error! Promocode $id not deleted"
            );
        }

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'promocode_' . $id;
        $returnArray['massage'] = 'Промокод №' . $id . ' успешно удалён';
        return $returnArray;
    }
}
