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

// declare reply as global
global $reply;

// initialize reply with default values
$reply = array(
  'id' => 0,
  'name' => '',
  'major' => 0,
  'minor' => 0,
  'fix' => 0,
  'copyright' => 0,
  'company' => '',
  'update' => 0
);

// keys expected to be submitted in POST
// the 'update' key is not included, as we will provide this based on logic
$keys = array('id','name','major','minor','fix','copyright','company');

// replace default values with any that have been acquired
foreach ($keys as $key) {
	if (isset($_POST[$key])) {
	    // assign received value to the return array
		$reply[$key] = $_POST[$key];
	}
}

// make sure we have actually received an id
if ($reply['id'] > 0) {

	// acquire table singleton
	$at = AppTable::sharedInstance();

	// attempt to retrieve record
	if ($rec = $at->fetchRowFromId($reply['id'])) {

		// check version incrementally from fix to major
		if (intval($rec['fix']) > intval($reply['fix'])) {
			$rec['update'] = 1;
		}
		if (intval($rec['minor']) > intval($reply['minor'])) {
			$rec['update'] = 1;
		}
		if (intval($rec['major']) > intval($reply['major'])) {
			$rec['update'] = 1;
		}
		
		// enforce database authority
		$reply['name'] = $rec['name'];
		$reply['copyright'] = $rec['copyright'];
		$reply['company'] = $rec['company'];			

	} else {

		// id is not in table... allow passthrough
		$reply['app'] = $_POST;

	}

} else {

	// @TODO unrecognized parameters, record IP Address to possibly block

    // pretend to understand
	$reply['app'] = $_POST;

}

// send the reply
header('Content-type: application/json');
echo json_encode($reply);

