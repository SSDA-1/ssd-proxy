<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\TariffSettings;
use Ssda1\proxies\Service\ProcessLogService;
use Ssda1\proxies\Models\CountPairsProxyDiscount;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CountPairsProxyDiscountController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    function __construct()
    {
        $this->middleware('permission:countpairsproxydiscount-list|countpairsproxydiscount-create|countpairsproxydiscount-edit|countpairsproxydiscount-delete', ['only' => ['store, destroy']]);
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
            $discount = CountPairsProxyDiscount::create([
                'count_pairs' => (int)$data['count_pairs'],
                'discount_buy' => (float)$data['discount_buy'],
                'discount_extension' => (float)$data['discount_extension'],
            ]);

            $proxyPairsDiscount = isset($data['proxy_pairs_discount']);
            $tariff = TariffSettings::find(1);
            $tariff->proxy_pairs_discount = $proxyPairsDiscount;
            $tariff->save();

            $this->log(
                'Создание скидки на продление и покупку',
                "Успешно! Скидка $discount->id создана",
                'Creating a discount on renewal and purchase',
                "Success! Discount $discount->id created"
            );
        } catch (\Exception $exception) {
            $e = $exception->getMessage();
            $this->log(
                'Создание скидки на продление и покупку',
                "Ошибка! Скидка не создана $e",
                'Creating a discount on renewal and purchase',
                "Error! Discount not created $e"
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
        $discount = CountPairsProxyDiscount::find($id);

        if (is_null($discount)) {
            abort(404);
        }

        try {
            $discount->delete();

            $this->log(
                'Удаление скидки на продление и покупку',
                "Успешно! Скидка $id удалена",
                'Removal of Extension and Purchase Discount',
                "Successful! Discount $id removed"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Удаление скидки на продление и покупку',
                "Ошибка! Скидка $id не удалена",
                'Removal of Extension and Purchase Discount',
                "Error! Discount $id not deleted"
            );
        }

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'countPairsProxyDiscount_' . $id;
        $returnArray['massage'] = 'Скидка №' . $id . ' успешно удалена';
        return $returnArray;
    }
}
