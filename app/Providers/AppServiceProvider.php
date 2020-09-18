<?php

namespace App\Providers;

use ReflectionClass;
use Illuminate\Support\ServiceProvider;
use App\Logging\LogCallback;
use Domainrobot\Domainrobot;
use Domainrobot\Lib\DomainrobotAuth;
use Domainrobot\Lib\DomainrobotHeaders;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->passthroughHeaders();
        $this->app->bind(
            'Domainrobot',
            function ($app) {
                return $this->getDomainrobot(
                    env('DOMAINROBOT_URL'),
                    env('DOMAINROBOT_USER'),
                    env('DOMAINROBOT_PASSWORD'),
                    env('DOMAINROBOT_CONTEXT')
                );
            }
        );

        $this->app->bind(
            'DomainrobotSSL',
            function ($app) {
                return $this->getDomainrobot(
                    env('DOMAINROBOT_URL'),
                    env('DOMAINROBOT_SSL_USER'),
                    env('DOMAINROBOT_SSL_PASSWORD'),
                    env('DOMAINROBOT_SSL_CONTEXT')
                );
            }
        );

        $this->app->bind(
            'DomainrobotPcDomains',
            function ($app) {
                return $this->getDomainrobot(
                    env('DOMAINROBOT_URL') . env('DOMAINROBOT_URL_PCDOMAINS_SUFFIX'),
                    env('DOMAINROBOT_USER'),
                    env('DOMAINROBOT_PASSWORD'),
                    env('DOMAINROBOT_CONTEXT')
                );
            }
        );
    }

    /**
     * Create an Domainrobot Instance
     * 
     * @param  string $url
     * @param  string $user
     * @param  string $pass
     * @param  string $context
     * @return object Domainrobot
     */
    protected function getDomainrobot($url, $user, $pass, $context) {

        $domainrobot = new Domainrobot([
            'url' => $url,
            'auth' => new DomainrobotAuth([
                'user' => $user,
                'password' => $pass,
                'context' => $context
            ]),
            'headers' => $this->passthroughHeaders(),
            'logRequestCallback' => function ($method, $url, $requestOptions, $headers) {
                LogCallback::dailyRequest($method, $url, $requestOptions, $headers);
            },
            'logResponseCallback' => function ($url, $response, $statusCode, $exectime){
                LogCallback::dailyResponse($url, $response, $statusCode, $exectime);
            }
        ]);

        return $domainrobot;
    }

    /**
     * Pass the to the Application sent Headers through to 
     * AutoDNS if they are valid Domainrobot Headers
     *
     * @return array $domainrobotConfigHeaders
     */
    protected function passthroughHeaders() {

        $reflect = new ReflectionClass('Domainrobot\Lib\DomainrobotHeaders');
        $validDomainrobotHeaders = $reflect->getConstants(); 

        $requestHeaders = $this->app->request->headers->all();

        $domainrobotConfigHeaders = [];
        foreach ($requestHeaders as $headerKey => $headerValue) {

            // Dont pass the user-agent and the 
            // content-type Header through
            if (
                preg_match("/^user-agent$/i", $headerKey) ||
                preg_match("/^content-type$/i", $headerKey)
            ) {
                continue;
            }

            // Search in the DomainRobotHeaders after 
            // the given key of the sent Header
            $regex = "/^" . $headerKey . "$/i";
            $search = preg_grep($regex, $validDomainrobotHeaders);

            // If the sent Header Key is an valid 
            // Domainrobot Header assign it 
            if (count($search) > 0) {
                $searchKey = key($search);

                $searchHeader = $reflect->getConstant($searchKey);

                $domainrobotConfigHeaders[$searchHeader] = $headerValue[0];
            }
        }

        return $domainrobotConfigHeaders;
    }
}
