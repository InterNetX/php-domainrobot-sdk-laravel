<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiUserCreateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\ApiUserRequest;
use Domainrobot\Lib\DomainrobotException;
use Domainrobot\Model\Query;
use Domainrobot\Model\QueryFilter;
use Domainrobot\Model\QueryView;
use Domainrobot\Model\ServiceUsersProfile;
use Domainrobot\Model\User;
use Domainrobot\Model\UserDetails;
use Domainrobot\Model\UserProfile;

class ApiUser extends Controller
{
    /*
    Create Example Request

    POST /api/user
    {
      "user": "autodns-user",
      "defaultEmail": "autodns-user@internetx.com",
      "password": "secret123",
      "details": {
        "fname": "firstname",
        "lname": "lastname",
        "organization": "InterNetX GmbH",
        "phone": "+49 941 12345-67"
      }
    }
    */

    /**
     * Create an User
     * 
     * @param  ApiUserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ApiUserCreateRequest $request) 
    {
        $domainrobot = app('Domainrobot');

        $userModel = new User();

        $userModel->setUser($request->user);
        $userModel->setContext($request->context ?? '');
        $userModel->setDefaultEmail($request->defaultEmail);
        $userModel->setPassword($request->password ?? '');
        
        if ( isset($request->details) && !empty($request->details) ) {

            $userModel->setDetails(new UserDetails([
                'fname' => $request->details['fname'] ?? '',
                'lname' => $request->details['lname'] ?? '',
                'organization' => $request->details['organization'] ?? '',
                'phone' => $request->details['phone'] ?? '',
                'passwordResetEmail' => $request->details['passwordResetEmail'] ?? ''
            ]));
        }

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user->create($userModel);
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
    Read Example Request

    GET /api/user/{user}/{context}
    */

    /**
     * Get an User Info
     * 
     * @param  ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user->info($request->user, $request->context);
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
    Update Example Request

    PUT /api/user/{user}/{context}
    {
      "defaultEmail": "autodns-user@internetx.xyz",
      "password": "abc123"
    }
    */

    /**
     * Update an existing User
     * 
     * @param  ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ApiUserRequest $request) 
    {
        $domainrobot = app('Domainrobot');

        try {

            $user = $domainrobot->user->info($request->user, $request->context);

            if ( isset($request->context) ) {
                $user->setContext($request->context);
            }

            if ( isset($request->defaultEmail) ) {
                $user->setDefaultEmail($request->defaultEmail);
            }

            if ( isset($request->password) ) {
                $user->setPassword($request->password);
            }

            if ( isset($request->details) && !empty($request->details) ) {

                $userDetails = new UserDetails();

                if ( isset($request->details['fname']) ) {
                    $userDetails->setFname($request->details['fname']);
                }

                if ( isset($request->details['lname']) ) {
                    $userDetails->setLname($request->details['lname']);
                }

                if ( isset($request->details['organization']) ) {
                    $userDetails->setOrganization($request->details['organization']);
                }

                $user->setDetails($userDetails);
            }
        
            // Domainrobot\Model\User
            $user = $domainrobot->user->update($user);

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
    Delete Example Request

    DELETE /api/user/{user}/{context}
    */

    /**
     * Delete an User
     * 
     * @param  ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $user = $domainrobot->user->delete($request->user, $request->context);
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
    List Example Request

    POST /api/user/_search
    {
      "filters": [
        {
          "key": "status",
          "value": "2",
          "operator": "EQUAL"
        }
      ]
    }
    */

    /**
     * List User
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

            $list = $domainrobot->user->list($query);

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
    Inquiring the Billing Limit Example Request

    GET /api/user/billinglimit
    */

    /**
     * Inquiring the Billing Limit for the User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function billingObjectLimitInfo(Request $request) 
    {
        $domainrobot = app('Domainrobot');

        $keys = [];
        if ( isset($request->keys) ) {
            $keys = $request->keys;
        }

        $articleTypes = [];
        if ( isset($request->articleTypes) ) {
            $articleTypes = $request->articleTypes;
        }

        try {
            // Domainrobot\Model\BillingObjectLimit
            $billingLimitObjects = $domainrobot->user->billingObjectLimitInfo($keys, $articleTypes);

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
    Inquiring the Billing Terms Example Request

    GET /api/user/billingterm
    */

    /**
     * Inquiring the Billing Terms for the User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function billingObjectTermsInfo() 
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\BillingObjectTerms
            $billingTermObjects = $domainrobot->user->billingObjectTermsInfo();

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
    Lock Example Request

    PUT /api/user/{username}/{context}/_lock
    */

    /**
     * Lock an User
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLock(ApiUserRequest $request) 
    {
        $domainrobot = app('Domainrobot');

        $keys = [];
        if ( isset($request->keys) ) {
            $keys = $request->keys;
        }

        try {
            // Domainrobot\Model\BasicUser
            $basicUser = $domainrobot->user->updateLock($request->user, $request->context, $keys);

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
    Unlock Example Request

    PUT /api/user/{username}/{context}/_unlock
    */

