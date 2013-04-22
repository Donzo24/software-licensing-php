<?php

/*
 * This class enables you to work with the REST api provided by
 * https://www.easysoftwarelicensing.com
 */

/**
 * Description of SoftwareLicenseAPI
 *
 * @author Daniel Watrous <helpdesk@danielwatrous.com>
 */
require_once 'httpful-0.2.0.phar';

class SoftwareLicenseAPI {
    const serviceBaseURI = "https://api.easysoftwarelicensing.com/v1";
    private static $siteLicenseTemplate = array(
        "licenseKey" => "",
		"expiration" => null,
		"deleted" => null,
		"domain" => "",
		"licenseeName" => "",
		"licenseeEmail" => "",
		"renewURL" => "",
		"ipAddress" => "",
		"disposition" => "",
		"validationMethod" => "LICENSE_EXISTS_NOT_EXPIRED" //default validation method
    );
    private $apikey;
    
    public function __construct($apikey) {
        $this->apikey = $apikey;
    }
    
    public function getSiteLicenseTemplate() {
        return self::$siteLicenseTemplate;
    }
    
    public function getSiteLicenses($page = 1) {
        $siteLicenses = \Httpful\Request::get(self::serviceBaseURI.'/sitelicenses/page/'.$page)
                ->addHeader("ESL-API-Key", $this->apikey)
                ->send();
        $siteLicensesArray = json_decode($siteLicenses, true);
        return $siteLicensesArray;
    }
    
    public function getSiteMultipleLicenses($licenseKeys) {
        $siteLicenses = \Httpful\Request::post(self::serviceBaseURI.'/sitelicenses/multiple')
                ->addHeader("ESL-API-Key", $this->apikey)
                ->addHeader("Content-type", "application/json")
                ->body(json_encode($licenseKeys))
                ->send();
        $siteLicensesArray = json_decode($siteLicenses, true);
        return $siteLicensesArray;
    }
    
    public function getSiteLicense($licenseKey) {
        $siteLicense = \Httpful\Request::get(self::serviceBaseURI.'/sitelicenses/'.$licenseKey)
                ->addHeader("ESL-API-Key", $this->apikey)
                ->send();
        $siteLicenseArray = json_decode($siteLicense, true);
        return $siteLicenseArray;
    }
    
    public function updateSiteLicense($siteLicense) {
        $updatedSiteLicense = \Httpful\Request::put(self::serviceBaseURI.'/sitelicenses/'.$siteLicense['licenseKey'])
                ->addHeader("ESL-API-Key", $this->apikey)
                ->addHeader("Content-type", "application/json")
                ->body(json_encode($siteLicense))
                ->send();
        $siteLicenseArray = json_decode($updatedSiteLicense, true);
        return $siteLicenseArray;
    }
    
    public function deleteSiteLicense($licenseKey) {
        $result = \Httpful\Request::delete(self::serviceBaseURI.'/sitelicenses/'.$licenseKey)
                ->addHeader("ESL-API-Key", $this->apikey)
                ->send();
        return json_decode($result, true);
    }
    
    public function createSiteLicense($siteLicense) {
        try {
            $newSiteLicenseContainer = $siteLicense;
            $newSiteLicense = \Httpful\Request::post(self::serviceBaseURI.'/sitelicenses')
                    ->addHeader("ESL-API-Key", $this->apikey)
                    ->addHeader("Content-type", "application/json")
                    ->body(json_encode($newSiteLicenseContainer))
                    ->send();
            $siteLicenseArray = json_decode($newSiteLicense, true);
            return $siteLicenseArray;
        } catch (Exception $e) { //some other exception
			$response = array (
			'message' => "<span class='error'> <br>Caught exception when creating a SiteLicense : " .  $e->getMessage() . "<br><span class='error'> ",
			'status' => 'error'
				);
			return json_encode($response);
            
        }
    }
}

?>
