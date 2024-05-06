<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\TariffSettings;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TariffSettingsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tariffsettings-list|tariffsettings-create|tariffsettings-edit|tariffsettings-delete', ['only' => ['store']]);
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
        $tariffType = $data['globalTariff'];
        $proxyType = $data['globalType'];
        $defaultCountry = $data['country-default'];

        $result = [];
        $tariff = TariffSettings::find(1);
        $tariff->type_tariff = $tariffType;
        $tariff->type_proxy = $proxyType;
        $tariff->default_country = $defaultCountry;

        if (!$tariffType) {
            $max_days = $data['max_days'];
            $tariff->max_days = $max_days;

            $generalPrices = $data['general_price'];
            $privatePrices = $data['private_price'];
            $countries = $data['country'];

            foreach ($countries as $key => $country) {
                $result[$key] = [
                    'general_price' => $generalPrices[$key],
                    'private_price' => $privatePrices[$key],
                    'country' => $country,
                ];
            }

            $tariff->days_tariff = $result;
        } else {
            for ($i = 1; $i <= $data['id']; $i++) {
                $result[$i] = [
                    'name' => $data['name_' . $i],
                    'properties' => $data['properties_' . $i],
                    'period' => $data['period_' . $i],
                    'country' => $data['country_' . $i],
                    'general_price' => $data['cost-general_' . $i],
                    'private_price' => $data['cost-private_' . $i],
                    'lang' => $data['lang_' . $i],
                ];
            }
            $tariff->tariff = $result;
        }
        $tariff->save();

        return redirect()->back()->with('success', 'Тариф создан');
    }
}
