<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	if(isset($_POST["edit"])) {
		$id = $_POST["id"];

		$startDate = $_POST["startDate"];
		$endDate = $_POST["endDate"];
		$isActive = $_POST["isActive"];
		
		$error_startDate = "";
		$error_endDate = "";
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
			if(strlen($isActive) != 0) {
				$isActive = 1;
				$result_set = $db->getAllNotDeletedOrder("start_and_end_semesters_dates", "startDate DESC");
				while (($row = $result_set->fetch_assoc()) != false) {
					if($row[isActive] == 1) $db->resetIsActive($row[id]);
				}
			}
			$db->editSemester($id, $startDate, $endDate, $isActive);
			
			$_SESSION["success"] = 1;
					
			header("Location: allSemesters.php");
			exit;
		}
	}
		
	if(isset($_GET["edit"])) {
		$id = $_GET["edit"];
				
		$result_set = $db->getRowWhere("start_and_end_semesters_dates", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$startDate = date('Y-m-d', $row["startDate"]);
		$endDate = date('Y-m-d', $row["endDate"]);
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Редактирование семестра</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
				<div class="">
					<h3>Редактирование семестра</h3>
					<form name="" action="" method="post">
						Дата начала семестра*
						<div>
							<input class="standartInput" type="date" name="startDate" value="<?=$startDate?>">
							<span style="color: red";><?=$error_startDate ?></span>
						</div> <br />
						
						Дата окончания семестра*
						<div>
							<input class="standartInput" type="date" name="endDate" value="<?=$endDate?>">
							<span style="color: red";><?=$error_endDate ?></span>
						</div> <br />
						
						<? if($row["isActive"] == 1) {?>
							<input type="checkbox" checked name="isActive"> Является ли семестр действующим <br /> <br />
						<?} else {?>
							<input type="checkbox" name="isActive"> Является ли семестр действующим <br /> <br />
						<?}?>
						
						<input type="hidden" name="id" value="<?=$id ?>">
						<input class="standartButton" type="submit" name="edit" value="Изменить">
					</form>
				</div>
			</main>
		</body>
		</html>
	<?}?>