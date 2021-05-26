<?php

/**
 * This script is provided as a sample usage of class radiusClient
 * 
 * For this script to work you need to generate a dictionnary cache usable by classes in the project.
 * Dictionnary must be generated into classes/radius/dict/ or to a custom path but radclient.php
 * must be invoked with a -d argument
 * Argument handled : 
 * -p : output php code that can be used with radiusClient class
 * -d directory : path to dictionnary cache
 * -t timeout : udp timeout
 * -x : enable debug output
 * -q : disable output (override -x)
 * -f file : use external file containing attribute/value pairs if not present values are read from stdin
 * -i identifier : use identifier as request identifier 
 *
 * Dictionnary is generated using import_free_radius.php
 */
require_once(dirname(__FILE__) . '/../classes/cli.class.php');
require_once(dirname(__FILE__) . '/../classes/radius_client.class.php');

class radiusClientTextDebug extends radiusClient {

  var $_debug = false;             // Debug mode flag
  var $_quiet = false;             // quiet mode flag
    
  function setDebug($value) {
    $this->_debug = (TRUE === $value);
  }
    
  function setQuiet($value) {
    $this->_quiet = (TRUE === $value);
  }

  public function processRequest(radiusRequest $request, $request_identifier = null) {
    $response = parent::processRequest($request, $request_identifier);
    if (!$this->_quiet) {
      if ($this->_debug) {
	echo 'Sending '.$request->getName().' of id '.$this->_identifier_to_send.' to '.$this->_ip_server.' port '.$this->_port."\n";
	echo $request->attributes();
	echo 'rad_recv: '.$response->getName().' from host '.$this->_ip_server.' port '.$this->_port.', id='.$response->getIdentifier().', length='.strlen($response->getReceivedData())."\n";
      } else
	echo 'Received response ID '.$response->getIdentifier().', code '.$response->getCode().', length = '.strlen($response->getReceivedData())."\n";
      if (!$response->isAuthenticatorValid())
	echo "WARNING INVALID RESPONSE AUTHENTICATOR !!!\n";
      echo $response->attributes();
    }
    return $response;
  }

}

class radiusClientCodeOutput extends radiusClient {

  public function processRequest(radiusRequest $request, $request_identifier = null) {
    $response = parent::processRequest($request, $request_identifier);
    echo "\ncopy/paste following code into a chlid radiusClient class method to replay request\n\n";
    echo '$request = new radiusRequest('.$request->getCode().");\n";
    foreach ($request->attributes() as $attribute) {
      if (is_a($attribute, 'radiusVendorSpecificAttribute'))
	echo '$request->attributes()->add(radiusVendorSpecificAttribute::allocateFromId('.$attribute->getVendorId().', '.$attribute->getId().', \''.addslashes($attribute->getValue())."'));\n";
      else
	echo '$request->attributes()->add(radiusAttribute::allocateFromId('.$attribute->getId().', \''.addslashes($attribute->getValue())."'));\n";
    }
    echo '$response = $this->processRequest($request);'."\n";
    echo "\nresponse from radius server\n";
    echo 'rad_recv: '.$response->getName().' from host '.$this->_ip_server.' port '.$this->_port.', id='.$response->getIdentifier().', length='.strlen($response->getReceivedData())."\n";
    if (!$response->isAuthenticatorValid())
      echo "WARNING INVALID RESPONSE AUTHENTICATOR !!!\n";
    echo $response->attributes();
    return $response;
  }

}

define('USAGE', $argv[0].' server {acct|auth|status|disconnect|auto} secret');

$opt = CLIUtils::getopt('d: c: f: F h i: n: p: q r: s S: t: v x p', $argv);
if (count($argv) < 4) {
  echo USAGE;
  exit (1);
}
$secret = array_pop($argv);
$command = array_pop($argv);
$server = array_pop($argv);
if (isset($opt['p']))
  $radc = new radiusClientCodeOutput($server, $secret);
else {
  $radc = new radiusClientTextDebug($server, $secret);
  if (isset($opt['x']))
    $radc->setDebug(TRUE);
  if (isset($opt['q']))
    $radc->setQuiet(TRUE);
}
if (isset($opt['t']))
  $radc->setUdpTimeout($opt['t']);
$request_identifier = (isset($opt['i'])) ? $opt['i'] : null;
if (isset($opt['d']))
  radiusDictionnaryLookup::setCacheDirectory($opt['d']);
else
  radiusDictionnaryLookup::setCacheDirectory(dirname(__FILE__).'/../dict');

if (isset($opt['f'])) {
  if (($fd = fopen($opt['f'], 'r')) === FALSE) {
    echo 'can\'t open command file : '.$opt['f']."\n";
    exit (2);
  }
} else
  $fd = fopen('php://stdin', 'r');
$req = new radiusRequest(radiusDictionnaryLookup::getCommandCodeFromName($command));
while ($line = fgets($fd, 4096)) {
  preg_match('/\s*(?P<name>[^=,\s]+)\s*=\s*(?P<value>(?:"((?:[^"]|"")*)"|([^,"]*)))\s*,?/', $line, $matches);
  $req->attributes()->add(radiusAttribute::allocateFromName(trim($matches['name']), trim($matches['value'], '"')));
}
$ret = $radc->processRequest($req, $request_identifier);

exit (0);

?>