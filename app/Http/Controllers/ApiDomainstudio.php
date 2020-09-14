<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\ApiDomainRequest;
use Domainrobot\Lib\DomainrobotAuth;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Lib\DomainrobotHeaders;
use Domainrobot\Model\DomainEnvelopeSearchRequest;
use Domainrobot\Model\DomainEnvelopeSearchService;
use Domainrobot\Model\DomainStudioService;
use Domainrobot\Model\DomainStudioSourceInitial;
use Domainrobot\Model\DomainStudioSourcePremium;
use Domainrobot\Model\DomainStudioSources;
use Domainrobot\Model\DomainStudioSourceSuggestion;
use Domainrobot\Model\EstimationServiceData;

class ApiDomainstudio extends Controller
{

    /**
     * 
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $domainrobot = app('Domainrobot');

        $domainEnvelopeSearchRequest = new DomainEnvelopeSearchRequest();

        $domainStudioSources = new DomainStudioSources();

        $domainStudioSources->setInitial(new DomainStudioSourceInitial([
            'services' => [ 
                DomainEnvelopeSearchService::WHOIS, 
                DomainEnvelopeSearchService::PRICE,
                DomainEnvelopeSearchService::ESTIMATION
            ] 
        ]));

        // $domainStudioSources->setSuggestion(new DomainStudioSourceSuggestion([
        //     'max' => 5,
        //     'useIdn' => true
        // ]));

        $domainStudioSources->setPremium(new DomainStudioSourcePremium([
            'max' => 5,
            'promoTlds' => [ 'rocks', 'shop' ],
            'services' => [ 
                DomainEnvelopeSearchService::WHOIS, 
                DomainEnvelopeSearchService::PRICE,
                DomainEnvelopeSearchService::ESTIMATION
            ],
            'topTlds' => [ 'de', 'com', 'net' ]
        ]));

        $domainEnvelopeSearchRequest->setSources($domainStudioSources);
        $domainEnvelopeSearchRequest->setSearchToken($request->searchToken);
        $domainEnvelopeSearchRequest->setCurrency($request->currency);

        try {

            $domainSuggestions = $domainrobot->domainStudio->logRequest(function($method, $url, $requestOptions, $headers) {
                // execute your code here
                // print_r($requestOptions);
            })->search($domainEnvelopeSearchRequest);

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
