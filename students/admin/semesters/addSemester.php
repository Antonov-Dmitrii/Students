<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	if(isset($_POST["add"])) {		
		$startDate = $_POST["startDate"];
		$endDate = $_POST["endDate"];
		$isActive = $_POST["isActive"];
		
		$error_name = "";
		$error_name = "";
		$error = false;
		
		if(strlen($startDate) == 0) {
			$error_startDate = "Не заполнено поле";
			$error = true;
		}
		if(strlen($endDate) == 0) {
			$error_endDate = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {		
			$db = DB::getObject();
			if(strlen($isActive) != 0) {
				$isActive = 1;
				$result_set = $db->getAllNotDeletedOrder("start_and_end_semesters_dates", "startDate DESC");
				while (($row = $result_set->fetch_assoc()) != false) {
					if($row[isActive] == 1) $db->resetIsActive($row[id]);
				}
			}
		
			$db->addSemester($startDate, $endDate, $isActive);
			
			$_SESSION["success"] = 1;
			
			header("Location: allSemesters.php");
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Добавить семестр</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		<h3>Добавить семестр</h3>
		
		<form name="" action="" method="post">
			Дата начала семестра*
			<div>
				<input class="standartInput" type="date" name="startDate" value="<?=$_POST["startDate"]?>"> <br />
				<span style="color: red";><?=$error_startDate ?></span>
			</div> <br />

			Дата окончания семестра*
			<div>
				<input class="standartInput" type="date" name="endDate" value="<?=$_POST["endDate"]?>"> <br />
				<span style="color: red";><?=$error_endDate ?></span>
			</div> <br />
			
			<input type="checkbox" name="isActive"> Является ли семестр действующим <br /> <br />
			
			<input class="standartButton" type="submit" name="add" value="Добавить">
		</form>
	</main>
</body>
</html>