<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Symbole</div>
	<table class='table orderListTable'>
		<tr>
			<th>symbol</th><th>ukryty</th>
		</tr>
	<?PHP foreach($boardsSymbols as $symbol):?>
		<tr id='<?=$symbol -> id?>' class="pointer" onclick="showOptions('<?=$symbol -> id?>');">
			<td>
				<label><span class='symbols' id='symbol<?=$symbol -> id?>'><?=$symbol -> symbol?></span></label>
			</td>
			<td id="hidden<?=$symbol -> id?>"><?PHP if($symbol -> hidden): ?><span class="glyphicon glyphicon-eye-close"></span><?PHP endif;?></td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
		<div class="btn btn-default btn-block" type="button" onclick="addNewSymbol();"><span class="glyphicon glyphicon-plus"></span> Nowy symbol</div>
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