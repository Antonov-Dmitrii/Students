<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	if(isset($_POST["add"])) {		
		$idBranch = $_POST["idBranch"];
		$name = $_POST["name"];
		
		$error_name = "";
		$error = false;
		
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {		
			$db->addGroup($name, $idBranch);
			
			$_SESSION["success"] = 1;
			
			header("Location: showGroups.php?branch=$idBranch");
			exit;
		}
	}
	
	if(isset($_GET["idBranch"])) {
		$idBranch = $_GET["idBranch"];
		
		$result_set = $db->getRowWhere("branches", "id", $idBranch);
		$row = $result_set->fetch_assoc();
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Добавить группу в отделение "<?=$row[name]?>"</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
				<h3>Добавить группу в отделение "<?=$row[name]?>"</h3>
				<form name="" action="" method="post">
					<div>
						<input class="standartInput" type="text" name="name" placeholder="Название группы" value="<?=$_POST["name"]?>"> <br />
						<span style="color: red";><?=$error_name ?></span>
					</div> <br />
					<input type="hidden" name="idBranch" value="<?=$idBranch ?>">
					<input class="standartButton" type="submit" name="add" value="Добавить">
				</form>
			</main>
		</body>
		</html>
	<?}?>