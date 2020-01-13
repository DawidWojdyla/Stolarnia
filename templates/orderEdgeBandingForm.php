<?php if(!isset($this)) die(); ?>
<h3 class="text-center"><?=$orderTitle?></h3>
	<?PHP foreach($boards as $board): ?>
	<div style="border-top: solid white 2px;" class='text-center pointer boardTitle' id='board<?=$board['boardId']?>' onclick="showBoardDetails('<?=$board['boardId']?>')"><h4><?=$board['boardName']?></h4>ilość: <?=($board['boardAmount']+0)?> szt. / cięcie: <?=($board['boardCuttingMetters']+0)?> mb</div>
	<table style="display:none;" id="table<?=$board['boardId']?>" class="table table-condensed ebtable">
	<tr>
		<td>pilarze:</td><td colspan='2'><?PHP foreach($board['cuttingWorkers'] as $worker): ?><div><?=$worker?></div><?PHP endforeach; ?></td>
	</tr>
	<?PHP if($board['boardCuttingComment']): ?>
	<tr>
		<td>cięcie - uwagi:</td><td colspan='2'><?=$board['boardCuttingComment']?></td>
	</tr>
	<?PHP endif; ?>
	<th>obrzeże</th><th><div>wz</div><div>[mb]</div></th><th><div>maszyna</div><div>[mb]</div></th>
	<?PHP foreach($board['edgeBanding'] as $edgeBanding): ?>
	<tr class="pointer"  id="<?=$edgeBanding['edgeBandingId']?>" onclick="showEdgeBandingModal(<?=$edgeBanding['edgeBandingId']?>);">
		<td><div><?=$edgeBanding['edgeBandType']?><?PHP if($edgeBanding['edgeBandSymbol'] != '---'): ?> <?=$edgeBanding['edgeBandSymbol']?><?PHP endif; ?></div>
		<div>(<?=$edgeBanding['stickerSymbol']?>)</div></td>
		<td><?=($edgeBanding['wzMetters']+0)?></td>
		<td id="m<?=$edgeBanding['edgeBandingId']?>"><?=($edgeBanding['machineMetters']+0)?></td>
	</tr>
	<?PHP if($edgeBanding['edgeBandComment']): ?>
	<tr>
		<td colspan="3" style='border-top: none;'><?=$edgeBanding['edgeBandComment']?></td>
	</tr>
	<?PHP endif; ?>
	<tr style="display:none;"><td id="c<?=$edgeBanding['edgeBandingId']?>"><?PHP if($edgeBanding['edgeBandingComment']): ?><?=$edgeBanding['edgeBandingComment']?><?PHP endif; ?></td></tr>
	<?PHP endforeach; ?>
	</table>
	<?PHP endforeach; ?>
	<?PHP if($phone != ""): ?>
	</br>
	<div class="text-center"><h5><a href="sms:+48<?=$phone?>?body=ITS%Rzeszów.%20Państwa%20zamówienie%20zostało%20zrealizowane.%20Zapraszamy%20po%20odbiór%20od%20poniedziałku%20do%20piątku%20w%20godzinach%207-17.%20Pozdrawiamy.">Wyślij SMS</a></h5></div>
	<?PHP endif; ?>
	
<div class="modal fade" id="edgeBandingModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='edgeBandingModalBody'>
			</div>
		</div>
	</div>
</div>