software-licensing-php
======================

This is the official PHP library needed to work with the easy software licensing REST interface located at https://www.easysoftwarelicensing.com/

It's simple to use. Here's an example:

`
// include the API class
require_once 'SoftwareLicenseAPI.php';
// set your APIKEY
// NOTE: you must have obtained access to the service before you receive this
$apikey = "apikey";
// create a new instance of SoftwareLicenseAPI
$api = new SoftwareLicenseAPI($apikey);
// come up with a random (likely unique) licensekey to use for these tests
$licenseKey = (string)mt_rand();

// create a SiteLicense
$newSiteLicense = $api->getSiteLicenseTemplate();
$newSiteLicense['licenseKey'] = $licenseKey;
$newSiteLicense['renewURL'] = "http://www.danielwatrous.com/buyhere";
$siteLicense = $api->createSiteLicense($newSiteLicense);
print_r($siteLicense);

// get a single SiteLicense
$siteLicense = $api->getSiteLicense($licenseKey);
print_r($siteLicense);
`

Documentation
=============

Full documentation is available on the main website:

https://www.easysoftwarelicensing.com/docs/api-1-0/