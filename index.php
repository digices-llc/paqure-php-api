<?php

/**
 * index.php
 * web accessible api bootstrapper
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

// declare global object var
global $object;

// set object based on request
if (isset($_GET['object'])) {
    $object = $_GET['object'];
} else {
    $object = 'home';
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $object; ?></title>
    <style>
    div {
      margin-top: 18px;
      text-align: center;
      font-family: Arial;
    }
    input {
      font-size: 18px;
      font-weight: 100;
      height: 30px;
      width: 300px;
      border:1px solid #CCCCCC;
      border-radius: 4px;
    }
    .btn {
      height: 36px;
      color:#FFFFFF;
      background-color:#0E69D4;
      border:1px solid #094D9E;
      padding:4px;
    }
    .btn:hover {
      background-color: #094D9E;
    }
    </style>
  </head>
  <body>
    <?php echo file_get_contents ('views'.DS.$object.'.html'); ?>
    </div>
  </body>
</html>

