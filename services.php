<?php
date_default_timezone_set('America/Toronto');
include 'api_utils.php';
session_start();

$cmd = $_GET['cmd'];

switch ($cmd) {
	case 'listrepos':
		include('icl/listrepos.php');
		listrepos();
		break;
	default:
		die('invalid cmd: ' . $cmd);
		break;
}