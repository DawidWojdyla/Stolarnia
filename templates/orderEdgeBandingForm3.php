<?php if(!isset($this)) die(); ?>
<h3 class="text-center"><?=$orderTitle?></h3>
<table class="table">
	<th>płyta</th><th><div>obrzeże</div><div>(naklejki)</div></th><th><div>wz</div><div>[mb]</div></th><th><div>maszyna</div><div>[mb]</div></th>
	<?PHP foreach($boards as $board): ?>
	<tr class="pointer" id="<?=$board->edgeBandingId?>" onclick="showEdgeBandingModal(<?=$board->edgeBandingId?>);">
		<td id='board<?=$board->boardId?>'><div><?=$board->boardSign?><?=($board->thickness+0)?></div><div><?PHP if($board->boardSymbol != '---'): ?><?=$board->boardSymbol?><?PHP endif;?><?PHP if($board->structure != '---'): ?><?=$board->structure?><?PHP endif;?></div></td>
		<td><div><?=$board->edgeBandType?><?PHP if($board->edgeBandSymbol != '---'): ?> <?=$board->edgeBandSymbol?><?PHP endif; ?></div><div>(<?=$board->stickerSymbol?>)</div></td>
		<td><?=($board->wzMetters+0)?></td>
		<td id="m<?=$board->edgeBandingId?>"><?=($board->machineMetters+0)?></td>
	</tr>
	<?PHP if($board->edgeBandComment): ?>
	<tr>
		<td colspan="4" style='border-top: none;'>okleina->uwagi: <?=$board->edgeBandComment?></td>
	</tr>
	<?PHP endif; ?>
	<?PHP if($board->cuttingComment): ?>
	<tr>
		<td colspan="4" style='border-top: none;'>cięcie->uwagi: <?=$board->cuttingComment?></td>
	</tr>
	<?PHP endif; ?>
	<tr style="display:none;"><td id="c<?=$board->edgeBandingId?>"><?PHP if($board->edgeBandingComment): ?><?=$board->edgeBandingComment?><?PHP endif; ?></td></tr>
	<?PHP endforeach; ?>
	</table>
<div class="modal fade" id="edgeBandingModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='edgeBandingModalBody'>
			</div>
		</div>
	</div>
</div>