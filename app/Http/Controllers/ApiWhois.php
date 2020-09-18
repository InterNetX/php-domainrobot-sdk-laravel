<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;

class ApiWhois extends Controller
{
    /*
    Whois Single Example Request

    GET /api/whois/example.de
    */

    /**
     * Sends a Whois Single Request
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $whoisStatus = $domainrobot->whois->single($request->domain);

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

    /*
    Whois Multi Example Request

    GET /api/whois/example.de
    {
      "domains": [
        "example.de",
        "domain.com",
        "php.net"
      ]
    }
    */

    /**
     * Sends a Whois Multi Request
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multi(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $whoisStatus = $domainrobot->whois->multi($request->domains);

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
