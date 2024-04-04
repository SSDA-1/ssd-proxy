<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\ProcessLog;
use ssd\proxies\Models\TariffSettings;
use ssd\proxies\Models\CountProxyDiscount;
use ssd\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CountProxyDiscountController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    function __construct()
    {
        $this->middleware('permission:countproxydiscount-list|countproxydiscount-create|countproxydiscount-edit|countproxydiscount-delete', ['only' => ['store, destroy']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->input();

        try {
            $discount = CountProxyDiscount::create([
                'proxy' => (int)$data['proxy'],
                'discount' => (float)$data['discount'],
                'type' => $data['type-proxy'],
                'country' => $data['country'],
            ]);

            $proxyDiscount = isset($data['proxy_discount']);
            $tariff = TariffSettings::find(1);
            $tariff->proxy_discount = $proxyDiscount;
            $tariff->save();

            $this->log(
                'Создание скидки за количество прокси',
                "Успешно! Скидка $discount->id создана",
                'Creating a discount for the number of proxies',
                "Success! Discount $discount->id created"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Создание скидки за количество прокси',
                "Ошибка! Скидка не создана",
                'Creating a discount for the number of proxies',
                "Error! Discount not created"
            );
        }

        return redirect()->back()->with('success', 'Скидка создана');
    }

    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param int $id
     * @return array|RedirectResponse
     */
    public function destroy(int $id): array|RedirectResponse
    {
        $discount = CountProxyDiscount::find($id);

        if (is_null($discount)) {
            abort(404);
        }

        try {
            $discount->delete();

            $this->log(
                'Удаление скидки за количество прокси',
                "Успешно! Скидка $id удалена",
                'Removal of proxy number discount',
                "Successful! Discount $id removed"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Удаление скидки за количество прокси',
                "Ошибка! Скидка $id не удалена",
                'Removal of proxy number discount',
                "Error! Discount $id not deleted"
            );
        }

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'countProxyDiscount_' . $id;
        $returnArray['massage'] = 'Скидка №' . $id . ' успешно удалена';
        return $returnArray;
    }
}
