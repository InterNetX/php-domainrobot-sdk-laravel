<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Redirect;

class ApiRedirect extends Controller
{
    public function create(Request $request)
    {
        $domainrobot = app('Domainrobot');

        $keys = [];
        if ( isset($request->keys) ) {
            $keys = $request->keys;
        }

        try {

            $redirect = new Redirect();

             // Set the source domain
            $redirect->setSource($request->source);

            // Set the target path
            $redirect->setTarget($request->target);

            // Set type DOMAIN, EMAIL
            $redirect->setType($request->type);

            // Set mode CATCHALL, FRAME, HTTP, HTTPS & SINGLE
            $redirect->setMode($request->mode); 

            // Set title
            $redirect->setTitle($request->title);

            $job = $domainrobot->redirect->create($redirect);

        } catch (DomainrobotException $exception) {
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

    public function update($source, Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $redirect = $domainrobot->redirect->info($request->source);

            // Change title
            $redirect->setTitle($request->title);

            $domainrobot->redirect->update($redirect);

        } catch (DomainrobotException $exception) {
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
