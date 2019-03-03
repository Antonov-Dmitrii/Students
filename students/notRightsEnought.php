<?php
	if(isset($_GET["goToLoginPage"])) {
		session_start();
		session_destroy();
		header("Location: index.php");
		exit;
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Недостаточно прав!</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
	<main class="main mainStartPage">
		<div class="notRightsEnought">
			<p><b>У вас недостаточно прав для просмотра этой страницы. </b></p>
			<p>Вы можете вернуться на страницу входа и войти под другими учетными данными.</p>
			<div class="">
				<a class="download" href="\notRightsEnought.php?goToLoginPage=1">Страница входа</a>
			</div>
		</div>
	</main>
</body>
</html>