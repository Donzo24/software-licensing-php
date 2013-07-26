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
    
    public function __construct($apikey = null) {
        $this->apikey = $apikey;
    }
    
    public function getSiteLicenseTemplate() {
        return self::$siteLicenseTemplate;
    }
    
    public function getSiteLicenses($page = 1) {
       
		$header = array('ESL-API-Key:' . $this->apikey);
		$siteLicenses = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses/page/' . $page, $header, 'GET');
		return $siteLicenses;

    }
    
    public function getSiteMultipleLicenses($licenseKeys) {
	
		$header = array('Content-type: application/json', 'ESL-API-Key:' . $this->apikey);
		$siteLicenseArray = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses/multiple', $header, 'POST', json_encode($licenseKeys));
		return $siteLicenseArray;
		
    }
	
	public function getSiteLicense($licenseKey, $domain = null) {
	
		if ( $this->apikey != null ) 
			$header = array('ESL-API-Key:' . $this->apikey); // full sitelicense representation 
		else if ( $domain != null ) 
			$header = array('ESL-domain:' . $domain); // minimal sitelicense representation with domain validation
		else  
			$header = null; // minimal sitelicense representation 

		$siteLicenseArray = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses/' . $licenseKey, $header, 'GET');
		return $siteLicenseArray;
		
    }
    
    public function updateSiteLicense($siteLicense) {
		
		$header = array('Content-type: application/json', 'ESL-API-Key:' . $this->apikey);
		$siteLicenseArray = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses/' . $siteLicense['licenseKey'], $header, 'PUT', json_encode($siteLicense));
		return $siteLicenseArray;
		
    }
    
    public function deleteSiteLicense($licenseKey) {
		
		$header = array('ESL-API-Key:' . $this->apikey);
		$result = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses/' . $licenseKey, $header, 'DELETE');
		return $result;
		
    }
    
    public function createSiteLicense($newSiteLicenseContainer) {
	
		try {

			$header = array('Content-type: application/json', 'ESL-API-Key:' . $this->apikey);
			$siteLicenseArray = $this->cURLExecute(self::serviceBaseURI.'/sitelicenses', $header, 'POST', json_encode($newSiteLicenseContainer));
			return $siteLicenseArray;

		} catch (Exception $e) { //some other exception
		
		
			$response = array (
			'message' => "<span class='error'> <br>Caught exception when creating a SiteLicense : " .  $e->getMessage() . "<br><span class='error'> ",
			'status' => 'error'
			);
			return json_encode($response);

		}
    }
	protected function cURLExecute($url, $header, $request, $fields = null) {
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
		
		if ( $request == 'POST' || $request == 'PUT' ) { 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		if ( $header != null ) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		return json_decode($response, true);
	
	}
}

?>
