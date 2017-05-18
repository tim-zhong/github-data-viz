<?php
include 'api_utils.php';

session_start();
if(!session('access_token')){
	header('Location: ./index.php');
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="flat-ui/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="flat-ui/dist/css/flat-ui.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<title>Github Data Visualization</title>
	</head>
	<body>
		<div id="top-global-menu">
			<div class="cwidth">
				<span id="site-title">
					Github Data Visualization
				</span>
				<a id="log-out" href="logout.php" class="btn btn-danger btn-sm"><span>Log out</span></a>
				<span id="user-info">
					<a href="<?php echo $user->html_url;?>">
						<img id="avatar" src="<?php echo $user->avatar_url; ?>">
						<span id="username"><?php echo $user->login; ?></span>
					</a>
				</span>
				<div class="clear"></div>
			</div>
		</div>
		<div class="cwidth">
			<div id="dashboard-headline">
				<h1>Welcome! <?php echo $user->name; ?>.</h1>
				<h5>Choose a repo to start with.</h5>
			</div>
			<div id="repos" class="card-columns"></div>
		</div>
	</body>
	<script src="flat-ui/dist/js/vendor/jquery.min.js"></script>

	<script src="flat-ui/dist/js/flat-ui.min.js"></script>
	<script src="js/nano.js"></script>
	<script>
		ajxpgn('repos','services.php?cmd=listrepos');
	</script>
</html>
