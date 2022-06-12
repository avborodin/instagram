<?php
require 'src/InstagramBasicDisplay.php';
require 'inc/config.php';

use Themeart\InstagramBasicDisplay\InstagramBasicDisplay;

session_start();

if(isset($_GET['redirect_uri'])){
	$_SESSION['redirect_uri'] = $_GET['redirect_uri'];
}
if(isset($_GET['jtl_token'])){
	$_SESSION['jtl_token'] = $_GET['jtl_token'];
}


$instagram = new InstagramBasicDisplay(array(
	'appId' => appId,
	'appSecret' => appSecret,
	'redirectUri' => redirectUri 
));

$ig_token = '';
$code = '';

if($_GET['code'] && $_SESSION['redirect_uri'] && $_SESSION['jtl_token']){

	$code  = $_GET['code'];
	$ig_token = $instagram->getOAuthToken($code, true);
	$ig_token = $instagram->getLongLivedToken($ig_token, true);

	$redirect_uri = $_SESSION['redirect_uri'];
	$jtl_token = $_SESSION['jtl_token'];
	$ig_time_expire_token = time()+(60*24*60*60);

	header('Location: '.$redirect_uri."&token=".$jtl_token."&ig_token=".$ig_token."&ig_time_expire_token=".$ig_time_expire_token);
	exit;
}else{
	$heading  = 'Instagram Token';
	$loginUrl = $instagram->getLoginUrl(['user_profile','user_media']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instagram</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
<section class="login p-fixed d-flex text-center">
<div class="container-fluid">
	<div class="row">
		<dic class="col-sm-12">
		<div class="card mb-4 box-shadow mx-auto my-auto" style="width: 400px;">
			<div class="card-body">
				<h4 class="card-title"><?php echo $heading;?></h4>
				<?php if(empty($code)){?>
				<div class="alert alert-primary" role="alert">
					Log into your Instagram account and then click on this button
				</div>
				<a class="btn btn-lg btn-block btn-outline-primary" href="<?php echo $loginUrl ?>">Аuthorize Instagram</a>
				<?php }else {?>
					<form class="form-inline">
						<div class="form-group mx-sm-3 mb-2">
							<input type="text" id="token" class="form-control" id="exampleInputPassword1" value="<?php echo $token;?>">
						</div>
						<button type="button" class="btn btn-primary mb-2" onclick="copyToken();">Copy token</button>
					</form>
				<?php }?>
			</div>
		</div>
		</div>
	</div>
</div>
</section>
<script src="js/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
function copyToken() {
	var copyText = document.getElementById("token");
	copyText.select();
	copyText.setSelectionRange(0, 99999); 
	navigator.clipboard.writeText(copyText.value);
}
</script>
</body>
</html>