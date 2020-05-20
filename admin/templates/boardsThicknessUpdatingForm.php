<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Grubości płyt</div>
	<table class='table orderListTable'>
		<tr>
			<th>grubość</th><th>ukryty</th>
		</tr>
	<?PHP foreach($boardsThickness as $thickness):?>
		<tr id='<?=$thickness -> id?>' class="pointer" onclick="showOptions('<?=$thickness -> id?>');">
			<td>
				<label><span class='thickness' id='thickness<?=$thickness -> id?>'><?=$thickness -> thickness?></span></label>
			</td>
			<td id="hidden<?=$thickness -> id?>"><?PHP if($thickness -> hidden): ?><span class="glyphicon glyphicon-eye-close"></span><?PHP endif;?></td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
		<div class="btn btn-default btn-block" type="button" onclick="addNewThickness();"><span class="glyphicon glyphicon-plus"></span> Nowa grubość</div>
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