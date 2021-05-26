<?php

/**
 * dictionnary cache generation script in order for class radiusClient to work efficiently
 * every freeradius dictionnary definitions are handled by this script including $INCLUDE
 * So script can be launched with /usr/share/freeradius/dictionnary as input file
 *
 * Notice are thrown when multiple attributes are present with same id or with the same name
 * In order to avoid debug printing use  -d error_reporting='E_ALL & E_NOTICE'
 *
 * Example : php -d error_reporting='E_ALL & E_NOTICE' bin/import_free_radius.php /usr/share/freeradius/dictionary classes/radius/dict
 */
define('USAGE', $argv[0]." : dictionnary_file destination_directory\n");

if ($argc < 3) {
  echo USAGE;
  exit -1;
}

require_once(dirname(__FILE__) . '/../classes/freeradius_dictionnary_cache_generator.class.php');

$dictionnary = $argv[1];
$dest_directory = $argv[2];

$rad_cache_gen = new freeRadiusDictionnaryCacheGenerator();
if (($ret = $rad_cache_gen->run($dictionnary, $dest_directory, TRUE)) != 0)
  echo $ret.' : '.$dictionnary."\n";

?>