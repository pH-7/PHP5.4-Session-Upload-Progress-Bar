<?php
/**
 * @category       PHP5.4 Progress Bar
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012, Pierre-Henry Soria. All Rights Reserved.
 * @license        CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * @version        1.0.0
 */

class Upload
{

    const UPLOAD_PROGRESS_PREFIX = 'progress_bar';

    private $_sMsg, $_sUploadDir, $_sProgressKey;

    // The short array syntax (only for PHP 5.4.0 and higher)
    private $_aErrFile = [
         UPLOAD_ERR_OK         => 'There is no error, the file uploaded with success.',
         UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
         UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
         UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
         UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
         UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
         UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
         UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
    ];

    public function __construct()
    {
        // Session initialization
        if ('' === session_id()) session_start();

        $this->_sUploadDir = './uploads/';
        $this->_sProgressKey = strtolower(ini_get('session.upload_progress.prefix') . static::UPLOAD_PROGRESS_PREFIX);
        /**
        // You can also retrieve the session this way.
        $this->_sProgressKey = strtolower(ini_get('session.upload_progress.prefix') . $_POST[ini_get('session.upload_progress.name')]);
        */
    }

    /**
     * @return integer Percentage increase.
     */
    public function progress()
    {
        if(!empty($_SESSION[$this->_sProgressKey]))
        {
            $aData = $_SESSION[$this->_sProgressKey];
            $iProcessed = $aData['bytes_processed'];
            $iLength = $aData['content_length'];
            $iProgress = ceil(100*$iProcessed / $iLength);
        }
        else
        {
            $iProgress = 100;
        }

        return $iProgress;
    }

    /**
     * @return object this
     */
    public function addFile()
    {
        if(!empty($_FILES))
        {
            $this->_sMsg = '';

            foreach($_FILES as $sKey => $aFiles)
            {
                for($i = 0, $iNumFiles = count($aFiles['tmp_name']); $i < $iNumFiles; $i++)
                {
                    $iErrCode = $aFiles['error'][$i];
                    $sFileName = $aFiles['name'][$i];
                    $sTmpFile = $aFiles['tmp_name'][$i];
                    $sFileDest = $this->_sUploadDir . $aFiles['name'][$i];

                    /**
                     * Check files.
                     */
                    $bIsImgExt = (strtolower(substr(strrchr($sFileName, '.'), 1))); // Get the file extension
                    if(($bIsImgExt == 'jpeg' || $bIsImgExt == 'jpg' || $bIsImgExt == 'png' || $bIsImgExt == 'gif') && (strstr($aFiles['type'][$i], '/', true) === 'image'))
                    {
                        if($iErrCode == UPLOAD_ERR_OK)
                        {
                            move_uploaded_file($sTmpFile, $sFileDest);
                            $this->_sMsg .= '<p style="color:green; font-weight:bold; text-align:center">Successful "' . $sFileName . '" file upload!</p>';
                            $this->_sMsg .= '<p style="text-align:center">Image type: ' . str_replace('image/', '', $aFiles['type'][$i]) . '<br />';
                            $this->_sMsg .= 'Size: ' . round($aFiles['size'][$i] / 1024) . ' KB<br />';
                            $this->_sMsg .= '<a href="' . $sFileDest . '" title="Click here to see the original file" target="_blank"><img src="' . $sFileDest . '" alt="' . $sFileName  . '" width="300" height="250" style="border:1.5px solid #ccc; border-radius:5px" /></a></p>';
                        }
                        else
                        {
                            $this->_sMsg .= '<p style="color:red; font-weight:bold; text-align:center">Error while downloading the file "' . $sFileName . '"<br />';
                            $this->_sMsg .= 'Error code: "' . $iErrCode . '"<br />';
                            $this->_sMsg .= 'Error message: "' . $this->_aErrFile[$iErrCode] . '"</p>';
                        }
                    }
                    else
                    {
                        $this->_sMsg .= '<p style="color:red; font-weight:bold; text-align:center">File type incompatible. Please save the image in .jpg, .jpeg, .png or .gif</p>';
                    }
                }
            }
        }
        else
        {
            $this->_sMsg = '<p style="color:red; font-weight:bold; text-align:center">You must select at least one file before submitting the form.</p>';
        }

        return $this;
    }

    /**
     * @return object this
     */
     public function show()
     {
         ob_start();
         echo '<p><strong>$_FILES Result:</strong></p><pre>';
         var_dump($_FILES);
         echo '</pre>';
         echo '<p><strong>$_SESSION Result:</strong></p><pre>';
         var_dump($_SESSION);
         echo '</pre>';
         $this->_sMsg = ob_get_clean();

         return $this;
     }

    /**
     * Cancel the file download.
     *
     * @return object this
     */
    public function cancel()
    {
        if (!empty($_SESSION[$this->_sProgressKey]))
            $_SESSION[$this->_sProgressKey]['cancel_upload'] = true;

        return $this;
    }

    /**
     * Get the JSON informational message.
     *
     * @param integer $iStatus, 1 = success, 0 = error
     * @param string $sTxt
     * @return string JSON Format.
     */
     public static function jsonMsg($iStatus, $sTxt)
     {
         return '{"status":' . $iStatus . ',"txt":"' . $sTxt . '"}';
     }

    /**
     * Get the informational message.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_sMsg;
    }

}
