<?php
include "start.html";
?>

<div id="form-box">
	
	<form id="regstudent" name="regstudent">
		
		<div class="from-group">

			<label> Fornavn <input class="form-control" type="text" id="fornavn" name="fornavn" onfocus="GiveUserFeedBack('','BLANK')" required > </label>
			
		</div><br>
		
		<div class="from-group">
			<label> Etternavn  <input class="form-control" type="text" id="etternavn" name="etternavn" onfocus="GiveUserFeedBack('','BLANK')" required > </label> 
			
		</div><br>
			
		<div class="from-group">
			<label> Klassekode 
				<div id="klasseKodeVelger">
					<p> Henter klassekoder... </p>
				</div>
					<!-- <input class="form-control" type="text" id="klassekode" name="klassekode" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> -->
					<script> 
						HentKlasseKodeVelger("klasseKodeVelger");
					</script>
				</label>
		</div>

		
		<br>

		<div class="from-group">
			<label> Bilde 
				<div id="bildeNrVelger">
					<p> Henter bildebeskrivelser... </p>
				</div>
					<!-- <input class="form-control" type="text" id="klassekode" name="klassekode" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> -->
					<script> 
						HentBildeVelger("bildeNrVelger");
					</script>
				</label>
		</div>

		<br>
			
		<div class="from-group">

			<label> Brukernavn <input class="form-control" type="text" id="brukernavn" name="brukernavn" onfocus="GiveUserFeedBack('','BLANK')" onkeyup="KeyUp(this.value, this.id, 'regstudent')" required > </label>
		</div>
		<br>
			
			
		<button type="button" class="btn btn-success" value="Lagre" onclick="SubmitStudent()"> Lagre </button>
		<button class="btn btn-warning" type="button" value="Reset" onclick="ResetForm(this)"> Slett skjemadata </button>
		
	</form>
</div>
	
<?php		
include "slutt.html";
?>