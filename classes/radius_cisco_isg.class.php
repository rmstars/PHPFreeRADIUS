<?php

/*!
 * \author	charles.foichat@gmail.com
 */

class radiusCiscoISG extends radiusClient {

  const	VENDOR_ID = 9;

  /**
   * @~english
   * @param $user : login to pass
   * @param $encoded_password : encoded password according to cisco documentation
   * @param $cisco_account_identifier : correspond to ssg-account-info attribute in cisco documentation
   * @return a radiusResponse object
   */
  public function CoASessionQuery($user, $encoded_password, $cisco_account_identifier) {
    $req = new radiusRequest(43);
    /*  those three lines are equivalent to the 3 lines not commented below */
    /*
      $req->attributes()->add(radiusAttribute::allocateFromName('User-Password', $encoded_password));
      $req->attributes()->add(radiusAttribute::allocateFromName('Cisco-Account-Info', $cisco_account_identifier));
      $req->attributes()->add(radiusAttribute::allocateFromName('Cisco-Command-Code', '4 &'));
    */
    $req->attributes()->add(radiusAttribute::allocateFromId(2, $encoded_password));
    $req->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(self::VENDOR_ID, 
							  250, $cisco_account_identifier));
    $req->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(self::VENDOR_ID, 
							    252, '4 &'));
    $ret = $this->processRequest($req);
    return $ret;
  }

  function encodePassword($password = '', $initialisation_vector = NULL) {
    if ($initialisation_vector === NULL)
      $initialisation_vector = radiusEncoder::getRandomBinaryVector(16);
    $encoded_password = radiusEncoder::encodePassword(pack('C', strlen($password)).$password, $this->_shared_secret, $initialisation_vector);
    return $initialisation_vector.$encoded_password;
  }

  function logon($username, $password, $ip) {
    $request = new radiusRequest(43);
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 252, '1'));
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 1, 'subscriber:command=account-logon'));
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 250, $ip));
    $request->attributes()->add(radiusAttribute::allocateFromId(1, $username));
    $response = $this->processRequest($request);
    return $response;
  }

  function logout($ip) {
    $request = new radiusRequest(43);
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 252, '2'));
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 1, 'subscriber:command=account-logoff'));
    $request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId(9, 250, $ip));
    $response = $this->processRequest($request);
    return $response;
  }

}

?>