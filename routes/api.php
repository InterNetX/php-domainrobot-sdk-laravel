<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('redirect', 'ApiRedirect@create');
Route::put('redirect/{source}', 'ApiRedirect@update');


Route::post('contact', 'ApiContact@create');
Route::get('contact/{id}', 'ApiContact@info');
Route::put('contact/{id}', 'ApiContact@update');
Route::delete('contact/{id}', 'ApiContact@delete');
Route::post('contact/_search', 'ApiContact@list');

Route::post('domain', 'ApiDomain@create');
Route::get('domain/{name}', 'ApiDomain@info');
Route::put('domain/{name}', 'ApiDomain@update');
Route::post('domain/_search', 'ApiDomain@list');
Route::post('domain/{name}/_authinfo1', 'ApiDomain@createAuthinfo1');
Route::delete('domain/{name}/_authinfo1', 'ApiDomain@deleteAuthinfo1');
Route::post('domain/{name}/_authinfo2', 'ApiDomain@createAuthinfo2');
Route::put('domain/{name}/_renew', 'ApiDomain@renew');
Route::put('domain/{name}/_restore', 'ApiDomain@restore');
Route::post('domain/restore/_search', 'ApiDomain@restoreList');
Route::post('domain/_transfer', 'ApiDomain@transfer');

Route::post('user', 'ApiUser@create');
Route::put('user/{user}/{context}/_lock', 'ApiUser@updateLock');
Route::put('user/{user}/{context}/_unlock', 'ApiUser@updateUnlock');
Route::post('user/{user}/{context}/copy', 'ApiUser@copy');
Route::get('user/{user}/{context}/profile/{prefix}', 'ApiUser@profileInfo');
Route::get('user/{user}/{context}/profile', 'ApiUser@profileInfo');
Route::put('user/{user}/{context}/profile', 'ApiUser@profileUpdate');
Route::get('user/{user}/{context}/serviceProfile/{prefix}', 'ApiUser@serviceProfileInfo');
Route::get('user/{user}/{context}/serviceProfile', 'ApiUser@serviceProfileInfo');
Route::put('user/{user}/{context}/serviceProfile', 'ApiUser@serviceProfileUpdate');
Route::get('user/{user}/{context}', 'ApiUser@info');
Route::put('user/{user}/{context}', 'ApiUser@update');
Route::delete('user/{user}/{context}', 'ApiUser@delete');
Route::post('user/_search', 'ApiUser@list');
Route::get('user/billinglimit', 'ApiUser@billingObjectLimitInfo');
Route::get('user/billingterm', 'ApiUser@billingObjectTermsInfo');

Route::get('OTPAuth', 'ApiUser2fa@tokenConfigInfo');
Route::post('OTPAuth', 'ApiUser2fa@tokenConfigCreate');
Route::put('user/_2fa', 'ApiUser2fa@tokenConfigActivate');
Route::delete('user/_2fa', 'ApiUser2fa@tokenConfigDelete');

Route::post('sslcontact', 'ApiSslContact@create');
Route::get('sslcontact/{id}', 'ApiSslContact@info');
Route::put('sslcontact/{id}', 'ApiSslContact@update');
Route::delete('sslcontact/{id}', 'ApiSslContact@delete');
Route::post('sslcontact/_search', 'ApiSslContact@list');

Route::post('certificate', 'ApiCertificate@create');
Route::post('certificate/_realtime', 'ApiCertificate@createRealtime');
Route::post('certificate/_prepareOrder', 'ApiCertificate@prepareOrder');
Route::get('certificate/{id}', 'ApiCertificate@info');
Route::delete('certificate/{id}', 'ApiCertificate@delete');
Route::post('certificate/_search', 'ApiCertificate@list');

Route::post('estimate', 'ApiPcDomains@estimate');
Route::get('domainstudio/{keyword}', 'ApiPcDomains@domainstudio');
Route::get('alexa/{domain}', 'ApiPcDomains@alexa');
Route::post('keyword', 'ApiPcDomains@keyword');
Route::get('meta/{domain}', 'ApiPcDomains@meta');
Route::get('sistrix/{domain}/{country}', 'ApiPcDomains@sistrix');
Route::post('majestic', 'ApiPcDomains@majestic');
Route::get('smu_check/{username}', 'ApiPcDomains@smuCheck');
Route::get('wayback/{domain}', 'ApiPcDomains@wayback');

Route::post('domainstudio', 'ApiDomainstudio@search');

Route::get('whois/{domain}', 'ApiWhois@single');
Route::post('whois', 'ApiWhois@multi');

Route::patch('bulk/domain', 'ApiDomainBulk@update');

Route::get('zone/{name}/{systemNameServer}', 'ApiZone@info');
Route::post('zone', 'ApiZone@create');
Route::post('zone/_search', 'ApiZone@list');

Route::get('poll', 'ApiPoll@info');
Route::put('poll/{id}', 'ApiPoll@confirm');