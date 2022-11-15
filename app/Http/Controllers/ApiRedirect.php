<?php

namespace App\Http\Controllers;

use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRedirect extends Controller
{
    public function create(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            $redirect = new Redirect();

            // Set the source domain
            $redirect->setSource("flutter-blub.com");

            // Set the target path
            $redirect->setTarget("flutter-blub.com/test");

            // Set type DOMAIN
            $redirect->setType("DOMAIN");

            // Set mode HTTP
            $redirect->setMode("HTTP");
            $redirect->setTitle("Testredirect");

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
            $redirect = $domainrobot->redirect->info('flutter-blub.com');
            $redirect->setTitle($request->input('title'));
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
