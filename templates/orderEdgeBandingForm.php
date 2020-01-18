<?php if(!isset($this)) die(); ?>
<h3 class="text-center"><?=$orderTitle?></h3>
	<?PHP foreach($boards as $board): ?>
	<div class='btn btn-default btn-block boardTitle'' id='board<?=$board['boardId']?>' onclick="showBoardDetails('<?=$board['boardId']?>')"><?=$board['boardName']?></div>
	<div class="text-right" id="info<?=$board['boardId']?>" style="display:none;"><span class="glyphicon glyphicon-info-sign pointer" data-toggle="popover" data-trigger="focus" tabindex="0" data-placement="left" title="Informacje o cięciu" data-content="<table><tr><td>ilość:</td><td colspan='3'><?=($board['boardAmount']+0)?> szt.</td></tr><tr><td>cięcie:</td><td  colspan='3'><?=($board['boardCuttingMetters']+0)?> mb</td></tr><tr><td>pilarze:</td><td colspan='3'><?PHP foreach($board['cuttingWorkers'] as $worker): ?><?=$worker?><?PHP endforeach; ?></td></tr><?PHP if($board['boardCuttingComment']): ?><tr><td>uwagi:</td><td colspan='3'><?=$board['boardCuttingComment']?><?PHP endif; ?></td></tr></table>"></span></div>
	<table style="display:none;" id="table<?=$board['boardId']?>" class="table table-condensed ebtable">
	<th>obrzeże</th><th>naklejki</th><th><div>wz</div><div>[mb]</div></th><th><div>maszyna</div><div>[mb]</div></th>
	<?PHP foreach($board['edgeBanding'] as $edgeBanding): ?>
	<tr class="pointer"  id="<?=$edgeBanding['edgeBandingId']?>" onclick="showEdgeBandingModal(<?=$edgeBanding['edgeBandingId']?>);">
		<td style="border-top: 1.5px solid white;"><div><?=$edgeBanding['edgeBandType']?><?PHP if($edgeBanding['edgeBandSymbol'] != '---'): ?> <?=$edgeBanding['edgeBandSymbol']?><?PHP endif; ?></td>
		<td style="border-top: 1.5px solid white;"><?=$edgeBanding['stickerSymbol']?></td>
		<td style="border-top: 1.5px solid white;"><?=($edgeBanding['wzMetters']+0)?></td>
		<td style="border-top: 1.5px solid white;" id="m<?=$edgeBanding['edgeBandingId']?>"><?=($edgeBanding['machineMetters']+0)?></td>
	</tr>
	<?PHP if($edgeBanding['edgeBandComment']): ?>
	<tr class="pointer" onclick="showEdgeBandingModal(<?=$edgeBanding['edgeBandingId']?>);">
		<td colspan="4" style='border-top: none; font-style: italic;'><span style="font-size: 12px;" class="glyphicon glyphicon-pushpin"></span>  <?=$edgeBanding['edgeBandComment']?></td>
	</tr>
	<?PHP endif; ?>
	<tr style="display:none;"><td id="c<?=$edgeBanding['edgeBandingId']?>"><?PHP if($edgeBanding['edgeBandingComment']): ?><?=$edgeBanding['edgeBandingComment']?><?PHP endif; ?></td></tr>
	<?PHP endforeach; ?>
	</table>
	<?PHP endforeach; ?>
	<?PHP if($phone != ""): ?>
	</br>
	<div id='sms' class='btn btn-default btn-block' onclick="location.href='sms:+48<?=$phone?>?body=ITS%20Rzeszów.%20Państwa%20zamówienie%20zostało%20zrealizowane.%20Zapraszamy%20po%20odbiór%20od%20poniedziałku%20do%20piątku%20w%20godzinach%207-17.%20Pozdrawiamy.';location.href='index.php?action=showOrderList';">Wyślij SMS</div>
	<?PHP endif; ?>
	
<div class="modal fade" id="edgeBandingModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='edgeBandingModalBody'>
			</div>
		</div>
	</div>
</div>