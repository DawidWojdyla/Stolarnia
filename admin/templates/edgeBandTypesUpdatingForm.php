<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Typy oklein</div>
	<table class='table orderListTable'>
		<tr>
			<th>typ</th><th>ukryty</th><th>standard</th>
		</tr>
	<?PHP foreach($edgeBandTypes as $edgeBand):?>
		<tr id='<?=$edgeBand -> id?>' class="pointer" onclick="showOptions('<?=$edgeBand -> id?>');">
			<td>
				<label><span class='edgeBands' id='edgeBand<?=$edgeBand -> id?>'><?=$edgeBand -> type?></span></label>
			</td>
			<td id="hidden<?=$edgeBand -> id?>"><?PHP if($edgeBand -> hidden): ?><span class="glyphicon glyphicon-eye-close"></span><?PHP endif;?></td>
			<td id="default<?=$edgeBand -> id?>"><?PHP if($edgeBand -> max_thickness): ?><span class="glyphicon glyphicon-flag"></span><?PHP endif;?></td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
		<div class="btn btn-default btn-block" type="button" onclick="addNewEdgeBandType();"><span class="glyphicon glyphicon-plus"></span> Nowy typ</div>
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