<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	if(isset($_POST["add"])) {		
		$name = $_POST["name"];
		
		$error_name = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {		
			$db = DB::getObject();
			$db->add("subjects", $name);
			
			$_SESSION["success"] = 1;
			
			header("Location: allSubjects.php");
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Добавить предмет</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		<h3>Добавить предмет</h3>
		
		<form name="" action="" method="post">
			<div>
				<input class="standartInput" type="text" name="name" placeholder="Название предмета" value="<?=$_POST["name"]?>"> <br />
				<span style="color: red";><?=$error_name ?></span>
			</div> <br />
			
			<input class="standartButton" type="submit" name="add" value="Добавить">
		</form>
	</main>
</body>
</html>