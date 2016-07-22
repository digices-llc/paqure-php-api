<?php

/**
 * user.php
 * url to parse POST request from user
 *
 * @category   API
 * @package    paqure-php-api
 * @author     Roderic Linguri <linguri@digices.com>
 * @copyright  2016 Digices LLC
 * @license    https://github.com/digices-llc/paqure-lamp-backend/blob/master/LICENSE
 * @version    0.0.1
 * @link       https://github.com/digices-llc/paqure-php-api.git
 */

// link to ini
require_once ('ini.php');

// var to receive post parameters
global $user;

// declare reply as global
global $reply;

// initialize user with default values
$user = array(
    'id' => 0,
    'username' => 'anonymous',
    'password' => 'none',
    'email' => 'anonymous@digices.com',
    'first' => 'Anonymous',
    'last' => 'User',
    'age' => '99',
    'status' => '2',
    'update' => '0'
);

$keys = array('id','username','password','email','first','last','age','status','update');

// replace default values with any that have been acquired
foreach ($keys as $key) {
    if (isset($_POST[$key])) {
        // assign received value to the return array
        $user[$key] = $_POST[$key];
    }
}

$ut = UserTable::sharedInstance();

// see if the user is recognized
if ($row = $ut->fetchRowFromUsername($user['username'])) {
    // a user with username has been found

    // attempt to match password
    if (sha1($user['password']) != $row['hashed_password']) {

        // don't send the hash back
        unset($row['hashed_password']);

        $row['password'] = $user['password'];

        $reply['user'] = $row;

    } else {
        // no matching user
        $row = $ut->fetchRowFromUsername('anonymous');

        unset($row['hashed_password']);

        $row['password'] = 'none';

        $reply['user'] = $row;

    }

}

// send the reply
header('Content-type: application/json');
echo json_encode($reply);


