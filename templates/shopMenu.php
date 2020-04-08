<?php if(!isset($joinery)) die();?>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-dashboard"></span> Zlecenia <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showOrderAddingForm"><span class="glyphicon glyphicon-plus"></span> Nowe</a></li>
		<li><a href="index.php?action=showOrderSearchingForm"><span class="glyphicon glyphicon-search"></span> Znajdź</a></li>
		<li><a href="index.php?action=showLastMadeOrders"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> Zrealizowane</a></li>
		<li><a href="index.php?action=showOrderList"><span class="glyphicon glyphicon-tasks"></span> Stan</a></li>
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Klienci <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showCustomerAddingForm"><span class="glyphicon glyphicon-plus"></span> Dodaj</a></li>
		<li><a href="index.php?action=showCustomerSearchingForm"><span class="glyphicon glyphicon-search"></span> Znajdź</a></li>
		<li><a href="index.php?action=showCustomersList"><span class="glyphicon glyphicon-th-list"></span> Lista</a></li>
		<li><a href="index.php?action=showRemovedCustomersList"><span class="glyphicon glyphicon-ban-circle"></span> Usunięci</a></li>
	</ul>
</li>