<?php
include "start.html";
?>
	<form action="" method="POST">
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<br>
				<div class="form-group" id="form-box">
						<select class="form-control custom-select" onchange="onTabellVelgerChange()" id="tabellVelger" onchange="OnChange_tabellVelger()">
						<option value="student"> Student </option>
						<option value="klasse"> Klasse </option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="form-group">
				<label>
					Søkestreng <input type="text" id="keyword" name="keyword" class="form-control" placeholder=" * = velg alt fra tabellen" onkeyup="DoSearch(event)">   
				</label>
		</div>
	</form>

<div id="table">
</div>


<?php
include "slutt.html";
?>