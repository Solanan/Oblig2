<?php
include ("start.html");

    //print_r($_FILES);
    $dir = "bilder/";
    $fil = $dir . basename($_FILES["fil"]["name"]);
    $filtype = pathinfo($fil , PATHINFO_EXTENSION);
    $filtype = strtolower($filtype);
    $stoerrelse = getimagesize($_FILES["fil"]["tmp_name"]);
    //print ("<br>Bildefilen er:" . $stoerrelse["mime"] . "<br>");
    $godkjent = true;
    $beskrivelse = $_POST["beskrivelse"];
    //START VALIDERING
    if (!$beskrivelse)
    {
        print ("Du må legge ved en beskrivelse av bildet! <br>");
        $godkjent = false;
    }

    if (file_exists($fil)) 
    {
        print ("Filnavnet eksisterer allerede!<br>");
        $godkjent = false;
    }
    if ($_FILES["fil"]["size"] > 100000)
    {
        print ("Filen er for stor!<br>");
        $godkjent = false;

    }
    if($stoerrelse == false)
    {
        print ("Filen er ikke ett bilde! <br>");
        $godkjent = false;

    }
    if($filtype != "jpg" && $filtype != "png" && $filtype != "jpeg"
&& $filtype != "gif" ) {
    echo "Kun JPG, JPEG, PNG & GIF filer er tillatt<br>";
    $godkjent = false;
}

//SLUTT VALIDERING

    if($godkjent)
    {
        if(move_uploaded_file($_FILES["fil"]["tmp_name"], $fil))
        {
            print ("Bildet: " .  $_FILES['fil']['name'] . "er lastet opp!<br>");
            try 
                {
                    include ("database.php");
                    $dato = date('Ymd');
                    $db = new DBConn();
					$db->RunQuery("INSERT INTO bilde(opplastings_dato,filnavn,beskrivelse) VALUES('$dato','$fil','$beskrivelse')");
					echo ("Lagring vellykket.<br>");
                }
			catch(Exception $e)
                {
                    echo ($e->getMessage());
                }

        }
        else
        {
            print ("Noe gikk galt under opplasting");

        }
    }
    else
    {
        print("Opplastingsvalgene dine er ikke korrekte");
    }

    include ("slutt.html");
    ?>