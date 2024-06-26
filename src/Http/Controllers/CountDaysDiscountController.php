<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\TariffSettings;
use Ssda1\proxies\Models\CountDaysDiscount;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CountDaysDiscountController extends Controller
{
    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }
    function __construct()
    {
        $this->middleware('permission:countdaysdiscount-list|countdaysdiscount-create|countdaysdiscount-edit|countdaysdiscount-delete', ['only' => ['store, destroy']]);
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
            $discount = CountDaysDiscount::create([
                'days' => (int)$data['days'],
                'discount' => (float)$data['discount'],
                'type' => $data['type-proxy'],
                'country' => $data['country'],
            ]);

            $daysDiscount = isset($data['days_discount']);
            $tariff = TariffSettings::find(1);
            $tariff->days_discount = $daysDiscount;
            $tariff->save();

            $this->log(
                'Создание скидки за количество дней',
                "Успешно! Скидка $discount->id создана",
                'Creating a discount for the number of days',
                "Success! Discount $discount->id created"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Создание скидки за количество дней',
                "Ошибка! Скидка не создана",
                'Creating a discount for the number of days',
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
        $discount = CountDaysDiscount::find($id);

        if (is_null($discount)) {
            abort(404);
        }

        try {
            $discount->delete();

            $this->log(
                'Удаление скидки за количество дней',
                "Успешно! Скидка $id удалена",
                'Remove the discount for the number of days',
                "Successful! Discount $id removed"
            );
        } catch (\Exception $exception) {
            $this->log(
                'Удаление скидки за количество дней',
                "Ошибка! Скидка $id не удалена",
                'Remove the discount for the number of days',
                "Error! Discount $id not deleted"
            );
        }

        $returnArray['status'] = true;
        $returnArray['action'] = 'delTable';
        $returnArray['tr'] = 'countDaysDiscount_' . $id;
        $returnArray['massage'] = 'Скидка №' . $id . ' успешно удалена';
        return $returnArray;
    }
}
