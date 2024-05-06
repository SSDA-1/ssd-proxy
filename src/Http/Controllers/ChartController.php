<?php

declare(strict_types=1);

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\User;
use Ssda1\proxies\Models\HistoryOperation;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;

class ChartController extends Controller
{
    public array $attributes = [
        'labelsAllTime' => null,
        'datasetAllTime' => null,
        'labelsByCurrentMonth' => null,
        'datasetByCurrentMonth' => null,
        'labelsByInput' => null,
        'datasetByInput' => null,
        'datasetByCurrentYear' => null,
        'labelsByCurrentYear' => null,
        'labelsByCentury' => null,
        'datasetByCentury' => null
    ];

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    public function portsByInputValue(Request $request): JsonResponse
    {
        $inputDate = $request->input('datePicker');
        $firstInputDate = substr($inputDate, 0, 10);
        $secondInputDate = substr($inputDate, 13);

        try {
            $formattedFirstInputDate = Carbon::parse($firstInputDate);
            $formattedSecondInputDate = Carbon::parse($secondInputDate)->addHours(23);
        } catch (\Exception $exception) {
            $this->log(
                'График (input)',
                "Ошибка! Дата-парсинг",
                'Chart (input)',
                "Error! Data Parsing"
            );

            dump('error with parse');
        }

        $diffBetweenInputDates = $formattedFirstInputDate->diffInDays($formattedSecondInputDate);

        if ($diffBetweenInputDates >= 365) {
            $this->portsByCentury($formattedFirstInputDate, $formattedSecondInputDate);
            $this->attributes['labelsByInput'] = $this->attributes['labelsByCentury'];
            $this->attributes['datasetByInput'] = $this->attributes['datasetByCentury'];
        } elseif ($diffBetweenInputDates > 60) {
            $this->portsByYear($formattedFirstInputDate, $formattedSecondInputDate);
        } else {
            $this->portsByMonth($formattedFirstInputDate, $formattedSecondInputDate);
        }

        try {
            $chart = new ChartController();
            $chart->attributes['labelsByInput'] = $this->attributes['labelsByInput'];
            $chart->attributes['datasetByInput'] = $this->attributes['datasetByInput'];
        } catch (\Exception $exception) {
            $this->log(
                'График (input)',
                "Ошибка! Некорректное сохранение данных",
                'Chart (input)',
                "Error! Data Parsing"
            );
        }


        return response()->json($chart->attributes);
    }
    /**
     * Выдает информацию по продажам за все время
     * если разница между первой покупкой и последней больше одного года - выводится статистика по годам,
     * если разница меньше 365 дней - выводится статистика за год,
     * @return $this
     */
    public function portsByAllTime(): static
    {
        $purchaseOperations = HistoryOperation::where(function ($query) {
            $query->where('notes', 'Покупка прокси')
                ->orWhere('notes', 'Продление прокси');
        })->orderBy('created_at')->first();

        if (!$purchaseOperations) {
            return $this;
        }

        $firstDt = Carbon::createFromFormat('Y-m-d H:i:s', $purchaseOperations->created_at);
        $latestDt = Carbon::now();
        $diff = $firstDt->diffInDays($latestDt);


        try {
            if ($diff >= 365) {
                $this->portsByCentury($firstDt, $latestDt);
                $this->attributes['labelsAllTime'] = $this->attributes['labelsByCentury'];
                $this->attributes['datasetAllTime'] = $this->attributes['datasetByCentury'];
            } else {
                $this->portsByYear();
                $this->attributes['labelsAllTime'] = $this->attributes['labelsByCurrentYear'];
                $this->attributes['datasetAllTime'] = $this->attributes['datasetByCurrentYear'];
            }
        } catch (\Exception $exception) {
            $this->log(
                'График (alltime)',
                "Ошибка! Некорректное сохранение данных",
                'Chart (alltime)',
                "Error! Incorrect data retention"
            );
        }

        return $this;
    }
    public function portsByCentury(Carbon $firstDate = null, Carbon $secondDate = null): static
    {
        // Определение дат по умолчанию, если они не переданы
        if ($firstDate === null && $secondDate === null) {
            $firstYear = Carbon::now()->startOfYear();
            $lastYear = Carbon::now()->endOfYear();
        } else {
            $firstYear = $firstDate;
            $lastYear = $secondDate;
        }
        // Выполнение запроса к базе данных для получения данных из таблицы history_operations
        $operationType = ["Покупка прокси", "Продление прокси"];
        $salesData = HistoryOperation::select(DB::raw("COUNT(*) as count"), DB::raw('YEAR(created_at) as year_name'))
            ->whereIn('notes', $operationType)
            ->whereBetween('created_at', [$firstYear, $lastYear])
            ->groupBy(DB::raw("year_name"))
            ->orderBy('year_name', 'ASC')
            ->get();
        // Формирование массивов с данными для графика
        $datasets = $salesData->pluck('count')->toArray();
        $labels = $salesData->pluck('year_name')->toArray();
        // Сохранение результатов в атрибуты объекта
        try {
            if ($firstDate === null && $secondDate === null) {
                $this->attributes['labelsByCentury'] = $labels;
                $this->attributes['datasetByCentury'] = $datasets;
            } else {
                $this->attributes['labelsByInput'] = $labels;
                $this->attributes['datasetByInput'] = $datasets;
            }
        } catch (\Exception $exception) {
            $this->log(
                'График (century)',
                "Ошибка! Некорректное сохранение данных",
                'Chart (century)',
                "Error! Incorrect data retention"
            );
        }

        return $this;
    }
    /**
     * Выдает информацию по продажам за год если были введены значения.
     * Если значений нет, то выводится информация за текущий год
     * @return $this
     */
    public function portsByYear(): static
    {
        $startOfCurrentYear = Carbon::now()->startOfYear();
        $endOfCurrentYear = Carbon::now()->endOfYear();
        $activePortsByYear = HistoryOperation::select(DB::raw("COUNT(*) as count"), DB::raw('DATE_FORMAT(created_at, "%Y-%m")  as month_name'))
            ->where('notes', 'Покупка прокси')
            ->orWhere('notes', 'Продление прокси')
            ->whereBetween('created_at', [$startOfCurrentYear, $endOfCurrentYear])
            ->groupBy(DB::raw("month_name"))
            ->pluck('count', 'month_name');
        // Создание коллекции дней между началом и концом текущего года
        $collectionOfDays = $this->generateDateRange($startOfCurrentYear, $endOfCurrentYear);
        // Создание массивов $datasets и $labels
        $datasets = [];
        $labels = [];
        foreach ($collectionOfDays as $date) {
            $monthName = $date->format('Y-m');
            $datasets[] = $activePortsByYear[$monthName] ?? 0;
            $labels[] = $monthName;
        }
        // Форматирование меток
        $formattedLabels = array_map(function ($label) {
            return Carbon::createFromFormat('Y-m', $label)->isoFormat('MMMM Y');
        }, $labels);
        // Запись результатов в атрибуты объекта
        try {
            $this->attributes['labelsByCurrentYear'] = $formattedLabels;
            $this->attributes['datasetByCurrentYear'] = $datasets;
        } catch (\Exception $exception) {
            $this->log(
                'График (year)',
                "Ошибка! Некорректное сохранение данных",
                'Chart (year)',
                "Error! Incorrect data retention"
            );
        }

        return $this;
    }
    // Вспомогательная функция для создания коллекции дат между двумя датами
    private function generateDateRange(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];
        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->copy();
            $startDate->addMonth();
        }
        return $dates;
    }
    /**
     * Выдает информацию по продажам за неделю если были введены значения.
     * Если значений нет, то выводится информация по текущей неделе
     * @return $this
     */
    public function portsByMonth(Carbon $firstDate = null, Carbon $secondDate = null): static
    {
        // Определение дат по умолчанию, если они не переданы
        if ($firstDate === null && $secondDate === null) {
            $firstDayOfMonth = Carbon::now()->startOfMonth();
            $lastDayOfMonth = Carbon::now()->endOfMonth();
        } else {
            $firstDayOfMonth = $firstDate;
            $lastDayOfMonth = $secondDate;
        }
        // Выполнение запроса к базе данных для получения данных из таблицы history_operations
        $operationType = ["Покупка прокси", "Продление прокси"];
        $salesData = HistoryOperation::select(DB::raw("COUNT(*) as count"), DB::raw('DATE(created_at) as date'))
            ->whereIn('notes', $operationType)
            ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->groupBy(DB::raw("date"))
            ->orderBy('date', 'ASC')
            ->get();
        // Формирование массивов с данными для графика
        $datasets = $salesData->pluck('count')->toArray();
        $labels = $salesData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->isoFormat('D MMM.Y');
        })->toArray();
        // Сохранение результатов в атрибуты объекта
        try {
            if ($firstDate === null && $secondDate === null) {
                $this->attributes['labelsByCurrentMonth'] = $labels;
                $this->attributes['datasetByCurrentMonth'] = $datasets;
            } else {
                $this->attributes['labelsByInput'] = $labels;
                $this->attributes['datasetByInput'] = $datasets;
            }
        } catch (\Exception $exception) {
            $this->log(
                'График (month)',
                "Ошибка! Некорректное сохранение данных",
                'Chart (month)',
                "Error! Incorrect data retention"
            );
        }

        return $this;
    }
    /**
     * Создаем массив $datePeriod и записываем в него даты, которые входят в диапазон с $firstDate по $secondDate.
     * Создаем коллекцию $collectionOfDays, ключи которой равны значениям массива $datePeriod,
     * а значения равны нулю
     * @param string $dateInterval
     * @param string $format
     * @param $firstDate
     * @param $secondDate
     * @return Collection
     */
    private function someFunc(string $dateInterval, string $format, $firstDate, $secondDate): Collection
    {
        $datePeriod = [];
        Carbon::macro('datePeriod', static function ($startDate, $endDate) use ($dateInterval) {
            return new DatePeriod($startDate, new DateInterval($dateInterval), $endDate);
        });
        foreach (Carbon::datePeriod(
            Carbon::createMidnightDate($firstDate),
            Carbon::createMidnightDate($secondDate)
        ) as $date) {
            $datePeriod[] = $date->format($format);
        }

        $array_flip = array_flip($datePeriod);
        $collection = collect([]);
        foreach ($array_flip as $key => $value) {
            $collection[$key] = 0;
        }
        return $collection;
    }

    /**
     * Вывод сатистики по покупкам прокси.
     *
     * @return array
     */
    public function statisticSell(): array
    {
        $days = HistoryOperation::where(function ($query) {
            $query->where('notes', 'Покупка прокси')
                ->orWhere('notes', 'Продление прокси');
        })->orderBy('created_at', 'desc')->get()->groupBy(function($item) {
            return $item->created_at->format('d-m-y');
        })->toArray();

        $result = [];
        foreach ($days as $key => $day) {
            foreach ($day as $key2 => $sell) {
                $user = User::find((int)$sell['user_id']);
                $day[$key2]['user'] = "$user->name $user->email $user->telegram_name";
            }

            $result[$key] = [
                'date' => $key,
                'count' => count($day),
                'sum' => array_sum(array_map(function($item) {
                    return abs((float)$item['amount']);
                }, $day)),
                'day' => $day
            ];
        }

        return $result;
    }

    /**
     * Вывод сатистики по покупкам прокси по дням.
     *
     * @return JsonResponse
     */
    public function statisticSellDay(): JsonResponse
    {
        $days = HistoryOperation::where(function ($query) {
            $query->where('notes', 'Покупка прокси')
                ->orWhere('notes', 'Продление прокси');
        })->orderBy('created_at', 'desc')->get()->groupBy(function($item) {
            return $item->created_at->format('d-m-y');
        })->toArray();

        $result = [];
        foreach ($days as $key => $day) {
            foreach ($day as $key2 => $sell) {
                $user = User::find((int)$sell['user_id']);
                $day[$key2]['user'] = "$user->name $user->email $user->telegram_name";
            }

            $result[$key] = [
                'date' => $key,
                'count' => count($day),
                'sum' => array_sum(array_map(function($item) {
                    return abs((float)$item['amount']);
                }, $day)),
                'day' => $day
            ];
        }

        return response()->json(['data' => $result]);
    }

    /**
     * Вывод сатистики по покупкам прокси по месяцам.
     *
     * @return JsonResponse
     */
    public function statisticSellMonth(): JsonResponse
    {
        $months = HistoryOperation::where(function ($query) {
            $query->where('notes', 'Покупка прокси')
                ->orWhere('notes', 'Продление прокси');
        })->orderBy('created_at', 'desc')->get()->groupBy([
            function ($item) {
                return $item->created_at->format('m-y');
            },
            function ($item) {
                return $item->created_at->format('d-m-y');
            }
        ])->toArray();

        $result = [];
        foreach ($months as $key => $month) {
            foreach ($month as $key2 => $day) {
                foreach ($day as $key3=> $sell) {
                    $user = User::find((int)$sell['user_id']);
                    $day[$key3]['user'] = "$user->name $user->email $user->telegram_name";
                }

                $result[$key][$key2] = [
                    'date' => $key2,
                    'count' => count($day),
                    'sum' => array_sum(array_map(function ($item) {
                        return abs((float)$item['amount']);
                    }, $day)),
                    'day' => $day
                ];
            }
        }

        return response()->json(['data' => $result]);
    }

    /**
     * Вывод сатистики по покупкам прокси по годам.
     *
     * @return JsonResponse
     */
    public function statisticSellYear(): JsonResponse
    {
        $years = HistoryOperation::where(function ($query) {
            $query->where('notes', 'Покупка прокси')
                ->orWhere('notes', 'Продление прокси');
        })->orderBy('created_at', 'desc')->get()->groupBy([
            function ($item) {
                return $item->created_at->format('y');
            },
            function ($item) {
                return $item->created_at->format('m-y');
            },
            function ($item) {
                return $item->created_at->format('d-m-y');
            }
        ])->toArray();

        $result = [];
        foreach ($years as $key => $year) {
            foreach ($year as $key2 => $months) {
                foreach ($months as $key3 => $days) {
                    foreach ($days as $key4 => $sell) {
                        $user = User::find((int)$sell['user_id']);
                        $days[$key4]['user'] = "$user->name $user->email $user->telegram_name";
                    }

                    $result[$key][$key2][$key3] = [
                        'date' => $key3,
                        'count' => count($days),
                        'sum' => array_sum(array_map(function ($item) {
                            return abs((float)$item['amount']);
                        }, $days)),
                        'day' => $days
                    ];
                }
            }
        }

        return response()->json(['data' => $result]);
    }
}
