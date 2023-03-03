<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;

class ApiDomainPremium extends Controller
{
    public function info(Request $request) 
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\DomainPremium
            $domain = $domainrobot->domainPremium->info($request->name);
        } catch ( DomainrobotException $exception ) {
            return response()->json(
                $exception->getError(),
                $exception->getStatusCode()
            );
        }
        
        return response()->json(
            $domainrobot::getLastDomainrobotResult()->getResult(),
            $domainrobot::getLastDomainrobotResult()->getStatusCode()
        );
    }
}
