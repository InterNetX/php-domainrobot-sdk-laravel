<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;

class ApiUser2fa extends Controller
{
    /*
    Get 2FA Configuration Example Request

    GET /api/OTPAuth
    */

    /**
     * Get Info about the 2FA Configuration
     *  
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenConfigInfo(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user2fa->tokenConfigInfo();
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
    Generate 2FA Secret Example Request

    POST /api/OTPAuth
    */

    /**
     * Generate 2FA Secret
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenConfigCreate(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user2fa->tokenConfigCreate();
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
    Activate 2FA Authentication Example Request

    PUT /api/user/_2fa
    */

    /**
     * Activate the 2FA Authentication
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenConfigActivate(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user2fa->tokenConfigActivate();
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
    Deactivate 2FA Authentication Example Request

    DELETE /api/user/_2fa
    */

    /**
     * Deactivate the 2FA Authentication
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenConfigDelete(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user2fa->tokenConfigDelete();
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
