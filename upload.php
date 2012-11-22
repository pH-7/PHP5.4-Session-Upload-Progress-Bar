<?php
/**
 * @category       PHP5.4 Progress Bar
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012, Pierre-Henry Soria. All Rights Reserved.
 * @license        CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * @version        1.0.0
 */

require_once 'Upload.class.php';

/**
 * If the request GET['show_transfer'] is "on", it shows $_FILES and $_SESSION
 * using the var_dump PHP function.
 * Otherwise it sends the image to the server and displays with information about the image.
 */
$sMethod = (!empty($_GET['show_transfer']) && $_GET['show_transfer'] == 'on') ? 'show' : 'addFile';
echo (new Upload)->$sMethod();
