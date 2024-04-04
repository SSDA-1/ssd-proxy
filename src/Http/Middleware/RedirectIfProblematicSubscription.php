<?php

namespace ssd\proxies\Http\Middleware;

use ssd\proxies\Http\Controllers\ProjectStatusController;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class RedirectIfProblematicSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $badStatus = (new ProjectStatusController())->getStatus();

        if ($badStatus == 0) {
            return redirect('trouble-with-payment');
        }
        return $next($request);
    }
}
