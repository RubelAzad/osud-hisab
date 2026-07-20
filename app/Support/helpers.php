<?php

use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;

if (! function_exists('currentPharmacyId')) {
    /**
     * The authenticated user is authoritative whenever one exists (this must not depend on
     * whether IdentifyPharmacy middleware has run yet — route-model-binding can resolve
     * before route middleware does). The container binding is only a fallback for
     * non-HTTP contexts (console commands) that need to act "as" a given pharmacy.
     */
    function currentPharmacyId(): ?int
    {
        $user = Auth::user();

        if ($user) {
            return $user->is_super_admin ? null : $user->pharmacy_id;
        }

        return app()->bound('currentPharmacyId') ? app('currentPharmacyId') : null;
    }
}

if (! function_exists('currentPharmacy')) {
    function currentPharmacy(): ?Pharmacy
    {
        $user = Auth::user();

        if ($user && ! $user->is_super_admin) {
            return $user->pharmacy;
        }

        return app()->bound('currentPharmacy') ? app('currentPharmacy') : null;
    }
}

if (! function_exists('runForPharmacy')) {
    /**
     * Execute a callback scoped to the given pharmacy — for console commands / jobs that
     * have no authenticated user and must still create/query tenant-scoped records.
     */
    function runForPharmacy(Pharmacy $pharmacy, Closure $callback): mixed
    {
        app()->instance('currentPharmacyId', $pharmacy->id);
        app()->instance('currentPharmacy', $pharmacy);

        try {
            return $callback();
        } finally {
            app()->forgetInstance('currentPharmacyId');
            app()->forgetInstance('currentPharmacy');
        }
    }
}
