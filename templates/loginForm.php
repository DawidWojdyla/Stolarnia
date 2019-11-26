<?php if(!isset($joinery)) die(); ?>
<div class="loginFormWrapper">
	<form class="form-horizontal" action="index.php?action=login" method = "post">
		<div class="form-group">
			<label for="standSelect">Stanowisko: </label>
			<select  name="standId" id="standSelect" class="form-control">
				<option value="1">Piła 1</option>
				<option value="2">Piła 2</option>
				<option value="3">Okleiniarka</option>
				<option value="4">Sklep</option>
			</select>
		</div>
		<div class="form-group">
			<label for="standPassword">Hasło: </label>
			<input class="form-control" name="password" id="standPassword" type="password" required>
		</div>
		<div class="form-group">
		<label> </label>
		<button type="submit" class="btn btn-default btn-block">Zaloguj</button>
		</div>
	</form>
</div>