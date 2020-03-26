<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Konto Administratora</div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr class="noTopBorder">
				<td><label>login:</label></td>
				<td>
					<div style="float:left;" id="login"><?=$login?></div>
					<div class="text-right noPadding">
						<span onclick="showOptions();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='modalBody'>
				</div>
			</div>
		</div>
	</div>
</div>