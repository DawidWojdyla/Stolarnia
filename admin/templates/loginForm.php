<?php if(!isset($joineryAdmin)) die(); ?>
<div class="loginFormWrapper">
	<form class="form-horizontal" action="index.php?action=login" method = "post" autocomplete="off">
		<div class="form-group">
			<label for="login">Login: </label>
			<input class="form-control" name="login" id="login" type="text" required>
		</div>
		<div class="form-group">
			<label for="password">Hasło: </label>
			<input class="form-control" name="password" id="password" type="password" required>
		</div>
		<div class="form-group">
		<label> </label>
		<button type="submit" class="btn btn-default btn-block">Zaloguj</button>
		</div>
	</form>
</div>