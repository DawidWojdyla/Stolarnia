<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Symbole</div>
	<table class='table orderListTable text-center'>
		<tr>
			<th class="text-center">symbol</th><th class="text-center">okleina</th><th class="text-center">laminowana</th><th class="text-center">blat</th><th class="text-center">hdf</th><th class="text-center">mdf</th><th class="text-center">akryl</th><th class="text-center">połysk</th><th class="text-center">fornir</th><th class="text-center">ukryty</th>
		</tr>
	<?PHP foreach($boardsSymbols as $symbol):?>
		<tr id='<?=$symbol -> id?>' class="pointer" onclick="showOptions('<?=$symbol -> id?>');">
			<td>
				<label><span class='symbols' id='symbol<?=$symbol -> id?>'><?=$symbol -> symbol?></span></label>
			</td>
			<td id="edgeBand<?=$symbol -> id?>"><?PHP if($symbol -> noEdgeBand): ?><span class="glyphicon glyphicon-remove"></span><?PHP endif;?></td>
			<td id="melamine<?=$symbol -> id?>"><?PHP if($symbol -> melamine): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="worktops<?=$symbol -> id?>"><?PHP if($symbol -> worktops): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="hdf<?=$symbol -> id?>"><?PHP if($symbol -> hdf): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="mdf<?=$symbol -> id?>"><?PHP if($symbol -> mdf): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="acrylic<?=$symbol -> id?>"><?PHP if($symbol -> acrylic): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="glossy<?=$symbol -> id?>"><?PHP if($symbol -> glossy): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
			<td id="veneer<?=$symbol -> id?>"><?PHP if($symbol -> veneer): ?><span class="glyphicon glyphicon-ok"></span><?PHP endif;?></td>
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