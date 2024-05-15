<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\ProjectStatus;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class ProjectStatusController extends Controller
{
  public function getStatus()
  {
      if (Schema::hasTable('project_status')) {
          return ProjectStatus::find(1)->value('is_domain_active') ?? 0;
      }

      return 0;
  }
}
