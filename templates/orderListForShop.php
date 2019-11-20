<div id="tableContainer" style="display:none;">
	<section>
		<div class="tableHead">
			Przychody
		</div>
		<table class="balanceTable">
			<?PHP
				if ($chosenIncomes): 
					$incomeCategoryId  = $chosenIncomes[0]->categoryId;
					?>
			<tr style="cursor: pointer;" onclick="showClass('i<?=$incomeCategoryId?>');">
				<td colspan="2"><?=$chosenIncomes[0]->categoryName?></td>
				<td class="text-right"><?=number_format($incomeCategorySum[$chosenIncomes[0]->categoryName], 2,'.',' ')?></td>
			</tr>
					<?PHP
					foreach ($chosenIncomes as $income):
						if($incomeCategoryId != $income->categoryId):
							$incomeCategoryId = $income->categoryId; ?>
			<tr style="cursor: pointer;" onclick="showClass('i<?=$incomeCategoryId?>');">
				<td colspan="2"><?=$income->categoryName?></td>
				<td class="text-right"><?=number_format($incomeCategorySum[$income->categoryName], 2,'.',' ')?></td>
			</tr>
						<?PHP endif; ?>
				<tr data-toggle="tooltip" title="<?=$income->comment?>" class="i<?=$income->categoryId?>" id="i<?=$income->incomeId?>s" style="display:none; font-size: 14px; color:#ccc;" onclick="showEditButtons('i<?=$income->incomeId?>');">
					<td class="text-center">
						<div style="display:none;" id="i<?=$income->incomeId?>+<?=$income->categoryId?>comment"><?=$income->comment?></div>
						<div style="padding-left: 5%;" id="i<?=$income->incomeId?>+<?=$income->categoryId?>date"><?=$income->incomeDate?></div>
					</td>
					<td class="text-right" style="padding-left: 5%;" >
						<div id="i<?=$income->incomeId?>+<?=$income->categoryId?>amount"><?=number_format($income->amount, 2,'.',' ')?></div>
					</td>
					<td style="text-align: right;">
						<div style="display:inline;" class="noMargin noPadding text-right">
							<span data-toggle="tooltip" title="Edytuj" onclick="showEditForm('i<?=$income->incomeId?>+<?=$income->categoryId?>');" class='glyphicon glyphicon-pencil balance-icon'></span>
							<span style="color: #bb4411;"  data-toggle="tooltip" title="Usuń" onclick="removeItem('i<?=$income->incomeId?>')" class='glyphicon glyphicon-remove balance-icon'></span>
						</div>
					</td>
				</tr>
					<?PHP endforeach; ?>
			<tr>
				<td>Razem</td><td class="text-right" colspan="2"><?=number_format($allIncomesSum, 2,'.',' ')?></td>
			</tr>
			<?PHP else: ?>
			<div class="text-center option" style="margin:15px;">Nie masz dodanych żadnych przychodów w tym okresie!</div>
			<?PHP endif; ?>
		</table>
	</section>
	<section>
		<div class="tableHead">
			Wydatki
		</div>
		<table class="balanceTable">
			<?PHP
				if ($chosenExpenses): 	
					$expenseCategoryId  = $chosenExpenses[0]->categoryId;
					?>
			<tr style="cursor: pointer;" onclick="showClass('e<?=$expenseCategoryId?>');">
				<td colspan="2"><?=$chosenExpenses[0]->categoryName?></td>
				<td colspan="2" class="text-right"><?=number_format($expenseCategorySum[$chosenExpenses[0]->categoryName], 2,'.',' ')?></td>
			</tr>
					<?PHP
					foreach ($chosenExpenses as $expense):
						if($expenseCategoryId != $expense->categoryId):
							$expenseCategoryId = $expense->categoryId; ?>
			<tr style="cursor: pointer;" onclick="showClass('e<?=$expenseCategoryId?>');">
				<td colspan="2"><?=$expense->categoryName?></td>
				<td colspan="2" class="text-right"><?=number_format($expenseCategorySum[$expense->categoryName], 2,'.',' ')?></td>
			</tr>
				<?PHP endif; ?>
				<tr data-toggle="tooltip" title="<?=$expense->comment?>" class="e<?=$expense->categoryId?>" id="e<?=$expense->expenseId?>&<?=$expense->paymentMethodId?>s" style="display:none; font-size: 14px; color:#ccc;" onclick="showEditButtons('e<?=$expense->expenseId?>&<?=$expense->paymentMethodId?>');">
					<td class="text-center">
						<div style="display:none;" id="e<?=$expense->expenseId?>+<?=$expense->categoryId?>&<?=$expense->paymentMethodId?>comment"><?=$expense->comment?></div>
						<div id="e<?=$expense->expenseId?>+<?=$expense->categoryId?>&<?=$expense->paymentMethodId?>date"><?=$expense->expenseDate?></div>
					</td>
					<td class="text-center">
						<div id="e<?=$expense->expenseId?>+<?=$expense->categoryId?>&<?=$expense->paymentMethodId?>paymentMethod"><?=$expense->paymentMethodName?></div>
					</td>
					<td class="text-right">
						<div id="e<?=$expense->expenseId?>+<?=$expense->categoryId?>&<?=$expense->paymentMethodId?>amount"><?=number_format($expense->amount, 2,'.',' ')?></div>
					</td>
					<td style="text-align: right;">
						<div style="display:inline;" class="noMargin noPadding text-right">
							<span data-toggle="tooltip" title="Edytuj" onclick="showEditForm('e<?=$expense->expenseId?>+<?=$expense->categoryId?>&<?=$expense->paymentMethodId?>');"  class='glyphicon glyphicon-pencil balance-icon'></span>
							<span style="color: #bb4411;"  data-toggle="tooltip" title="Usuń" onclick="removeItem('e<?=$expense->expenseId?>')" class='glyphicon glyphicon-remove balance-icon'></span>
						</div>
					</td>
				</tr>
				<tr id="e<?=$expense->expenseId?>&<?=$expense->paymentMethodId?>"></tr>
					<?PHP endforeach; ?>
			<tr>
				<td colspan="2">Razem</td><td class="text-right" colspan="2"><?=number_format($allExpensesSum, 2,'.',' ')?></td>
			</tr>
		</table>
		<table class="balanceTable"><div class="text-center"><div id="piechart"></div></div>
			<?PHP else:?>
			<div class="text-center option" style="margin:15px;">Nie masz dodanych żadnych przychodów w tym okresie!</div>
			<?PHP endif; ?>
		</table>
	</section>
	<section>
		<div class="tableHead">
			Bilans
		</div>
		<div class="balanceTable">
			<div class="balance">
					<?PHP $balance = $allIncomesSum - $allExpensesSum; ?>
				<div class="text-center"><?=number_format($balance, 2,'.',' ')?> PLN
					<?PHP if($balance > 0): ?>
					<div class="depiction">Gratulacje. Świetnie zarządzasz finansami!</div>
					<?PHP elseif($balance < 0): ?>
					<div class="depiction">Uważaj, wpadasz w długi!</div>
					<?PHP endif; ?>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" id="editFormModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="tableHead noMargin">
				Edycja danych:
			</div>
			<div style="color:white; margin-top: 15px;" id="editFormModalBody"></div>
		</div>
	</div>
	<div class="modal fade" id="modal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="settingsModal">
				<div id="modalBody"></div>
			</div>
		</div>
	</div>
</div>	