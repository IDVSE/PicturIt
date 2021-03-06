<?php 
session_start();

function unique_id() {
    return md5(uniqid(mt_rand(), true));
}

$token = unique_id();
$_SESSION['token'] = $token;

$title="Upload"; require_once("header.php"); ?>
<script type="text/javascript">
window.sessionToken = "<?= $token ?>";
</script>
<img class='loading' src='img/loading.gif' />
<img class='loading' src='img/loading2.gif' />

<noscript class='loading'>
Whoops! It appears you are using a browser that doesn't support JavaScript. 
This site needs JavaScript to function properly. 
If possible, enable JavaScript or upgrade to a newer browser, then reload this site.
</noscript>

<div id="error_no_apis">
	Whoops! It seems your browser doesn't support some features that we need to upload pictures!<br/>
	Make sure to upgrade to the latest version of your browser, or switch to a modern browser like Mozilla Firefox, Google Chrome, or Opera
</div>

<div id="upload_overlay">
	<div class='center'>
		<progress id="upload_progress"></progress>
		<p class='upload_info'></p>
	</div>
</div>

<div id="uploader" class='loading-hidden'>
	<div id="preview-div" class="clearfix">
		<br/><br/><br/><br/><br/><br/><br/><br/>
	</div>
	<form id="image_upload" enctype="multipart/form-data" action="upload.php" method="POST" class='loading-hidden button'>
		<p class="text">Select Images</p>
		<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
		<input id="userfile" name="userfile[]" type="file" title="Go ahead - it doesn't bite" multiple />
		<input type="submit" />
	</form>
	<p id="drop-info">
		Or drop images anywhere<br/>
		PNG, JPEG, GIF, and SVG images supported (more formats later)
		<br/><em>Limit of 8MB / 20 images per request</em></p>
	<div id="upload_button" class='button' title="Ready to go? Click me!"><p class='text'>Upload!</p></div>
	<pre id="json_response"></pre>
	<div id="results">
		
	</div>
</div>

<?php require_once("footer.php"); ?>