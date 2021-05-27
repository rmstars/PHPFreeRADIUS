<?php

/*!
 * \author	charles.foichat@gmail.com
 */

class radiusAttribute {

  protected $_id = null;
  protected $_value = null;
  protected $_attribute_definition = null;

  public function __toString() {
    return $this->getName(). ' = "'.$this->getValue().'"';
  }

  public function getId() {
    return $this->_id;
  }

  public function setId($id) {
    $this->_id = $id;
  }

  public function setAttributeDefinition($attribute_definition) {
    $this->_attribute_definition = $attribute_definition;
  }

  public function getType() {
    return $this->_attribute_definition['type'];
  }

  public function getName() {
    $ret = '';
    if (is_a($this, 'radiusVendorSpecificAttribute'))
      if (($att_info = radiusDictionnaryLookup::getVendorSpecificAttributeInformation($this->_vendor_id, $this->_id)) === FALSE)
	$ret = 'UnknownName (VendorId : '.$this->_vendor_id.', AttributeId : '.$this->_id.')';
      else
	$ret = $att_info['name'];
    else
      if (($att_info = radiusDictionnaryLookup::getAttributeInformation($this->_id)) === FALSE)
	$ret = 'UnknownName (AttributeId : '.$this->_id.')';
      else
	$ret = $att_info['name'];
    return $ret;
  }

  public function getValue() {
    return $this->_value;
  }

  public function setValue($value) {
    $this->_value = $value;
  }

  public static function allocateFromId($id, $value, $extended_informations = NULL) {
    if (($att_info = radiusDictionnaryLookup::getAttributeInformation($id)) === FALSE)
      $att_info = radiusDictionnaryLookup::getDefaultAttributeInformation();
    $att = new radiusAttribute();
    $att->_attribute_definition = $att_info;
    $att->_value = $value;
    $att->_id = $id;
    return $att;
  }

  public static function allocateFromName($name, $value) {
    $att = null;
    if (($att_info = radiusDictionnaryLookup::getAttributeInformationFromName($name)) !== FALSE) {
      if (isset($att_info['vendor_id'])) {
	$att = new radiusVendorSpecificAttribute();
	$att->_vendor_id = $att_info['vendor_id'];
      } else
	$att = new radiusAttribute();
      $att->_attribute_definition = $att_info;
      $att->_value = $value;
      $att->_id = $att_info['id'];
    }
    return $att;
  }

}

class radiusVendorSpecificAttribute extends radiusAttribute {

  protected $_vendor_id = null;

  public function getVendorId() {
    return $this->_vendor_id;
  }

  public function setVendorId($id) {
    $this->_vendor_id = $id;
  }

  public static function allocateFromId($id, $value, $extended_informations = NULL) {
    $vendor_id = (is_array($extended_informations) && isset($extended_informations["vendor_id"])) ? $extended_informations["vendor_id"] : NULL;
    if (($att_info = radiusDictionnaryLookup::getVendorSpecificAttributeInformation($vendor_id, $id)) === FALSE)
      $att_info = radiusDictionnaryLookup::getDefaultAttributeInformation();
    $att = new radiusVendorSpecificAttribute();
    $att->_id = $id;
    $att->_vendor_id = $vendor_id;
    $att->_attribute_definition = $att_info;
    $att->_value = $value;
    return $att;
  }

}

class radiusAttributeList implements iterator {

  private $_position = 0;
  protected $_attributes = array();

  public function rewind() {
    $this->_position = 0;
  }

  function current() {
    return $this->_attributes[$this->_position];
  }

  function key() {
    return $this->_position;
  }

  function next() {
    ++$this->_position;
  }

  function valid() {
    return isset($this->_attributes[$this->_position]);
  }

  public function __toString() {
    $ret = '';
    foreach ($this->_attributes as $attribute)
      $ret .= "\t".$attribute."\n";
    return $ret;
  }

  public function get() {
    return $this->_attributes;
  }

  public function getElement($attribute_id) {
    if (isset($this->_attributes[$attribute_id]))
      return $this->_attributes[$attribute_id];
    return null;
  }

  public function add($attribute) {
    if ($attribute === NULL)
      return false;
    $this->_attributes[] = $attribute;
    return count($this->_attributes);
  }

