<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Aktualności</div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr>
				<td>Ostatnio zrealizowane zlecenie:</td>
				<td>
					<label><?PHP if($lastMadeOrder):?><?=$lastMadeOrder -> customerName?> <?=$lastMadeOrder -> customerSurname?><?=$lastMadeOrder -> customerTempName?> (<?=$lastMadeOrder -> document_number?>)<?PHP else: ?>Nie zrealizowano jeszcze żadnego zlecenia<?PHP endif; ?></label>
				</td>
			</tr>
			<tr>
				<td>Realizacja zleceń w terminie:</td>
				<td>
						<label><?PHP if($numberOfOrders): ?><?= (int)(100*$numberOfCompletedOrders/$numberOfOrders)?>%<?PHP else: ?>brak niezrealizowanych zleceń po terminie<?PHP endif;?></label>
				</td>
			</tr>
			<tr>
				<td>Obecne terminy:</td>
				<td>
					<div><label><?=$potentialOrderCompletionDates[1]?> (Piła1 / <?=$boardsAmount[1]?> szt.) </label></div>
					<div><label><?=$potentialOrderCompletionDates[2]?> (Piła2 / <?=$boardsAmount[2]?> szt.)</label></div>
				</td>
			</tr>
		</table>
	</div>
</div>
