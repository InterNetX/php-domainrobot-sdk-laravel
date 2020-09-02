# Example Implementation of the InterNetX PHP Domainrobot SDK

## Preamble

This is an example implementation of the PHP-Domainrobot-SDK by [InterNetX GmbH](https://internetx.com).

Find the documentation to the API and the SDK here:

* [https://internetx.github.io/php-domainrobot-sdk/](https://internetx.github.io/php-domainrobot-sdk/)
* [https://en.help.internetx.com/display/APIXMLEN/JSON+Technical+Documentation](https://en.help.internetx.com/display/APIXMLEN/JSON+Technical+Documentation)

The source code of the PHP-SDK can be found here: [https://github.com/InterNetX/php-domainrobot-sdk/tree/master](https://github.com/InterNetX/php-domainrobot-sdk/tree/master)

This implementation has been built using the [Laravel](https://laravel.com) Framwork.

### Requirements

* PHP >=7.3
* and all [Laravel Requirements](https://laravel.com/docs/master/installation)
* Additionally the PHP Module **php-curl** needs to be installed
* [composer](https://getcomposer.org/) (PHP Dependency Manager)
* **Optional, only necessary if you want to create a UI**: [npm](https://www.npmjs.com/) or [yarn](https://yarnpkg.com/)
* [GIT](https://git-scm.com/) installed on the System (if you want to clone directly from this repository)

### Setup the Project

Clone the Source Code from the Repository using your favourite git client (e.g. [sourcetree](https://www.sourcetreeapp.com/))
> git clone https://github.com/InterNetX/php-domainrobot-sdk-laravel.git

or alternatively you can download the example implementation as a zip file here: [https://github.com/InterNetX/php-domainrobot-sdk-laravel/archive/master.zip](https://github.com/InterNetX/php-domainrobot-sdk-laravel/archive/master.zip)

Change into the Directory of the cloned Source Code
> cd php-domainrobot-sdk-laravel

Execute [composer](https://getcomposer.org/) install, the needed PHP Libraries for the Project will be installed.
> composer install

Afterwards it is necessary to create an .env File where the Authentication Credentials will be stored in the Root Directory of the Project. A File called .env.example is also located there which will be used as a base to create the .env File
> cp .env.example .env

Open the .env File with your text editor of choice e.g.
> nano .env

In the .env file locate the following section and set your AutoDNS Authentication Credentials

```bash
#####################################
##
## Domainrobot Configuration
##
#####################################

DOMAINROBOT_URL= # DOMAINROBOT_URL: Demo: https://api.demo.autodns.com/v1, Live: https://api.autodns.com/v1
DOMAINROBOT_USER= # AutoDNS API User
DOMAINROBOT_PASSWORD= # AutoDNS API Password
DOMAINROBOT_CONTEXT=4 # only change this if you have a Personal AutoDNS Account

#################
##
## Additional configuration if you want to use
## the SSLManager API
##
#################
DOMAINROBOT_SSL_USER= # SSLManager User if available
DOMAINROBOT_SSL_PASSWORD= # SSLManager Password if available
DOMAINROBOT_SSL_CONTEXT=9 # only change this if you have a Personal SSLManager Account
```

**NOTE**: To use SSL Certificate related tasks like creating SSL Contacts and SSL Certificates you need the InterNetX SSL Manager and an according API User


#### Starting the Laravel PHP Server

In the Root Directory of the Laravel Project execute the following Command to start Serving the Program

> php artisan serve --port=8181

#### Calling/Testing Routes

With a REST API Client (e.g. https://insomnia.rest/) you can now query different Tasks / Routes of the Example Implementation of the InterNetX PHP Domainrobot SDK

> GET /api/user/{username}/{context}

### Available Routes

> php artisan route:list