  public function remove($attribute_id) {
    unset($this->_attributes[$attribute_id]);
  }

  public function update($attribute_id, $attribute) {
    $this->_attributes[$attribute_id] = $attribute;
  }

}

class radiusPacket {

  protected $_code = '';
  protected $_authenticator = '';
  protected $_attributes = '';

  public function __construct() {
    $this->_attributes = new radiusAttributeList();
  }

  public function &attributes() {
    return $this->_attributes;
  }

  public function setCode($code) {
    $this->_code = $code;
  }

  public function getCode() {
    return $this->_code;
  }

  public function setAuthenticator($authenticator) {
    $this->_authenticator = $authenticator;
  }

  public function getAuthenticator() {
    return $this->_authenticator;
  }

}

class radiusRequest extends radiusPacket {

  public function __construct($request_code, $attribute_list = NULL) {
    if ($attribute_list !== NULL && is_array($attribute_list))
      $this->_attributes = $attribute_list;
    else
      $this->_attributes = new radiusAttributeList();
    $this->_code = $request_code;
  }

  public function getName() {
    $commands = radiusDictionnaryLookup::getAttributeInformationFromName(radiusDictionnaryLookup::COMMAND_NAME_IN_FREERADIUS_DICTIONNARY);
    if (isset($commands['values'][$this->_code]))
      return $commands['values'][$this->_code];
    return '';
  }

}

class radiusResponse extends radiusPacket {
  
  protected $_id = '';
  protected $_raw_response = '';
  protected $_socket_error_code = null;        // Last error code
  protected $_authenticator_valid = false;        // Last error code

  public function getReceivedData() {
    return $this->_raw_response;
  }

  public function setReceivedData($data) {
    $this->_raw_response = $data;
  }

  public function setIdentifier($id) {
    $this->_id = $id;
  }

  public function getIdentifier() {
    return $this->_id;
  }

  public function setAuthenticatorValid($value) {
    $this->_authenticator_valid = $value;
  }

  public function isAuthenticatorValid() {
    return $this->_authenticator_valid;
  }

  public function setErrorCode($error_code) {
    $this->_error_code = $error_code;
  }

  public function getErrorCode() {
    return $this->_error_code;
  }

  public function getError() {
    return socket_strerror($this->_error_code);
  }

  public function getName() {
    $responses = radiusDictionnaryLookup::getAttributeInformationFromName(radiusDictionnaryLookup::RESPONSE_NAME_IN_FREERADIUS_DICTIONNARY);
    if (isset($responses['values'][$this->_code]))
      return $responses['values'][$this->_code];
    return '';
  }

}

class radiusDictionnaryLookup {

  static $_internal_attr = array();
  static $_vendors = array();
  static $_all_attr = array();
  static $_cache_directory = '../dict';
  const COMMAND_NAME_IN_FREERADIUS_DICTIONNARY = 'Packet-Type';
  const RESPONSE_NAME_IN_FREERADIUS_DICTIONNARY = 'Response-Packet-Type';

  public static function setCacheDirectory($directory) {
    if (is_dir($directory))
      self::$_cache_directory = $directory;
  }

  protected static function _loadInternal() {
    if (@include(self::$_cache_directory.DIRECTORY_SEPARATOR.'internal.inc.php')) {
      self::$_internal_attr = $attributes;
      unset($attributes);
      return true;
    } else {
      if (@include(self::$_cache_directory.DIRECTORY_SEPARATOR.'minimum_internal.inc.php')) {
	self::$_internal_attr = $attributes;
	unset($attributes);
      }
    }      
    return false;
  }

  public static function _loadVendor($vendor_id) {
    if ((!isset(self::$_vendors[$vendor_id]) || (self::$_vendors[$vendor_id] !== -1)) && @include(self::$_cache_directory.DIRECTORY_SEPARATOR.$vendor_id.'.inc.php')) {
      self::$_vendors[$vendor_id] = $vendor_definition;
      unset($vendor_definition);
      return true;
    }
    self::$_vendors[$vendor_id] = -1;
    return false;
  }

