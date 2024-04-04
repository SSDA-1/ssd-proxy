<?php

namespace ssd\proxies\Http\Controllers;

use ssd\proxies\Models\ProjectStatus;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ProjectStatusController extends Controller
{
  public function getStatus()
  {
      return ProjectStatus::find(1)->value('is_domain_active');
  }
}
