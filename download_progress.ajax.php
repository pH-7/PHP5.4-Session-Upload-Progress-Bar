<?php
/**
 * @category       PHP5.4 Progress Bar
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012, Pierre-Henry Soria. All Rights Reserved.
 * @license        CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * @version        1.0.0
 */

require_once 'Upload.class.php';

$sParameter = (!empty($_POST['param'])) ? strip_tags($_POST['param']) : '';
$oUpload = new Upload;

switch($sParameter)
{
    case 'progress':
        echo $oUpload->progress();
    break;

    case 'cancel':
        $oUpload->cancel();
    break;
}
