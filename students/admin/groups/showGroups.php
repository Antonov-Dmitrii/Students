<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	$success = 0;
	if(!isset($_SESSION["success"])) {
		$success = 0;
	}
	else $success = $_SESSION["success"];
	
	if(isset($_POST["editMarkedItem"])) {
		$idBranch = $_POST["idBranch"];
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна группа";
		else {
			header("Location: editGroup.php?edit=$idItem[0]&idBranch=$idBranch");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idBranch = $_POST["idBranch"];
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна группа";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idBranch = $_POST["idBranch"];
		$idItem = $_POST["idItem"];
		foreach($idItem as $value) {
			$db->doIsDeleted("groups", $value);
		}
		
		$_SESSION["success"] = 1;
		
		header("Location: showGroups.php?branch=$idBranch");
		exit;
	}

	if(isset($_POST["cancelDelete"])) {
		$addConfirmButton = false;
	}
	
	if(isset($_GET["branch"])) {
		$idBranch = $_GET["branch"];
		
		$result_set = $db->getRowWhere("branches", "id", $idBranch);
		$row = $result_set->fetch_assoc();
		
		$result_set1 = $db->getRowWhereOrderNotDeleted("groups", "idBranch", $idBranch, "name");
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Редактирование групп, находящихся в отделении "<?=$row[name]?>"</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
			
				<? if($success == 1) {?>
					<p class="success">Данные успешно сохранены!</p>
				<?}?>
				
				<? if($addConfirmButton) {?>
					<form name="comfirmDelete" action="" method="post">
						<p><b>Вы действительно хотите удалить следующие группы?</b></p>
						<?
							$i = 0;
							foreach($idItem as $value) {
								$result_set4 = $db->getRowWhere("groups", "id", $value);
								$row4 = $result_set4->fetch_assoc(); ?>
								<input type="hidden" name="idItem[]" value="<?=$idItem[$i]?>">
								<? echo "<b>".$row4[name]."</b>"."<br />";
								$i++;
							}
						?> <br />
						<input type="hidden" name="idBranch" value="<?=$idBranch ?>">
						<input class="standartButton" type="submit" name="comfirmDelete" value="Удалить">
						<input class="standartButton" type="submit" name="cancelDelete" value="Отменить">
					</form>
					<br /> 
				<?}?>
				
				<a href="addGroup.php?idBranch=<?=$idBranch?>">Добавить новую группу</a> <br />
				
				<div class="">
					<h3>Редактирование групп, находящихся в отделении "<?=$row[name]?>"</h3>
					<form name="" action="" method="post">
						<input class="standartButton pr" type="submit" name="editMarkedItem" value="Правки">
						<input class="standartButton de" type="submit" name="deleteMarkedItems" value="Удалить">
						<span style="color: red";><?=$error_delete ?></span> <br /> <br />
						<input type="hidden" name="idBranch" value="<?=$idBranch ?>">
						
						<table class="" border='1'>
							<tr>
								<th></th>
								<th>Название группы</th>
							</tr>
							<? while (($row1 = $result_set1->fetch_assoc()) != false) { ?>					
								<tr>
									<td><input type="checkbox" name="idItem[]" value="<?=$row1[id] ?>"></td>
									<td><?=$row1[name] ?></td>
								</tr>
							<?}?>
						</table>
					</form> <br />
				</div>
			</main>
		</body>
		</html>
	<?}?>
	<?	$_SESSION["success"] = 0; ?>