<?php
    include "kirjautumistarkastus.php";

    $kommentti=$_POST['kommentti'];
    $kommentti = mysqli_real_escape_string($con,$kommentti);
    echo "muokattu kommentti: ".$kommentti."<br>";
    $viestiid=$_POST['viestiid'];
    $viestiid = mysqli_real_escape_string($con,$viestiid);
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
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $viestihaku->close();
    echo "Muokkaamaton kommentti: ".$muokkaamatonkommentti."<br>";
    echo "Kommentin lisääjä: ".$kommentinlisaaja."<br>";
    
    $muokkausajo = $con->prepare("UPDATE kronikka_kommentit SET kommentti = ? WHERE id = ?");
    if( $muokkausajo &&
        $muokkausajo->bind_param("si", $kommentti ,$viestiid) &&
        $muokkausajo->execute()
      ) {
        print "Kommentin muokkaus tallennettu.<br/>";
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $muokkausajo->close();

    $tapa = 'muokkaus';
    $muokkaustietoajo = $con->prepare("INSERT INTO kronikka_muokatutkommentit (muokatunkommentinid, tapa, muokkaamatonkommentti, muokattukommentti, kommentinlisaaja, muokkaaja, syy, vuosikerta)
    VALUES (?,?,?,?,?,?,?,?)");
    if( $muokkaustietoajo &&
        $muokkaustietoajo->bind_param("issssssi", $viestiid, $tapa, $muokkaamatonkommentti, $kommentti, $kommentinlisaaja, $kayttajanimi, $syy, $kayttajavuosikerta) &&
        $muokkaustietoajo->execute()
      ) {
        print "Muokattujen kommenttien tietokantaan tallennettu.<br/>";
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $muokkaustietoajo->close();
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

