<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\BulkDomainPatchRequest;
use Domainrobot\Model\NameServer;

class ApiDomainBulk extends Controller
{
    /*
    Estimation Example Request

    PATCH /bulk/domain
	  {
	    "domains": [
		    {
		      "name": "example.com",
		      "nameservers": [
			      {
			        "name": "ns1.example.com",
			        "ipAddresses": [
				        "111.112.113.114"
			        ]
			      },
			      {
			        "name": "ns2.example.com",
			        "ipAddresses": [
				        "211.212.213.214"
			        ]
			      }
		  ],
		  "confirm_owner_consent": true,
		  "ownerc_contact_id": 23234103
		},
		{
		  "name": "example.de",
		  "nameservers": [
			  {
			    "name": "ns1.example.de",
			    "ipAddresses": [
				    "112.113.114.115"
			    ]
			  },
			  {
			    "name": "ns2.example.de",
			    "ipAddresses": [
				    "212.213.214.215"
			    ]
			  }
		  ],
		  "confirm_owner_consent": true,
		  "ownerc_contact_id": 23234102
		}
    */

    /**
     * Sends an Bulk Domain Update
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $domainrobot = app('Domainrobot');

        $domains = [];
        foreach ($request->domains as $domain) {

            // Domainrobot\Model\Domain
            $domainModel = $domainrobot->domain->info($domain['name']);

            if ( isset($domain['nameservers']) ) {

                $nameServers = [];
                foreach ( $domain['nameservers'] as $nameServer ) {

                    $nameServers[] = new NameServer([
                        'name' => $nameServer['name'],
                        'ipAddresses' => $nameServer['ipAddresses']
                    ]);
                }

                $domainModel->setNameServers($nameServers);
            }

            if (isset(
                $domain['confirm_owner_consent'],
                $domain['ownerc_contact_id']
            )) {

                $domainModel->setConfirmOwnerConsent($domain['confirm_owner_consent']);

                $contactModel = $domainrobot->contact->info($domain['ownerc_contact_id']);

                $domainModel->setOwnerc($contactModel);
            }

            $domains[] = $domainModel;
        }

        $bulkDomainPatchRequest = new BulkDomainPatchRequest();
        $bulkDomainPatchRequest->setObjects($domains);

        try {

            $jsonResponseDataDomains = $domainrobot->domainBulk->update($bulkDomainPatchRequest);

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
