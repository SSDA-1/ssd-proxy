<?php

namespace ssd\proxies\Service;

use ssd\proxies\Models\ProcessLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProcessLogService
{
    public function createProcessLog($name, $description, $name_en = null, $description_en = null)
    {
        $maxRowCount = 1000;

        $currentRowCount = ProcessLog::count();

        if ($currentRowCount >= $maxRowCount) {
            $rowCountToDelete = $currentRowCount - $maxRowCount + 1;
            ProcessLog::orderBy('created_at')->limit($rowCountToDelete)->delete();
        }

        ProcessLog::create([
            'name' => $name,
            'description' => $description,
            'name_en' => $name_en,
            'description_en' => $description_en
        ]);
    }
}
