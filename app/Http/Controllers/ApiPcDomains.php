<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\ApiPcDomainsEstimateRequest;
use App\Http\Requests\ApiPcDomainsKeywordRequest;
use Domainrobot\Lib\DomainrobotAuth;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Lib\DomainrobotHeaders;
use Domainrobot\Model\Estimation;
use Domainrobot\Model\Keywords;
use Domainrobot\Model\Domains;
use stdClass;

class ApiPcDomains extends Controller
{
    /*
    Estimation Example Request

    POST /api/estimate
    {
      "domains": [
	    "internetx.com",
		"example.de"
	  ],
      "currency": "EUR"
    }
    */

    /**
     * Sends an Estimation Request
     * Estimates the value for the given domain
     * 
     * @param  ApiPcDomainsEstimateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function estimate(ApiPcDomainsEstimateRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            // Domainrobot\Model\Estimation
            $estimation = new Estimation();
            $estimation->setDomains($request->domains);
            $estimation->setCurrency($request->currency);

            $domainrobot->pcDomains->estimation($estimation);

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
    Alexa Example Request

    GET /api/alexa/{domain}
    */

    /**
     * Sends an Alexa Site Info Request
     *
     * @param string $domain
     * @return \Illuminate\Http\JsonResponse
     */
    public function alexa($domain)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->alexa($domain);

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
    Exchangerate Example Request

    GET /api/exchangerate/{source}/{target}
    */

    /**
     * Sends an Exchangerate Request
     *
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangerate($sourceCurrency, $targetCurrency)
    {
        $domainrobot = app('Domainrobot');

        Log::debug($sourceCurrency);
        Log::debug($targetCurrency);

        Log::debug($domainrobot->getDomainrobotConfig()->getUrl() . "/v1/exchangerate/$sourceCurrency/$targetCurrency");

        try {

            $domainrobot->pcDomains->exchangerate($sourceCurrency, $targetCurrency);

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
    DomainStudio Example Request

    GET /api/domainstudio/{keyword}
    */

    /**
     * Sends an DomainStudio Request
     * Get a list of domain name suggestions
     *
     * @param string $keyword
     * @return \Illuminate\Http\JsonResponse
     */
    public function domainstudio($keyword)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->domainstudio($keyword);

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
    Keyword Example Request

    POST /api/keyword
    {
      "keywords": [
        "bicycling",
        "hiking"
      ]
    }
    */

    /**
     * Sends an Keyword Request
     * Get Google Ad Words Data
     *
     * @param ApiPcDomainsKeywordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyword(ApiPcDomainsKeywordRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $keywords = new Keywords();
            $keywords->setKeywords($request->keywords);

            $domainrobot->pcDomains->keyword($keywords);


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
    Meta Example Request

    GET /api/meta/{domain}
    */

    /**
     * Sends an Meta Request
     * Get Meta Information like Online Status, Site Title, Site Description 
     *
     * @param string $domain
     * @return \Illuminate\Http\JsonResponse
     */
    public function meta($domain)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->meta($domain);

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
    Sistrix Example Request

    GET /api/sistrix/{domain}/{country}
    */

    /**
     * Sends an Sistrix Request
     *
     * @param string $domain
     * @param string $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function sistrix($domain, $country)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->sistrix($domain, $country);

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
    Majestic Example Request

    POST /api/keyword
    {
        "domains": [
            "internetx.com"
        ]
    }
    */

    /**
     * Sends an Majestic Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function majestic(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domains = new Domains();
            $domains->setDomains($request->domains);

            $domainrobot->pcDomains->majestic($domains);

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
    Social Media Username Check Example Request

    GET /api/smu_check/{username}
    */

    /**
     * Sends an Social Media User Check Request
     * Checks if Username is available on different Social Media Platforms 
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function smuCheck($username)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->smuCheck($username);

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
    Wayback Example Request

    GET /api/wayback/{domain}
    */

    /**
     * Sends an Wayback Request
     * Retrieve Info rom Wayback Snapshot Archive
     * 
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function wayback($domain)
    {
        $domainrobot = app('Domainrobot');

        try {

            $domainrobot->pcDomains->wayback($domain);

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
