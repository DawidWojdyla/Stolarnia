<?php if(!isset($this)) die(); ?>
<h3 class="text-center"><?=$orderTitle?></h3>
<table class="table">
	<th>płyta</th><th><div>ilość</div><div>[szt.]</div></th><th><div>cięcie</div><div>[mb]</div></th><th>status</th>
	<?PHP foreach($boards as $board): ?>
	<tr class='orderLink' id="<?=$board->boardId?>" onclick="showCuttingModal(<?=$board->boardId?>);">
		<td id='board<?=$board->boardId?>'><?=$board->sign?><?=($board->thickness+0)?><?PHP if($board->symbol != '---'): ?>-<?=$board->symbol?><?PHP endif;?><?PHP if($board->structure != '---'): ?><?=$board->structure?><?PHP endif;?></td><td><?=($board->amount+0)?></td><td><?=($board->cutting_metters+0)?></td><td  id="s<?=$board->boardId?>"><?PHP if($board->cutting_completion_date == NULL): ?>niepocięta<?PHP else: ?>pocięta<?PHP endif; ?></td></tr>
		<tr style="display:none;"><td id="c<?=$board->boardId?>"><?=$board->cuttingComment?></td></tr>
	<?PHP endforeach; ?>
	</table>

<div class="modal fade" id="cutting-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='cutting-modal-body'>
			</div>
		</div>
	</div>
</div>