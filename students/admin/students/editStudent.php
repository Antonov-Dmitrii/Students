<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();

	if(isset($_POST["edit"])) {
		$idGroup = $_POST["idGroup"];
		$idStudent = $_POST["idStudent"];

		$name = $_POST["name"];
		$date = $_POST["date"];
		$docs = $_POST["docs"];
		$note = $_POST["note"];
		
		$error_name = "";
		$error_date = "";
		$error_docs = "";
		$error = false;
				
		if(strlen($name) == 0) {
			$error_name = "Не заполнено поле";
			$error = true;
		}	
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		if(strlen($docs) == 0) {
			$error_docs = "Не заполнено поле";
			$error = true;
		}			
		
		if(!$error) {			
			$db->editStudent($idStudent, $name, $date, $docs, $note);
			
			$_SESSION["success"] = 1;
					
			header("Location: allStudents.php?idGroup=$idGroup");
			exit;
		}
		else $newDate = $_POST["date"];
	}
		
	if(isset($_GET["edit"])) {
		$idStudent = $_GET["edit"];
		$idGroup = $_GET["idGroup"];
				
		$result_set = $db->getRowWhere("students", "id", $idStudent);
		$row = $result_set->fetch_assoc();
		
		$date = date('Y-m-d', $row["date"]);
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Редактирование данных студента</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
				<div class="">
					<h3>Редактирование данных студента</h3>
					<form name="" action="" method="post">
						ФИО студента*
						<div>
							<input class="standartInput" type="text" name="name" placeholder="" value="<?=isset($_POST["name"])? $_POST["name"]:$row[name]?>">
							<span style="color: red";><?=$error_name ?></span>
						</div> <br />
						
						Дата рождения*
						<div>
							<input class="standartInput" type="date" name="date" value="<?=isset($_POST["date"])? $newDate:$date?>">
							<span style="color: red";><?=$error_date ?></span>
						</div> <br />
						
						Документ, удостоверяющий личность*
						<div>
							<textarea class="standartTextArea" rows="6" cols="40" name="docs"><?=isset($_POST["docs"])? $_POST["docs"]:$row[docs]?></textarea>
							<span style="color: red";><?=$error_docs ?></span>
						</div> <br />

						Примечания
						<div>
							<textarea class="standartTextArea" rows="6" cols="40" name="note"><?=isset($_POST["note"])? $_POST["note"]:$row[note]?></textarea>
						</div> <br />
						
						<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
						<input type="hidden" name="idStudent" value="<?=$idStudent ?>">
						<input class="standartButton" type="submit" name="edit" value="Изменить">
					</form>
				</div>
			</main>
		</body>
		</html>
	<?}?>