  protected static function _loadAttributesList($key) {
    if (@include(self::$_cache_directory.DIRECTORY_SEPARATOR.'all_attr_'.$key.'.inc.php')) {
      self::$_all_attr[$key] = $attributes;
      unset($attributes);
      return true;
    }
    self::$_all_attr[$key] = '';;
    return false;
  }

  public static function getAttributeInformation($id) {
    if (self::$_internal_attr == array())
      self::_loadInternal();
    if (isset(self::$_internal_attr[$id]))
      return self::$_internal_attr[$id];
    return false;
  }

  public static function getDefaultAttributeInformation() {
    return array('name' => 'Unkown', 'type' => 'string');
  }

  public static function getAttributeInformationFromName($name) {
    $key = substr(md5($name), 0, 2);
    if (!isset(self::$_all_attr[$key]))
      self::_loadAttributesList($key);
    if (self::$_all_attr[$key] == '')
      return false;
    if (isset(self::$_all_attr[$key][$name]))
      return self::$_all_attr[$key][$name];
    return false;
  }

  public static function getVendorSpecificAttributeInformation($vendor_id, $id) {
    if (!isset(self::$_vendors[$vendor_id]))
      self::_loadVendor($vendor_id);
    if (self::$_vendors[$vendor_id] === -1)
      return false;
    if (isset(self::$_vendors[$vendor_id]['attributes'][$id]))
      return self::$_vendors[$vendor_id]['attributes'][$id];
    return false;
  }

  public static function getCommandCodeFromName($name) {
    if (($commands = self::getAttributeInformationFromName(self::COMMAND_NAME_IN_FREERADIUS_DICTIONNARY)) === FALSE)
      return 0;
    foreach ($commands['values'] as $code => $command)
      if ($command == $name)
	return $code;
    return 0;
  }

}

class radiusDecoder {

  protected static function _decodeAttribute($id, $value, $vendor_id = null) {
    if ($id == 26) {
      $pvendor_id = unpack('N', chr(0).substr($value, 1,3));
      $vendor_id = $pvendor_id[1];
      $id = ord(substr($value, 4, 1));
      $length = ord(substr($value, 5, 1));
      return self::_decodeAttribute($id, substr($value, 6, $length-2), $vendor_id);
    }
    if ($vendor_id !== null) {
      $att = new radiusVendorSpecificAttribute();
      if (($att_info = radiusDictionnaryLookup::getVendorSpecificAttributeInformation($vendor_id, $id)) === FALSE)
	$att_info = radiusDictionnaryLookup::getDefaultAttributeInformation();
      $att->setVendorId($vendor_id);
    }
    else {
      $att = new radiusAttribute();
      if (($att_info = radiusDictionnaryLookup::getAttributeInformation($id)) === FALSE)
	$att_info = radiusDictionnaryLookup::getDefaultAttributeInformation();
    }
    $att->setId($id);
    $att->setAttributeDefinition($att_info);
    switch ($att_info['type']) {
    case 'ipaddr':
      $decoded_value = ord(substr($value, 0, 1)).'.'.ord(substr($value, 1, 1)).'.'.ord(substr($value, 2, 1)).'.'.ord(substr($value, 3, 1));
      break;
    case 'integer':
      $decoded_value = (ord(substr($value, 0, 1))*256*256*256)+(ord(substr($value, 1, 1))*256*256)+(ord(substr($value, 2, 1))*256)+ord(substr($value, 3, 1));
      break;
    case 'octets':
    case 'date':
    case 'ipv6addr':
    case 'ifid':
    case 'ipv6prefix':
    case 'short':
    case 'abinary':
    case 'byte':
    case 'ether':
    case 'tlv':
    case 'signed':
    case 'combo-ip':
    case 'string':
    default:
      $decoded_value = $value;
      break;
    }
    $att->setValue($decoded_value);
    return $att;
  }

