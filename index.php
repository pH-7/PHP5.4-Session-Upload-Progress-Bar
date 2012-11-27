<?php
/**
 * @category       PHP5.4 Progress Bar
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012, Pierre-Henry Soria. All Rights Reserved.
 * @license        CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * @version        1.0.0
 */

/**
 * Check the version of PHP
 */
if (version_compare(phpversion(), '5.4.0', '<'))
    exit('ERROR: Your PHP version is ' . phpversion() . ' but this script requires PHP 5.4.0 or higher.');

/**
 * Check if "session upload progress" is enabled
 */
if (!intval(ini_get('session.upload_progress.enabled')))
    exit('session.upload_progress.enabled is not enabled, please activate it in your PHP config file to use this script.');

require_once 'Upload.class.php';
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
      <meta charset="utf-8" />
      <title>PHP 5.4 Session Upload Progress Bar Demo</title>
      <meta name="description" content="PHP 5.4 Session Upload Progress Bar" />
      <meta name="keywords" content="PHP, session, upload, progress bar" />
      <meta name="author" content="Pierre-Henry Soria" />
      <link rel="stylesheet" href="./static/css/common.css" />
  </head>

  <div id="container">

  <header>
    <h1>Example for Progress Bar with PHP 5.4 and jQuery</h1>
  </header>

  <!-- Debug Mod --> <!-- <form action="upload.php?show_transfer=on" method="post" id="upload_form" enctype="multipart/form-data" target="result_frame"> -->
  <form action="upload.php" method="post" id="upload_form" enctype="multipart/form-data" target="result_frame">
      <fieldset>
          <legend>Upload Images</legend>
          <input type="hidden" name="<?php echo ini_get('session.upload_progress.name');?>" value="<?php Upload::UPLOAD_PROGRESS_PREFIX ?>" />
          <label for="file">Images: <input type="file" name="files[]" id="file" multiple="multiple" accept="image/*" required="required" />
          <small><em>You can select multiple files at once by clicking multiple files while holding down the "CTRL" key.</em></small></label>
          <button type="submit" id="upload">Upload!</button>
          <button type="reset" id="cancel">Cancel Upload</button>

      <!-- Progress bar here -->
      <div id="upload_progress" class="hidden center progress">
          <div class="bar"></div>
      </div>

      </fieldset>
  </form>

  <iframe id="result_frame" name="result_frame" src="about:blank"></iframe>

  <footer>
    <p>By <strong><a href="http://ph-7.github.com">pH7</a></strong> &copy; 2012.</p>
  </footer>

</div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script src="./static/js/ProgressBar.class.js"></script>
  <script>
  $('#upload').click(function() {
    (new UploadBar).upload();
  });
  $('#cancel').click(function() {
    (new UploadBar).cancel();
  });
  </script>
</html>
