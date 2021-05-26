<?php

/*!
 * \author	charles.foichat@gmail.com
 * \author	Copyright : Internet Hallway (http://www.internethallway.com/)
 */
class CLIUtils {

  static $stderr_fd = null;

    public static function getopt($options, &$argv) {
	$temp_array = explode(' ', $options);
	$option_array = array();
	$saved_argv = $argv;
	foreach ($temp_array as $opt) {
	  $default_value = null;
	    $must_have_arg = FALSE;
	    if (substr($opt, -1) == ':')
		$must_have_arg = TRUE;
	    $opt = rtrim($opt, ':');
	    $values = explode('=', $opt);
	    if (isset($values[1]))
	      $default_value = $values[1];
	    $values = $values[0];
	    $values = explode('|', $values);
	    if (count($values) > 2)
		continue;
	    if (count($values) == 1 && strlen($values[0]) != 1)
		continue;
	    $key = "";
	    $alias = "";
	    if (strlen($values[0]) == 1) {
		$key = $values[0];
		$alias = isset($values[1]) ? $values[1] : $values[0];
	    } elseif (strlen($values[1]) == 1) {
		$key = $values[1];
		$alias = $values[0];
	    }
	    if ($key == "")
		continue;
	    $option_array["-".$key]["argument"] = $must_have_arg;
	    $option_array["-".$key]["key"] = $key;
	    if ($default_value !== NULL)
	      $option_array["-".$key]["default_value"] = $default_value;
	    if ($alias == "")
		continue;
	    $option_array["--".$alias]["argument"] = $must_have_arg;
	    $option_array["--".$alias]["key"] = $key;
	    if ($default_value !== NULL)
	      $option_array["--".$alias]["default_value"] = $default_value;
	}
	$result = array();
	$keys_to_search = array_keys($option_array);
	foreach($option_array as $key => $value) {
	  if (($idx = array_search($key, $argv)) === FALSE) {
	    if (isset($value['default_value']))
	      $result[$value['key']] = $value['default_value'];
	    continue;
	  }
	    if (!$value["argument"]) {
		$result[$value["key"]] = TRUE;
		unset($argv[$idx]);
		continue;
	    }
	    if(isset($argv[$idx+1]) && !in_array($argv[$idx+1], $keys_to_search)) {
		$result[$value["key"]] = $argv[$idx+1];
		unset($argv[$idx]);
		unset($argv[$idx+1]);
		continue;
	    } else
		return FALSE;
	}
	if ($result == array())
	    return FALSE;
	return $result;
    }

    public static function errOut($msg = '', $exit_code = null) {
      if ($msg != '') {
	if (!self::$stderr_fd) 
	  self::$stderr_fd = fopen('php://stderr', 'w');
	if (self::$stderr_fd)
	  fwrite(self::$stderr_fd, $msg);
	else
	  echo ($msg);
      }
      if ($exit_code !== null) {
	if (is_resource(self::$stderr_fd))
	  fclose(self::$stderr_fd);
	exit ($exit_code);
      }
    }

}
?>