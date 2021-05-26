<?php

/*
  base types :
    [0] => string
    [1] => ipaddr
    [2] => integer
    [3] => octets
    [4] => date
    [5] => ipv6addr
    [6] => ifid
    [7] => ipv6prefix
    [8] => short
    [9] => abinary
    [10] => byte
    [11] => ether
    [12] => tlv
    [13] => signed
    [14] => combo-ip
 */

class freeRadiusDictionnaryCacheGenerator {

  protected $_internal_attr;
  protected $_all_attr;
  protected $_all_value;
  protected $_all_tlv;
  protected $_internal_value;
  protected $_internal_tlv;
  protected $_dictionnary_file;
  protected $_override_only_compat;

  public function run($dictionnary_file, $destination_directory = '.', $override_only_compat = FALSE) {
    $this->_dictionnary_file = $dictionnary_file;
    $this->_override_only_compat = $override_only_compat;
    $this->_internal_attr = array();
    $this->_internal_value = array();
    $this->_internal_tlv = array();
    return $this->_genCacheFile($dictionnary_file, $destination_directory);
  }

  protected function _genCacheFile($dictionnary_file, $destination_directory) {
    if (($fd = fopen($dictionnary_file, 'r')) === FALSE)
      return 1;
    $vendors = $tlvs = $values = array();
    $in_tlv = $in_vendor = '';
    $current_format = '1,1';
    while ($line = fgets($fd, 4096)) {
      $line = trim($line);
      if (($line == '') || strpos($line, '#') === 0)
	continue;
      $line = preg_replace('/\s[\s]+/',' ',$line);
      $line = preg_replace('/[\t]+/',' ',$line);
      $tab_line = explode(" ", $line);
      switch(trim($tab_line[0])) {
      case 'VENDOR' :
	if (!isset($tab_line[2]))
	  return 7;
	if (isset($tab_line[3]))
	  $current_format = $tab_line[3];
	else
	  $current_format = '1,1';
	$vendors[$tab_line[1]] = array('id' => $tab_line[2], 'name' => $tab_line[1], 'format' => $current_format, 'attributes' => array());
	break;
      case 'BEGIN-VENDOR' :
	if ($in_vendor != '')
	  return 2;
	$in_vendor = $tab_line[1];
	break;
      case 'END-VENDOR' :
	$in_vendor = '';
	break;
      case 'BEGIN-TLV' :
	if ($in_vendor == '')
	  return 3;
	if ($in_tlv != '')
	  return 4;
	$in_tlv = $tab_line[1];
	break;
      case 'END-TLV' :
	$in_tlv = '';
	break;
      case 'ATTRIBUTE' :
	if ($in_tlv) {
	  $this->_all_tlv[$in_tlv][$tab_line[2]] = array('name' => $tab_line[1], 'type' => $tab_line[3]);
	  $tlvs[$in_tlv][$tab_line[2]] = array('name' => $tab_line[1], 'type' => $tab_line[3]);
	}
	else {
	  if (isset($this->_all_attr[$tab_line[1]]))
	    trigger_error('attribute with that name already exist : attr-id('.$tab_line[2].') attr-name('.$tab_line[1].') file ('.$dictionnary_file.")");
	  $this->_all_attr[$tab_line[1]] = array('id' => $tab_line[2], 'type' => $tab_line[3]);
	  if ($in_vendor == '') {
	    if (isset($this->_internal_attr[$tab_line[2]])) {
	      $ext_from = pathinfo($this->_internal_attr[$tab_line[2]]['from'], PATHINFO_EXTENSION);
	      if (($ext_from != 'compat') && ($this->_override_only_compat))
		trigger_error('internal attribute already defined : attr-id('.$tab_line[2].') attr-name('.$tab_line[1].') file ('.$dictionnary_file.") originaly defined in ".$this->_internal_attr[$tab_line[2]]['from']);
	      else
		$this->_internal_attr[$tab_line[2]] = array('name' => $tab_line[1], 'type' => $tab_line[3], 'from' => $dictionnary_file);
	    }
	    else
	      $this->_internal_attr[$tab_line[2]] = array('name' => $tab_line[1], 'type' => $tab_line[3], 'from' => $dictionnary_file);
	  }
	  else {
	    $this->_all_attr[$tab_line[1]]['vendor_id'] = $vendors[$in_vendor]['id'];
	    $vendors[$in_vendor]['attributes'][$tab_line[2]] = array('name' => $tab_line[1], 'type' => $tab_line[3]);
	  }
	}
	break;
      case 'VALUE' :
	$this->_all_value[$tab_line[1]][$tab_line[3]] = $tab_line[2];
	$values[$tab_line[1]][$tab_line[3]] = $tab_line[2];
	break;
      case '$INCLUDE' :
	$dir = dirname($dictionnary_file);
	if (($ret = $this->_genCacheFile($dir.'/'.$tab_line[1], $destination_directory)) != 0)
	  echo $ret.' : '.$dir.'/'.$tab_line[1]."\n";
	break;
      default:
	trigger_error('unknown tag : '.$tab_line[0].' line('.$line.')');
	break;
      }
    }
    fclose($fd);
    if ($vendors == array()) {
      $this->_internal_value = array_merge($this->_internal_value, $values);
      $this->_internal_tlv = array_merge($this->_internal_tlv, $tlvs);
    }
    foreach ($vendors as &$vendor) {
      if (!isset($vendor['id']))
	return 8;
      $id = $vendor['id'];
      unset($vendor['id']);
      file_put_contents($destination_directory.DIRECTORY_SEPARATOR.$id.'.inc.php', "<?php\n");
      $attributes = $vendor['attributes'];
      unset($vendor['attributes']);
      file_put_contents($destination_directory.DIRECTORY_SEPARATOR.$id.'.inc.php', '$vendor_definition = array(\'vendor\' => '.var_export($vendor, true).",\n", FILE_APPEND);
      if ($attributes != array()) {
	foreach ($attributes as &$attr) {
	  if (isset($values[$attr['name']])) {
	    $attr['values'] = $values[$attr['name']];
	    unset($values[$attr['name']]);
	  }
	  if (isset($tlvs[$attr['name']])) {
	    foreach ($tlvs[$attr['name']] as &$tlv) {
	      if (isset($values[$tlv['name']])) {
		$tlv['values'] = $values[$tlv['name']];
		unset($values[$tlv['name']]);
	      }	      
	    }
	    $attr['tlv'] = $tlvs[$attr['name']];
	  }
	}
	file_put_contents($destination_directory.DIRECTORY_SEPARATOR.$id.'.inc.php', '\'attributes\' => '.var_export($attributes, true).");\n", FILE_APPEND);
      }
      file_put_contents($destination_directory.DIRECTORY_SEPARATOR.$id.'.inc.php', "?>\n", FILE_APPEND);
    }
    if ($dictionnary_file == $this->_dictionnary_file) {
      foreach ($this->_internal_attr as &$attr) {
	unset($attr['from']);
	if (isset($this->_internal_value[$attr['name']])) {
	  $attr['values'] = $this->_internal_value[$attr['name']];
	  unset($this->_internal_value[$attr['name']]);
	}
	if (isset($this->_internal_tlv[$attr['name']])) {
	  foreach ($this->_internal_tlv[$attr['name']] as &$tlv) {
	    if (isset($values[$tlv['name']])) {
	      $tlv['values'] = $values[$tlv['name']];
	      unset($values[$tlv['name']]);
	    }	      
	  }
	  $attr['tlv'] = $tlvs[$attr['name']];
	}
      }
      if ($this->_internal_attr != array()) {
	file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'internal.inc.php', "<?php\n");
	file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'internal.inc.php', '$attributes = '.var_export($this->_internal_attr, true).";\n", FILE_APPEND);
	file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'internal.inc.php', "?>\n", FILE_APPEND);
      }
      // reverse cache used when using attributes by name
      foreach ($this->_all_attr as $key => &$attr) {
	if (isset($this->_all_value[$key])) {
	  $attr['values'] = $this->_all_value[$key];
	  unset($this->_all_value[$key]);
	}
	if (isset($this->_all_tlv[$key])) {
	  foreach ($this->_all_tlv[$key] as &$tlv) {
	    if (isset($this->_all_value[$tlv['name']])) {
	      $tlv['values'] = $this->_all_value[$tlv['name']];
	      unset($values[$tlv['name']]);
	    }	      
	  }
	  $attr['tlv'] = $this->_all_tlv[$key];
	}
      }
      $all_attr = array();
      if ($this->_all_attr != array()) {
	foreach ($this->_all_attr as $key => $value) {
	  $nkey = substr(md5($key), 0, 2);
	  $all_attr[$nkey][$key] = $value;
	  unset($this->_all_attr[$key]);
	}
	foreach ($all_attr as $key => &$value) {
	  file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'all_attr_'.$key.'.inc.php', "<?php\n");
	  file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'all_attr_'.$key.'.inc.php', '$attributes = '.var_export($all_attr[$key], true).";\n", FILE_APPEND);
	  file_put_contents($destination_directory.DIRECTORY_SEPARATOR.'all_attr_'.$key.'.inc.php', "?>\n", FILE_APPEND);
	}
      }
    }
    return 0;
  }

}

?>