  public static function response($response, $shared_secret, $req_authenticator) {
    $resp = new radiusResponse();
    $resp->setReceivedData($response);
    $resp->setCode(ord(substr($response, 0, 1)));
    $resp->setIdentifier(ord(substr($response, 1, 1)));
    $length = unpack('n', substr($response, 2,2));
    $length = $length[1];
    $resp->setAuthenticator(substr($response, 4, 16));
    $attributes = substr($response, 20, ($length - 20));
    $expected_authenticator = md5(substr($response, 0, 4).$req_authenticator.$attributes.$shared_secret, true);
    if ($expected_authenticator == $resp->getAuthenticator())
      $resp->setAuthenticatorValid(TRUE);
    while (strlen($attributes) > 0) {
      $type = ord(substr($attributes,0,1));
      $length = ord(substr($attributes,1,1));
      $raw_value = substr($attributes,2,$length-2);
      $attributes = substr($attributes, $length);
      $attr = self::_decodeAttribute($type, $raw_value);
      $resp->attributes()->add($attr);
    }
    return $resp;
  }
}

class radiusEncoder {

  protected static function _encodeAttribute($id, $type, $value) {
    switch ($type) {
    case 'ipaddr':
      $ip_array = explode(".", $value);
      $encoded_value = chr($ip_array[0]).chr($ip_array[1]).chr($ip_array[2]).chr($ip_array[3]);
      break;
    case 'integer':
      $encoded_value = chr(($value/(256*256*256))%256).chr(($value/(256*256))%256).chr(($value/(256))%256).chr($value%256);
      break;
    case 'octets':
    case 'date':
    case 'ipv6addr':
    case 'ifid':
    case 'ipv6prefix':
    case 'short':
    case 'abinary':
    case 'byte':
    case 'ether':
    case 'tlv':
    case 'signed':
    case 'combo-ip':
    case 'string':
    default:
      $encoded_value = $value;
      break;
    }
    return chr($id).chr(2+strlen($encoded_value)).$encoded_value;
  }

  protected static function attribute($attribute) {
    if (is_a($attribute, 'radiusVendorSpecificAttribute')) {
      $encoded_attr = self::_encodeAttribute($attribute->getId(), 
					     $attribute->getType(),
					     $attribute->getValue());
      $b_vendor_id = pack('N', $attribute->getVendorId() & 0x00FFFFFF);
      $attribute = radiusAttribute::allocateFromId(26, $b_vendor_id.$encoded_attr);
    }
    return self::_encodeAttribute($attribute->getId(), 
				  $attribute->getType(),
				  $attribute->getValue());
  }

  public static function request(radiusRequest &$request, $shared_secret, $next_request_id) {
    if ($request->getCode() == 1) {
      if (!$request->getAuthenticator())
	$request->setAuthenticator(self::getRandomBinaryVector(16));
      $authenticator_as_md5 = FALSE;
    } else
      $authenticator_as_md5 = TRUE;

    $attributes = $request->attributes();
    $packet = '';
    $packet_attributes = '';
    foreach ($attributes as $attr)
      if (!$authenticator_as_md5 && !is_a($attr, 'radiusVendorSpecificAttribute') && ($attr->getId() == 2))
	$packet_attributes .= self::_encodeAttribute(2, 'string', self::encodePassword($attr->getValue(), $shared_secret, $request->getAuthenticator()));
      else
	$packet_attributes .= self::attribute($attr);
    $packet_len =  20 + strlen($packet_attributes); // Radius packet code + Identifier + Length high + Length low + Request-Authenticator + attributes
    $packet = chr($request->getCode());
    $packet .= chr($next_request_id);
    $packet .= chr(intval($packet_len/256));
    $packet .= chr(intval($packet_len%256));
    if ($authenticator_as_md5) {
      // 16 chr(0) used to pad packet to sign it
      $null_authenticator = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);
      $request_md5 = md5($packet.$null_authenticator.$packet_attributes.$shared_secret, TRUE);
      $request->setAuthenticator($request_md5);
      $packet .= $request_md5;
    } else
      $packet .= $request->getAuthenticator();
    $packet .= $packet_attributes;
    return $packet;
  }

  public static function getRandomBinaryVector($len) {
    $vector = '';
    for ($ra_loop = 0; $ra_loop < $len; $ra_loop++)
      $vector .= chr(rand(1, 255));
    return $vector;
  }

  public static function encodePassword($password, $shared_secret, $initialisation_vector) {
    $encrypted_password = '';
    $padded_password = $password;        
    if (0 != (strlen($password)%16))
      $padded_password .= str_repeat(chr(0),(16-strlen($password)%16));
    $previous_result = $initialisation_vector;
    for ($full_loop = 0; $full_loop < (strlen($padded_password)/16); $full_loop++) {
      $xor_value = md5($shared_secret.$previous_result);
      $previous_result = '';
      for ($xor_loop = 0; $xor_loop <= 15; $xor_loop++) {
	$value1 = ord(substr($padded_password, ($full_loop * 16) + $xor_loop, 1));
	$value2 = hexdec(substr($xor_value, 2*$xor_loop, 2));
	$xor_result = $value1 ^ $value2;
	$previous_result .= chr($xor_result);
      }
      $encrypted_password .= $previous_result;
    }
    return $encrypted_password;
  }

}

