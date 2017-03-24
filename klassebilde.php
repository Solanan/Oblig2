<?php
include "start.html";
include "database.php";
$db = new DBConn;
$optionmenu = $db->CreateOptionMenu('klasse','klassekode');

?>


<div id="content">
	<div id="form-box">
		<form action="" method="POST">
            <?php print($optionmenu); ?>
            <input type="submit" name="submit" value="Vis Klasseliste" class="btn btn-default">
		</form>
	</div>
	<br>

	<div id="table">
        <?php 
            if (isset ($_POST["submit"]))
                {
                    VisKlasseBilder();
                } 
        ?>
	</div>

</div>


<?php
include "slutt.html";
function VisKlasseBilder()
{
    $db = $GLOBALS['db'];
    /*print("Hello World");*/
    $klassekode=$_POST['klasseKodeVelger'];
    /*print($klassekode);*/
    $klassebilder = $db->RunQuery("SELECT * FROM oblig2_bilder");
    $result = json_decode($db->GetLastResultInJSON(),true);
    /*print_r($result);*/
    $table = '<div class="col-xs-12">';
    $table .= '<p>Viser klassebilde for klasse:' . $klassekode . '</p>';
    $table .= '</div>';
    $table .='<div id="klassebilde" class="row">';
    foreach ($result as $bilde)
    {
    $table .= '<div class="col-xs-4">';
    $table .= '<div class="row">';
    $table .= '<div class="col-xs-12">';
    $table .= '<img class="img-responsive" src=' . $bilde["filnavn"] . '></img>';
    $table .= '</div>';
    $table .= '<div class="col-xs-12">';
    $table .= '<p>' . $bilde["beskrivelse"] . '</p>';
    $table .= '</div>';
    $table .= '</div>';
    $table .= '</div>';
    }
    $table .= '</div>';
    echo ($table);
    /*echo $db->CreateResultTable();*/
}
?>