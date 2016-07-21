<?php

/**
 * device.php
 * url to parse POST request from devicelication
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
 * Client sends device parameters to this url to
 * (a) register devicelication launch, and
 * (b) to receive update notifications
 */

// link to ini
require_once ('ini.php');

// declare device as global
global $device;

// declare reply as global
global $reply;

// initialize device with default values
$device = array(
    'id' => 0,
    'identifier' => '',
    'locale' => 'en_US',
    'token' => '4c9184f37cff01bcdc32dc486ec36961',
    'created' => strval(date('U')),
    'modified' => strval(date('U')),
    'status' => '2',
    'update' => '0'
);

$keys = array('id','identifier','locale','token','created','modified','status','update');

// replace default values with any that have been acquired
foreach ($keys as $key) {
    if (isset($_POST[$key])) {
        // assign received value to the return array
        $device[$key] = $_POST[$key];
    }
}

// make sure we have actually received an id
if ($device['id'] > 0) {

    // acquire table singleton
    $dt = DeviceTable::sharedInstance();

// see if the device is recognized
    if ($row = $dt->fetchRowFromIdentifier($identifier)) {
        // a device with identifier has been found

        // label and locale are the only device changeable values
        if ($label != $row['label'] || $locale != $row['locale']) {
            $dt->updateDevice($id,$label,$locale,$token);
            $row = $dt->fetchRowFromIdentifier($identifier);
        }

        $reply['device'] = $row;

    } else {
        // no device, create the record
        $reply['device'] = $dt->newDevice($identifier,$locale);

    }

} else {

    // pretend to understand
    $device = $_POST;

}

$reply = array();

$reply['device'] = $device;

// send the reply
header('Content-type: application/json');
echo json_encode($reply);

