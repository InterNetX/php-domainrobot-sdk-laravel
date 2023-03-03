<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Redirect;
use Illuminate\Http\Request;;

class ApiRedirect extends Controller
{
    /**
     * Creating a new redirect.
     * https://help.internetx.com/display/APIXMLDE/Redirect+Create
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) 
    {
        $domainrobot = app('Domainrobot');

        $keys = [];
        if ( isset($request->keys) ) {
            $keys = $request->keys;
        }

        try {

            // Domainrobot\Model\Redirect
            $redirect = new Redirect();

            $redirect->setSource($request->source);
            $redirect->setTarget($request->target);
            $redirect->setType($request->type); // DOMAIN & EMAIL
            $redirect->setMode($request->mode); // CATCHALL, FRAME, HTTP, HTTPS & SINGLE

            // Domainrobot\Model\Redirect
            $redirectObject = $domainrobot->redirect->create($redirect, $keys);

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
