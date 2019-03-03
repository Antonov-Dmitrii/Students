<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$success = 0;
	if(!isset($_SESSION["success"])) {
		$success = 0;
	}
	else $success = $_SESSION["success"];
	
	$db = DB::getObject();
	$result_set = $db->getAllNotDeletedOrder("branches", "name");
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не было выбрано ни одно отделение";
		else {
			header("Location: editBranch.php?edit=$idItem[0]");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не было выбрано ни одно отделение";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];
		foreach($idItem as $value) {
			$db->doIsDeleted("branches", $value);
		}
		
		$_SESSION["success"] = 1;
		
		header("Location: allBranches.php");
		exit;
	}

	if(isset($_POST["cancelDelete"])) {
		$addConfirmButton = false;
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Список отделений</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		
		<? if($success == 1) {?>
			<p class="success">Данные успешно сохранены!</p>
		<?}?>
		
		<? if($addConfirmButton) {?>
			<form name="comfirmDelete" action="" method="post">
				<p><b>Вы действительно хотите удалить следующие отделения?</b></p>
				<?
					$i = 0;
					foreach($idItem as $value) {
						$result_set4 = $db->getRowWhere("branches", "id", $value);
						$row4 = $result_set4->fetch_assoc(); ?>
						<input type="hidden" name="idItem[]" value="<?=$idItem[$i]?>">
						<? echo "<b>".$row4[name]."</b>"."<br />";
						$i++;
					}
				?> <br />
				<input class="standartButton" type="submit" name="comfirmDelete" value="Удалить">
				<input class="standartButton" type="submit" name="cancelDelete" value="Отменить">
			</form>
			<br /> 
		<?}?>
		
		<a href="addBranch.php">Добавить новое отделение</a> <br />
		
		<h3>Список отделений</h3>
		
		<form name="" action="" method="post">
			<input class="standartButton pr" type="submit" name="editMarkedItem" value="Правки">
			<input class="standartButton de" type="submit" name="deleteMarkedItems" value="Удалить">
			<span style="color: red";><?=$error_delete ?></span> <br /> <br />
			
			<table class="" border='1'>
				<tr>
					<th></th>
					<th>Название отделения</th>
				</tr>
				<? while (($row = $result_set->fetch_assoc()) != false) { ?>					
					<tr>
						<td><input type="checkbox" name="idItem[]" value="<?=$row[id] ?>"></td>
						<td><?=$row[name] ?></td>
					</tr>
				<?}?>
			</table>
		</form> <br />
	</main>
</body>
</html>
<?	$_SESSION["success"] = 0; ?>