<?php

/**
 * app.php
 * url to parse POST request from application
 * 
 * @category   API
 * @package    paqure-php-api
 * @author     Roderic Linguri <linguri@digices.com>
 * @copyright  2016 Digices LLC
 * @license    https://github.com/digices-llc/paqure-lamp-backend/blob/master/LICENSE
 * @version    0.0.1
 * @link       https://github.com/digices-llc/paqure-php-api.git
 */

/**
  * Client sends app parameters to this url to 
  * (a) register application launch, and
  * (b) to receive update notifications
  */

// link to ini
require_once ('ini.php');

// declare app as global
global $app;

// declare reply as global
global $reply;

// initialize app with default values
$app = array(
  'id' => 0,
  'name' => '',
  'major' => 0,
  'minor' => 0,
  'fix' => 0,
  'copyright' => 0,
  'company' => '',
  'update' => '0'
);

// keys expected to be submitted in POST
// the 'update' key is not included, as we will provide this based on logic
$keys = array('id','name','major','minor','fix','copyright','company');

// replace default values with any that have been acquired
foreach ($keys as $key) {
	if (isset($_POST[$key])) {
	    // assign received value to the return array
		$app[$key] = $_POST[$key];
	}
}

// make sure we have actually received an id
if ($app['id'] > 0) {

	// acquire table singleton
	$at = AppTable::sharedInstance();

	// attempt to retrieve record
	if ($rec = $at->fetchRowFromId($app['id'])) {

		// check version incrementally from fix to major
		if (intval($rec['fix']) > intval($app['fix'])) {
			$rec['update'] = '1';
		}
		if (intval($rec['minor']) > intval($app['minor'])) {
			$rec['update'] = '1';
		}
		if (intval($rec['major']) > intval($app['major'])) {
			$rec['update'] = '1';
		}
		
		// enforce database authority
		$app['name'] = $rec['name'];
		$app['copyright'] = $rec['copyright'];
		$app['company'] = $rec['company'];

	} else {

		// id is not in table... allow passthrough
		$app = $_POST;

	}

} else {

	// @TODO unrecognized parameters, record IP Address to possibly block

    // pretend to understand
	$app = $_POST;

}

$reply = array();

$reply['app'] = $app;

// send the reply
header('Content-type: application/json');
echo json_encode($reply);

