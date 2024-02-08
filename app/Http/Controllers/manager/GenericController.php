<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GenericController extends Controller
{
    /**
     * Kijkt of het IP van de gebruiker overeenkomt met de geconfigureerde IP adressen
     * @param \Illuminate\Http\Request $request
     * @return bool true als IP toegestaan is, anders false
     */
    public function isIntermixIP(Request $request): bool
    {
        $requestIp = $request->ip();
        $envIp = explode("=", getenv("INTERMIXIP"));

        if (empty($envIp)) {
            return false;
        }

        $allowedIps = explode(",", $envIp[0]);
        if (in_array($requestIp, $allowedIps, true)) {
            return true;
        }

        return false;
    }
}
