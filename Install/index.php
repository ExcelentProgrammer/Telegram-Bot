<?php 
if (session_status() == PHP_SESSION_NONE)
    session_start();
 ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Wizard-v3</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="assets/css/roboto-font.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
	<!-- datepicker -->
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="assets/style.css">
	<!-- Main Style Css -->
	<link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
	<?php include "./alert.php" ?>

	<?php
	$error = json_encode($_SESSION['error'] ?? null);
	$done = json_encode($_SESSION['done'] ?? null);
	if ($error != "null") {
	?>
		<script>
			showAlert({
				message: <?= $error ?>
			});
		</script>
	<?php
	} elseif ($done == "\"ok\"") {
	?>
		<script>
			showAlert({
				message: "<?= "Bot ishlashga tayyor" ?>"
			});
		</script>
	<?php } ?>





	<div class="page-content" style="background-image: url('assets/images/wizard-v3.jpg')">
		<div class="wizard-v3-content">
			<div class="wizard-form">
				<div class="wizard-header">
					<h3 class="heading">Telegram-Bot</h3>
				</div>
				<form class="form-register" id="form-submit" action="request.php" method="post">
					<div id="form-total">
						<!-- SECTION 1 -->
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-account"></i></span>
							<span class="step-text">About</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Bot Malumotlari:</h3>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" name="token" id="token" class="form-control" required>
											<span class="label">Bot Tokeni</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
						</section>
						<!-- SECTION 2 -->
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
							<span class="step-text">Malumotlar Bazasi</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Mysql Sozlamalari:</h3>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" name="username" id="username" class="form-control" required>
											<span class="label">Username</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" name="password" id="password" class="form-control" required>
											<span class="label">Parol</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" name="baza-name" id="baza-name" class="form-control" required>
											<span class="label">Baza Nomi</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" name="host" value="localhost" id="host" class="form-control" required>
											<span class="label">Host</span>
											<span class="border"></span>
										</label>
									</div>
								</div>

							</div>
						</section>
						<!-- SECTION 3 -->

					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/jquery.steps.js"></script>
	<script src="assets/js/jquery-ui.min.js"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>