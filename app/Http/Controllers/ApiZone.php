<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Query;
use Domainrobot\Model\QueryFilter;
use Domainrobot\Model\QueryView;
use Domainrobot\Model\Zone;
use Domainrobot\Model\Soa;
use Domainrobot\Model\MainIp;
use Domainrobot\Model\NameServer;
use Domainrobot\Model\NameserverActionConstants;
use Domainrobot\Model\ResourceRecord;

class ApiZone extends Controller
{
    /**
     * Get an Zone Info
     * 
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function info($name, $systemNameServer) 
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\Contact
            $contact = $domainrobot->zone->info($name, $systemNameServer);
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

    public function create(Request $request){
        $domainrobot = app('Domainrobot');

        $zone = new Zone();
        $zone->setOrigin('example.com');
        $zone->setSoa(new Soa([
            "refresh" => 43200,
            "retry" => 7200,
            "expire" => 1209600,
            "email" => "someone@example.com"
        ]));

        $zone->setAction(NameserverActionConstants::COMPLETE);
        $zone->setNameServers([
            new NameServer([
                "name" => "a.ns14.net"
            ]),
            new NameServer([
                "name" => "b.ns14.net"
            ]),
            new NameServer([
                "name" => "c.ns14.net"
            ]),
            new NameServer([
                "name" => "d.ns14.net"
            ])
        ]);

        $zone->setResourceRecords([
            new ResourceRecord([
                "name" => "subdomain",
                "type" => "A",
                "value" => "198.51.100.1",
                //"pref" => 1 // optional
            ]),
             new ResourceRecord([
                "name" => "mail",
                "type" => "A",
                "value" => "198.51.100.1",
                //"pref" => 1 // optional
            ]),
             new ResourceRecord([
                "name" => "",
                "type" => "MX",
                "value" => "198.51.100.1",
                "pref" => 10
            ])
        ]);

        try {
            // Domainrobot\Model\Contact
            $contact = $domainrobot->zone->create($zone);
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

    /**
     * List Zones
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $filters = [];
            foreach ( $request->filters as $filter ) {
                // Overview of Permitted List Query Operators
                // https://help.internetx.com/display/APIXMLEN/List+Inquire#ListInquire-PermittedOperatorsforaListQuery
                $filters[] = new QueryFilter([
                    'key' => $filter['key'],
                    'value' => $filter['value'],
                    'operator' => $filter['operator']
                ]);
            }

            $query = new Query([
                'filters' => $filters,
                'view' => new QueryView([
                    'children' => 1,
                    'limit' => 10
                ])
            ]);
            
            $list = $domainrobot->zone->list($query);

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
