<!DOCTYPE html>
<html><body>

	<iframe src="http://winningfotos.lineage.media/">
	  Your browser does not support iframes.
	</iframe>

</body>
</html>
<?php
// auth.php
// check login status at winningfotos.com and send to blog header
$profile = 'http://winningfotos.lineage.media/settings/profile';
$headers = array('Authorization: Bearer cc633659e877ad254f67cbba2871aaceb014d55b6a3d8ef452fc0c68960fa26e');

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

//echo get_data($profile);

/*<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
//$("body").load("<?php _e($profile)?>");


</script>

*/
?>