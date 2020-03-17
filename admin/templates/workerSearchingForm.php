<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<form action="index.php?action=findWorker" name="workerSearchingForm" id="workerSearchingForm" method="post" autocomplete="off">
		<div class="text-center header">Znajdź pracownika:</div>
		<div class="tabContainer">
			<table class="table addingTab">
				<tr>
					<td><label>Imię:</label></td>
					<td><input minlength="2"  id='name' class="form-control text-center" type="text" name="name" maxlength="25" value="<?=$name?>"/></td>
				</tr>
				<tr>
					<td><label>Nazwisko:</label></td>
					<td><input minlength="2"  id='surname' class="form-control text-center" type="text" name="surname" maxlength="25" value="<?=$surname?>"/></td>
				</tr>
				<tr>
					<td><label>Stanowisko:</label></td>
					<td>
						<select class='form-control' id='stands' name='stands[]' multiple>
							<?PHP foreach ($stands as $stand): ?>
							<option value='<?=$stand -> id?>' <?PHP if($standsIds): foreach($standsIds as $standId): if($standId == $stand -> id): ?>selected<?PHP endif; endforeach; endif;?>><?=$stand -> name?></option>
							<?PHP endforeach; ?>
						</select>
					</td>
				</tr>
				<tr><td colspan='2'><button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Znajdź</button></td></tr>
			</table>
		</div>
	</form>
</div>
	
