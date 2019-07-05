<?php
    include "kirjautumistarkastus.php";

    $viestiid=$_POST['viestiid'];
    echo "viestiid: ".$viestiid."<br>";
    $syy=$_POST['syy'];
    $syy = mysqli_real_escape_string($con,$syy);
    echo "syy: ".$syy."<br>";

    $viestihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE id = ?");
    if( $viestihaku &&
        $viestihaku->bind_param("i", $viestiid) &&
        $viestihaku->execute() &&
        $viestihakutulos = $viestihaku->get_result()
      ) {
        foreach ($viestihakutulos as $row) {
            $muokkaamatonkommentti = $row['kommentti'];
            $muokkaamatonkommentti = mysqli_real_escape_string($con,$muokkaamatonkommentti);
            $kommentinlisaaja = $row['lisaaja'];
            $kommentinlisaaja = mysqli_real_escape_string($con,$kommentinlisaaja);
        }

    } else {
        echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $viestihaku->close();
    echo "Poistettava kommentti: ".$muokkaamatonkommentti."<br>";
    echo "Kommentin lisääjä: ".$kommentinlisaaja."<br>";
    
    $poistoajo = $con->prepare("DELETE FROM kronikka_kommentit WHERE id = ?");
    if( $poistoajo &&
        $poistoajo->bind_param("i", $viestiid) &&
        $poistoajo->execute()
      ) {
        print "Kommentin poisto tallennettu.<br/>";
    } else {
        echo "2Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $poistoajo->close();

    $tapa = 'poisto';
    $kommentti = '- poistettu -';
    $poistotietoajo = $con->prepare("INSERT INTO kronikka_muokatutkommentit (muokatunkommentinid, tapa, muokkaamatonkommentti, muokattukommentti, kommentinlisaaja, muokkaaja, syy, vuosikerta)
    VALUES (?,?,?,?,?,?,?,?)");
    if( $poistotietoajo &&
        $poistotietoajo->bind_param("issssssi", $viestiid, $tapa, $muokkaamatonkommentti, $kommentti, $kommentinlisaaja, $kayttajanimi, $syy, $kayttajavuosikerta) &&
        $poistotietoajo->execute()
      ) {
        print "Poistettujen kommenttien tietokantaan tallennettu.<br/>";
    } else {
        echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $poistotietoajo->close();
?>
<!DOCTYPE HTML html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <br>
        <form action="/hallinta.php">
         <button type="submit">Palaa takaisin kommenttien hallintaan </button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>