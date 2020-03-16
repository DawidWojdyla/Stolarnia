<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
<div class="text-center header">Nowy pracownik</div>
<form action="index.php?action=addNewWorker" name="workerAddingForm" id="workerAddingForm" method="post" autocomplete="off">
<div class="tabContainer">
	<table class="table table-condensed addingTab">
		<?PHP if (isset($_SESSION ['workerNameError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['workerNameError']?></td></tr>
		<?PHP unset($_SESSION ['workerNameError']); endif; ?>
		<tr>
			<td><label>Imię:</label></td>
			<td>
				<input id='workerName' class="form-control text-capitalize" type="text" name="workerName" required value="<?PHP if (isset($_SESSION['workerName'])): ?><?=$_SESSION['workerName']?><?PHP unset ($_SESSION['workerName']); endif; ?>"/>
			</td>
		<?PHP if (isset($_SESSION ['workerSurnameError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['workerSurnameError']?></td></tr>
		<?PHP unset($_SESSION ['workerSurnameError']); endif; ?>
		</tr>
		<tr>
			<td><label>Nazwisko:</label></td>
			<td>
				<input id='workerSurname' minlength="2" class="form-control text-capitalize" type="text" name="workerSurname" required <?PHP if (isset($_SESSION['workerSurname'])): ?>value="<?=$_SESSION['workerSurname']?>"<?PHP unset ($_SESSION['workerSurname']); endif; ?>/>
			</td>
		</tr>
		<tr>
			<td><label>Stanowisko:</label></td>
			<td>
				<select class='form-control' id='stands' name='stands[]' multiple required><?PHP foreach ($standsList as $stand): ?><option value='<?=$stand -> id?>'><?=$stand -> name?></option><?PHP endforeach; ?></select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Zapisz pracownika</div>
			</td>
		</tr>
	</table>
	</div>
</form>
</div>