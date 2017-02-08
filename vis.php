<?php
include "start.html";
?>


<div id="content">
	<div id="form-box">
		<form action="" method="POST">
			<div class="btn-group" >
				<label class="btn btn-default">
					<input onChange="HentTabell(this.value)" type="radio" value="student" name="tabell" autocomplete="off"> Student-tabell <br>
				</label>
				
				<label class="btn btn-default">
					<input onChange="HentTabell(this.value)" type="radio" value="klasse" name="tabell" autocomplete="off"> Klasse-tabell <br>
				</label>
				<label class="btn btn-default">
					<input onChange="HentTabell(this.value)" type="radio" value="bilde" name="tabell" autocomplete="off"> Bilde-tabell <br>
				</label>
			</div>
		</form>
	</div>
	<br>

	<div id="table">
	</div>

</div>


<?php
include "slutt.html";
?>