<?php

namespace App\Traits;

use App\Policies\PsVendorPolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;

trait VendorAuthorizationTrait {

    protected $vendorModule;

    public function vendorAuthorizations($module, $abilities = [], $policyClass = null, $params = [])
    {
        $policy = $policyClass ? new $policyClass($module, $this) : null;

        return collect($abilities)->mapWithKeys(function ($ability) use ($policy, $params) {
            return [$ability => $policy ? $policy->{$ability}(...($params[$ability] ?? [])) : false];
        });
    }

    public function vendorAuthorization(): Attribute
    {
        $params = [
            'update' => [Auth::user(), $this],
            'create' => [Auth::user()],
            'delete' => [Auth::user(), $this],
        ];

        return Attribute::make(
            get: fn($value) => $this->vendorAuthorizations($this->vendorModule, array_keys($params), PsVendorPolicy::class, $params),
        );
    }

}