class radiusClient
{
    var $_ip_server;       // Radius server IP address
    var $_shared_secret;   // Shared secret with the radius server
    var $_port;            // port
    var $_udp_timeout;     // Timeout of the UDP connection in seconds (default value is 5)
    var $_identifier_to_send;     // Identifier field for the packet to be sent
    
    /*********************************************************************
     *
     * Name: Radius
     * short description: Radius class constructor
     *
     * @param string ip address of the radius server, can be of form ip:port otherwise use setPort
     * @param string shared secret with the radius server
     * @param integer UDP timeout (default is 5)
     * @param integer port port to use for request
     * @return NULL
     *********************************************************************/
    public function __construct($ip_server = '127.0.0.1', $shared_secret = '', $udp_timeout = 5) {
        $this->_identifier_to_send = 0;
        $this->setServerIp($ip_server);
        $this->setSharedSecret($shared_secret);
        $this->SetUdpTimeout($udp_timeout);
    }
    
    function setServerIp($ip_server) {
      $ip_c = explode(':', $ip_server);
      if (isset($ip_c[1]))
	      $this->setPort($ip_c[1]);
      else
        $this->setPort('1812');
      $this->_ip_server = gethostbyname($ip_c[0]);
    }
    
    function setSharedSecret($shared_secret) {
        $this->_shared_secret = $shared_secret;
    }
    
    function setUdpTimeout($udp_timeout = 5) {
      if (intval($udp_timeout) > 0)
	$this->_udp_timeout = intval($udp_timeout);
    }
    
    function setPort($value) {
      if (intval($value) > 0)
        $this->_port = $value;
    }

    function getNextIdentifier() {
      $this->_identifier_to_send = (($this->_identifier_to_send + 1) % 256);
      return $this->_identifier_to_send;
    }

    protected function _rawRequest($raw_request) {
      if (($_socket_to_server = socket_create(AF_INET, SOCK_DGRAM, 17)) === FALSE)
	return socket_last_error();
      if (FALSE === socket_connect($_socket_to_server, $this->_ip_server, $this->_port))
	return socket_last_error();
      if (FALSE === socket_write($_socket_to_server, $raw_request, strlen($raw_request)))
	return socket_last_error();
      $received_packet = '';
      $read_socket_array   = array($_socket_to_server);
      $write_socket_array  = NULL;
      $except_socket_array = NULL;
      if (!(FALSE === socket_select($read_socket_array, $write_socket_array, $except_socket_array, $this->_udp_timeout))) {
	if (in_array($_socket_to_server, $read_socket_array)) {
	  if (FALSE === ($received_packet = @socket_read($_socket_to_server, 1024))) {  // @ used, than no error is displayed if the connection is closed by the remote host
	    // can't read
	    $received_packet = '';
	    return socket_last_error();
	  }
	  else
	    socket_close($_socket_to_server);
	}
      }
      else {
	// can't select
	socket_close($_socket_to_server);
      }
      return $received_packet;
    }

    public function processRequest(radiusRequest $request, $request_identifier = null) {
      $response = new radiusResponse();
      if ($request_identifier !== null)
	$this->_identifier_to_send = $request_identifier;
      $packet_to_send = radiusEncoder::request($request, $this->_shared_secret, $this->_identifier_to_send);
      $received_packet = $this->_rawRequest($packet_to_send);
      if (is_int($received_packet))
	$response->setErrorCode($received_packet);
      elseif ($received_packet)
	$response = radiusDecoder::response($received_packet, $this->_shared_secret, $request->getAuthenticator());
      return $response;
    }

}

?>
