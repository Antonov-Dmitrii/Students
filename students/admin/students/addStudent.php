<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	if(isset($_POST["add"])) {		
		$idGroup = $_POST["idGroup"];
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
			$db->addStudent($idGroup, $name, $date, $docs, $note);
			
			$_SESSION["success"] = 1;
			
			header("Location: allStudents.php?idGroup=$idGroup");
			exit;
		}
	}
	
	if(isset($_GET["idGroup"])) {
		$idGroup = $_GET["idGroup"];
		
		$result_set = $db->getRowWhere("groups", "id", $idGroup);
		$row = $result_set->fetch_assoc();
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Добавить студента в группу "<?=$row[name]?>"</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
				<h3>Добавить студента в группу "<?=$row[name]?>"</h3>

				<form name="" action="" method="post">
					<div>
						<input class="standartInput" type="text" name="name" placeholder="ФИО*" value="<?=$_POST["name"]?>"> <br />
						<span style="color: red";><?=$error_name ?></span>
					</div> <br />

					Дата рождения*
					<div>
						<input class="standartInput" type="date" name="date" value="<?=$_POST["date"]?>"> <br />
						<span style="color: red";><?=$error_date ?></span>
					</div> <br />
					
					<div>
						<textarea class="standartTextArea" rows="6" cols="35" name="docs" placeholder="Документ, удостоверяющий личность - название, серия и номер, дата выдачи, орган, который выдал документ*"><?=$_POST["docs"]?></textarea>
						<br />
						<span style="color: red";><?=$error_docs ?></span>
					</div> <br />

					<div>
						<textarea class="standartTextArea" rows="6" cols="35" name="note" placeholder="Примечание"><?=$_POST["note"]?></textarea>
					</div> <br />
					
					
					<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
					<input class="standartButton" type="submit" name="add" value="Добавить">
				</form>
			</main>
		</body>
		</html>
	<?}?>