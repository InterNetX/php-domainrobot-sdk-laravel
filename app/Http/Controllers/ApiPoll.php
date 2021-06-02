<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Domainrobot\Lib\DomainrobotException;

class ApiPoll extends Controller
{
    /**
     * Get Poll Info
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function info() 
    {
        $domainrobot = app('Domainrobot');

        try {

            // Domainrobot\Model\PollMessage
            $pollMessage = $domainrobot->poll->info();

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

    /**
     * Confirm Poll Message
     * 
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm($id)
    {
        $domainrobot = app('Domainrobot');

        try {
            
            $domainrobot->poll->confirm($id);

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
