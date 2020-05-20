<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Naklejki (symbole okleiny)</div>
	<table class='table orderListTable'>
		<tr>
			<th>symbol</th><th>ukryty</th>
		</tr>
	<?PHP foreach($edgeBandStickerSymbols as $stickerSymbol):?>
		<tr id='<?=$stickerSymbol -> id?>' class="pointer" onclick="showOptions('<?=$stickerSymbol -> id?>');">
			<td>
				<label><span class='stickerSymbols' id='stickerSymbol<?=$stickerSymbol -> id?>'><?=$stickerSymbol -> symbol?></span></label>
			</td>
			<td id="hidden<?=$stickerSymbol -> id?>"><?PHP if($stickerSymbol -> hidden): ?><span class="glyphicon glyphicon-eye-close"></span><?PHP endif;?></td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
		<div class="btn btn-default btn-block" type="button" onclick="addNewEdgeBandStickerSymbol();"><span class="glyphicon glyphicon-plus"></span> Nowy symbol</div>
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