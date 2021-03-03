<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Rodzaje płyt</div>
	<table class='table orderListTable'>
		<tr>
			<th>rodzaj</th><th>standardowa grubość</th><th>symbole</th><th>priorytet</th><th>ukryty</th>
		</tr>
	<?PHP foreach($boardsSigns as $sign):?>
		<tr id='<?=$sign -> id?>' class="pointer" onclick="showOptions('<?=$sign -> id?>');">
			<td>
				<label><span class='signs' id='sign<?=$sign -> id?>'><?=$sign -> sign?></span></label>
			</td>
			<td><span id='thickness<?=$sign -> id?>'><?PHP if($sign -> thicknessId): ?><?=$sign -> thickness?><?PHP else: ?>-<?PHP endif;?></span>
				<span style="display: none;" id='thicknessId<?=$sign -> id?>'><?=$sign -> thicknessId?></span>
			</td>
			<td id='symbol<?=$sign -> id?>'><?PHP if($sign -> noSymbolNeeded): ?><span class="glyphicon glyphicon-ban-circle"></span><?PHP else: ?><span class="glyphicon glyphicon-ok"></span><?PHP endif; ?></td>
			<td><span id='priority<?=$sign -> id?>'><?PHP if($sign -> priority): ?><?=$sign -> priority?><?PHP else: ?>-<?PHP endif;?></span></td>
			<td><span id='hidden<?=$sign -> id?>'><?PHP if($sign -> hidden): ?><span class="glyphicon glyphicon-eye-close"></span><?PHP endif;?></span></td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
		<div class="btn btn-default btn-block" type="button" onclick="addNewSign();"><span class="glyphicon glyphicon-plus"></span> Nowy Rodzaj</div>
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