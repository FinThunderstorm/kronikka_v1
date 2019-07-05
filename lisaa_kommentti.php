<?php
include "kirjautumistarkastus.php";
    $luokka=$_POST['luokka'];
    $luokka=mysqli_real_escape_string($con, $luokka);
    $abi=$_POST['abi'];
    $abi=mysqli_real_escape_string($con, $abi);
    $kommentti=$_POST['kommentti'];
    $kommentti=mysqli_real_escape_string($con, $kommentti);

    $abihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
    if( $abihaku &&
        $abihaku->bind_param("s", $abi) &&
        $abihaku->execute() &&
        $abihakutulos = $abihaku->get_result()
      ) {
        foreach ($abihakutulos as $row) {
            $abinimi = $row['nimi'];
            $abinimi = mysqli_real_escape_string($con,$abinimi);
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $abihaku->close();
    
    $kommenttiajo = $con->prepare("INSERT INTO kronikka_kommentit (luokka, abi, kommentti, lisaaja, vuosikerta)
    VALUES (?,?,?,?,?)");
        if( $kommenttiajo &&
            $kommenttiajo->bind_param("ssssi", $luokka, $abi, $kommentti, $kayttajanimi, $kayttajavuosikerta) &&
            $kommenttiajo->execute()
          ) {
            print "Kommenttisi on lisätty:<br/>";
            print "Luokka: ".$luokka."<br/>";
            print "Abi: ".$abinimi."<br/>";
            print "Kommenttisi: ".$kommentti."<br/>";
            print "Vuosikerta: ".$kayttajavuosikerta."<br />";
        } else {
            echo "Tapahtui odottamaton virhe, yritä uudelleen...";
        }
        $kommenttiajo->close();
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
        Haluatko lisätä seuraavan kommentin? 
        <form action="/kommentointi.php">
         <button type="submit">Lisää seuraava</button>
        </form><br>
        <form action="/home.php">
         <button type="submit">Älä lisää, palaa alkuun</button>
        </form>
    </body>

</html>
<?php
include "footer.php";
?>

