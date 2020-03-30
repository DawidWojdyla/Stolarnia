<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Logowanie</div>
	<div class="tabContainer">
		<div class="loginFormWrapper">
			<form class="form-horizontal" action="index.php?action=login" method = "post">
				<div class="form-group">
					<label class="control-label col-sm-2" for="standSelect">Stanowisko: </label>
					<div class="col-sm-10">
						<select  name="standId" id="standSelect" class="form-control">
						<?PHP foreach($stands as $stand): ?>
							<option value="<?=$stand -> id?>"><?=$stand -> name?></option>
						<?PHP endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="standPassword">Hasło: </label>
					<div class="col-sm-10">
						<input class="form-control" name="password" id="standPassword" type="password" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default btn-block">Zaloguj</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>