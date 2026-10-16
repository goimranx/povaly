<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableBotbleLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Completely disable Botble license system
        config([
            'core.base.general.enable_system_updater' => false,
            'core.base.general.disable_license_activation' => true,
        ]);

        // Skip license routes
        if ($request->is('*/license/*') || 
            $request->is('*/unlicensed') || 
            $request->is('*/membership/authorize')) {
            // Return success to skip license checks
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'data' => ['valid' => true]]);
            }
            return redirect()->to(config('core.base.general.admin_dir', 'admin'));
        }

        return $next($request);
    }
}
