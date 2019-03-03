<div class="welcome">
	<? 
		$login = $_SESSION["login"];
		$user = DB::getObject();
		$result_set100 = $user->getUserByLogin($login);
		$row100 = $result_set100->fetch_assoc();
		$_SESSION["id"] = $row100["id"];
	?>
	<p><b>Личный кабинет: <?=$row100["surname"]." ",$row100["name"]." ",$row100["secondName"]?>
	<span class="exit">	
		<a class="" href="\lib/logout.php">Выйти</a></b></p>
	</span>
</div>
<hr>