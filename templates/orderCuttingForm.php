<?php if(!isset($this)) die(); ?>
<div class="contentContainer noMargin">
	<div class="header text-center"><div><?=$documentNumber?></div><div><?=$customerName?></div><div style="font-size: 16px; margin-top: -3px;">tel. <?=$phone?></div></div>
	<div style="border-top: 1px solid black; padding-left: 2px; padding-right: 2px; padding-top: 2px;" class="tabContainer">
		<?PHP if($comment != ""): ?>
		<div style="border-bottom: 0.5px solid black; padding-top:3px; padding-bottom:3px; margin-bottom: -5px;" class="tabContainer text-center">
			<div class="container noPadding italic"><span class="glyphicon glyphicon-paperclip"></span> <?=$comment?></div>
		</div>
		<?PHP endif;?>
	<table class="table table-condensed cutFormTable edgeBandingOrderListTable">
		<th class='text-center'>płyta</th><th class='text-center'><div>ilość</div><div>[szt.]</div></th><th class='text-center'><div>cięcie</div><div>[mb]</div></th><th class='text-center'>okleiny</th><th class='text-center'>status</th>
		<?PHP foreach($boards as $board): ?>
		<?PHP if($board -> edgeBandingAmount > 0): ?>
		<script>isEdgeBanding = true;</script>
		<?PHP endif; ?>
		<tr class="btn btn-default btn-block cuttingButton" style="display: table-row;" id="<?=$board->boardId?>" onclick="showCuttingModal(<?=$board->boardId?>);">
			<td id='board<?=$board->boardId?>' class="text-center"><?=$board->sign?><?=($board->thickness+0)?><?PHP if($board->symbol != ''): ?>-<?=$board->symbol?><?PHP endif;?></td><td class="text-center"><?=($board->amount+0)?></td><td class="text-center"><?=($board->cutting_metters+0)?></td><td class="text-center"><?=$board -> edgeBandingAmount?></td><td class="text-center" id="s<?=$board->boardId?>"><?PHP if($board->cutting_completion_date == NULL): ?>niepocięta<script>itemsToDo++;</script><?PHP else: ?>pocięta<?PHP endif; ?></td></tr>
			<tr style="display:none;"><td id="c<?=$board->boardId?>"><?=$board->cuttingComment?></td></tr>
		<?PHP endforeach; ?>
		</table>
		<div style="padding-bottom: 1px; margin-top: 20px; margin-bottom: 20px;">
			<div class='btn btn-default btn-block' id="sms" style="display: none;" onclick="sendSMS();"><span class="glyphicon glyphicon-earphone"></span> Wyślij SMS</div>
		</div>
		<script>
			if(itemsToDo < 1 && !isEdgeBanding){
				document.getElementById('sms').style.display = 'block';
			}
		</script>
</div>
</div>
<div class="modal fade" id="cutting-modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='cutting-modal-body'>
				</div>
			</div>
		</div>
	</div>
</div>