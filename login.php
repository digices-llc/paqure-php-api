<?php

/**
 * login.php
 * url to parse POST request from device login
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

global $message;
global $status;

$message = 'none';
$source = '1';
$status = '2';

if (isset($_POST['submit'])) {

    $source = '1';

    unset($_POST['submit']);

}

// logic to detect valid login
if (isset($_POST['username'])) {

    if (strlen($_POST['username']) > 0) {

        if (isset($_POST['password'])) {

            if (strlen($_POST['password']) > 0) {
                // save parameters
                $username = $_POST['username'];
                $hashed_password = sha1($_POST['password']);
                // unset POST
                unset($_POST['username']);
                unset($_POST['password']);
                // get user table
                $ut = UserTable::sharedInstance();
                // query for username
                if ($row = $ut->fetchRowFromName($username)) {
                    if ($hashed_password == $row['hashed_password']) {
                        // username found
                        $message = 'Success';
                        // get status of user
                        $status = $row['status'];
                    } else {
                        $message = 'Password Incorrect';
                    }
                } else {
                    $message = 'Username not recognized';
                }
            } else {
                $message = 'Password is required';
            }
        } else {
            $message = 'Missing Parameter';
        }
    } else {
        $message = 'Username is required';
    }
} else {
    $message = 'Missing Parameter';
}


$return['message'] = $message;
$return['status'] = $status;

header('Content-type: application/json');
echo json_encode($return);
