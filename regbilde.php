<?php
include "start.html";
?>




<div id="form-box">
	
	<form action="lastoppbilde.php" method="POST" id="bildeopplasting" name="bildeopplasting" enctype="multipart/form-data">
		
        <div class="form-group">
            <label> Beskrivelse <input class="form-control" type="text" id="beskrivelse" name="beskrivelse"> </label>
        </div>

		<div class="form-group">
                <label for="bildeopplasting"> Velg bilde... </label>
                    <input type="file" name="fil" id="bildeopplasting">
                </label> 
		</div>

		<input type="submit" class="btn btn-success" name="submit" value="Lagre"> Last opp </button>
		
	</form>
</div>






<?php		
include "slutt.html";
?>