<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();

	if(isset($_POST["edit"])) {
		$id = $_POST["id"];

		$name = $_POST["name"];
		
		$error_name = "";
		$error = false;
				
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}			
		
		if(!$error) {			
			$db->edit("branches", $id, $name);
			
			$_SESSION["success"] = 1;
					
			header("Location: allBranches.php");
			exit;
		}
	}
		
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("branches", "id", $id);
		$row = $result_set->fetch_assoc();
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Редактирование названия отделения</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
				<div class="">
					<h3>Редактирование названия отделения</h3>
					<form name="" action="" method="post">
						<div>
							<input class="standartInput" type="text" name="name" placeholder="" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
							<span style="color: red";><?=$error_name ?></span>
						</div> <br />
						
						<input type="hidden" name="id" value="<?=$id ?>">
						<input class="standartButton" type="submit" name="edit" value="Изменить">
					</form>
				</div>
			</main>
		</body>
		</html>
	<?}?>