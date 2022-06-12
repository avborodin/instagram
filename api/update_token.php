<?php
require '../src/InstagramBasicDisplay.php';
use Themeart\InstagramBasicDisplay\InstagramBasicDisplay;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ig_token'])) {
	$token = $_GET['ig_token'];
	$instagram = new InstagramBasicDisplay($token);
	$token = $instagram->refreshToken($token, true);
	echo json_encode(array(
		"ig_token"=>$token,
		"ig_time_expire_token" => time()+(60*24*60*60);
	));
	exit();
}