<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Query;
use Domainrobot\Model\QueryFilter;
use Domainrobot\Model\QueryView;

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