    /**
     * Unlock an User
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUnlock(ApiUserRequest $request) 
    {
        $domainrobot = app('Domainrobot');

        $keys = [];
        if ( isset($request->keys) ) {
            $keys = $request->keys;
        }

        try {
            // Domainrobot\Model\BasicUser
            $basicUser = $domainrobot->user->updateUnlock($request->user, $request->context, $keys);

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
    Copy User Example Request

    POST /api/user/{username}/{context}/copy
    {
      "user": "new-username",
      "defaultEmail": "new-username@mail.com",
      "password": "abcdef123456"
    }
    */

    /**
     * Copy an User
     *
     * @param string $user
     * @param string $context
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copy($user, $context, Request $request)
    {
        $domainrobot = app('Domainrobot');

        try {
            // Domainrobot\Model\User
            $userObject = $domainrobot->user->info($user, $context);

            $userObject->setUser($request->user);

            $userObject->setDefaultEmail($request->defaultEmail);

            if ( isset($request->password) ) {
                $userObject->setPassword($request->password);
            }

            // Domainrobot\Model\BasicUser
            $basicUser = $domainrobot->user->copy($user, $context, $userObject);
            
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
    User Profile Info Example Request

    GET /api/user/{username}/{context}/profile
    */

    /**
     * Get an User Profile Info
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileInfo(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        $prefix = '';
        if ( isset($request->prefix) ) {
            $prefix = $request->prefix;
        }
 
        try {
            // Domainrobot\Model\UserProfileViews
            $userProfileViews = $domainrobot->user->profileInfo($request->user, $request->context, $prefix);
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
    Update User Profile Example Request

    PUT /api/user/{username}/{context}/profile
    {
      "profiles": [
        {
          "key": "domain_nserver1",
          "value": "ns1.example.com",
          "flag": "RECURSE",
          "readonly": false
        },
        {
          "key": "domain_nserver2",
          "value": "ns2.example.com",
          "flag": "RECURSE",
          "readonly": false
        }
      ]
    }
    */

    /**
     * Update the User Profile
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            $userProfileViews = $domainrobot->user->profileInfo($request->user, $request->context);

            // Domainrobot\Model\UserProfileViews
            $userProfileViews = $domainrobot->user->profileInfo($request->user, $request->context);

            $userProfiles = $userProfileViews->getProfiles();

            array_walk($userProfiles, function(&$userProfile) use ($request) {

                $requestKey = array_search(
                    $userProfile['key'], 
                    array_column($request->profiles, 'key')
                );

                if ( $requestKey !== FALSE ) {
                    $userProfile = new UserProfile($request->profiles[$requestKey]);
                }
            });

            $userProfileViews->setProfiles($userProfiles);

            // Domainrobot\Model\UserProfileViews
            $userProfileViews = $domainrobot->user->profileUpdate($request->user, $request->context, $userProfileViews);

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
    User Service Profile Info Example Request

    GET /api/user/{username}/{context}/serviceProfile
    */

    /**
     * Inquiring the User Service Profile
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function serviceProfileInfo(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        $prefix = '';
        if ( isset($request->prefix) ) {
            $prefix = $request->prefix;
        }
 
        try {
            // Domainrobot\Model\ServiceProfiles
            $serviceProfileViews = $domainrobot->user->serviceProfileInfo($request->user, $request->context, $prefix);
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
    Update User Service Profile Example Request

    PUT /api/user/{username}/{context}/serviceProfile
    {
      {
	    "serviceProfiles": [
		  {
		    "key": "techc",
		    "value": "23242526"
		  }
	    ]
      }
    }
    */

    /**
     * Inquiring the User Service Profile
     *
     * @param ApiUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function serviceProfileUpdate(ApiUserRequest $request)
    {
        $domainrobot = app('Domainrobot');

        try {

            // Domainrobot\Model\ServiceProfiles
            $serviceProfileViews = $domainrobot->user->serviceProfileInfo($request->user, $request->context);

            $serviceProfiles = $serviceProfileViews->getServiceProfiles();

            array_walk($serviceProfiles, function(&$serviceProfile) use ($request) {

                $requestKey = array_search(
                    $serviceProfile['key'], 
                    array_column($request->serviceProfiles, 'key')
                );

                if ( $requestKey !== FALSE ) {
                    $serviceProfile = new ServiceUsersProfile($request->serviceProfiles[$requestKey]);
                }
            });

            $serviceProfileViews->setServiceProfiles($serviceProfiles);

            // Domainrobot\Model\ServiceProfiles
            $serviceProfileViews = $domainrobot->user->serviceProfileUpdate($request->user, $request->context, $serviceProfileViews);

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
