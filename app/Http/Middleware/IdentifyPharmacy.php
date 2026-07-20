<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class IdentifyPharmacy
{
    /**
     * Sets Spatie's team context for permission checks. Eloquent tenant scoping
     * (PharmacyScope) does NOT depend on this middleware — it reads the current
     * pharmacy straight from the authenticated user, since route-model-binding can
     * resolve before route middleware runs and must already be scoped by then.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($pharmacyId = currentPharmacyId()) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($pharmacyId);
        }

        return $next($request);
    }
}
