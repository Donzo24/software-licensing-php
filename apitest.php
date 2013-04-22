<?php

// include the API class
require_once 'SoftwareLicenseAPI.php';
// set your APIKEY
// NOTE: you must have obtained access to the service before you receive this
$apikey = "apikey";
// create a new instance of SoftwareLicenseAPI
$api = new SoftwareLicenseAPI($apikey);

// come up with a random (likely unique) licensekey to use for these tests
$licenseKey = (string)mt_rand();
?>

<p>View the output of the tests below</p>

<textarea rows="30" cols="100">
<?php
// get all SiteLicenses
$siteLicenses = $api->getSiteLicenses();
print_r($siteLicenses);

// create a SiteLicense
$newSiteLicense = $api->getSiteLicenseTemplate();
$newSiteLicense['licenseKey'] = $licenseKey;
$newSiteLicense['renewURL'] = "http://www.danielwatrous.com/buyhere";
$siteLicense = $api->createSiteLicense($newSiteLicense);
print_r($siteLicense);

// get a single SiteLicense
$siteLicense = $api->getSiteLicense($licenseKey);
print_r($siteLicense);

// update a SiteLicense
$siteLicense['domain'] = 'danielwatrous.com';
$siteLicense = $api->updateSiteLicense($siteLicense);
print_r($siteLicense);

// delete a SiteLicense
$siteLicense = $api->deleteSiteLicense($licenseKey);
print_r($siteLicense);

?>
</textarea>
