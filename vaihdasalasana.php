<?php
    include "kirjautumistarkastus.php";
    $salasana=$_POST['lomake_salasana'];
    $salasana = mysqli_real_escape_string($con,$salasana);
    $sahkoposti=$_POST['lomake_sahkoposti'];
    $sahkoposti = mysqli_real_escape_string($con,$sahkoposti);
    
    $hashsalasana = password_hash($salasana, PASSWORD_DEFAULT);
    $hashsalasana = mysqli_real_escape_string($con,$hashsalasana);
    $ekakirjautuminen = 1;

    $salasanaajo = $con->prepare("UPDATE kronikka_kayttajat SET salasana = ? WHERE kayttajanimi = ?");
    if( $salasanaajo &&
        $salasanaajo->bind_param("ss", $hashsalasana, $kayttajanimi) &&
        $salasanaajo->execute()
      ) {
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $salasanaajo->close();
    
    $salasanaajo2 = $con->prepare("UPDATE kronikka_kayttajat SET ekakirjautuminen = ? WHERE kayttajanimi = ?");
    if( $salasanaajo2 &&
        $salasanaajo2->bind_param("is", $ekakirjautuminen, $kayttajanimi) &&
        $salasanaajo2->execute()
      ) {
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $salasanaajo2->close();

    if($sahkoposti !== NULL) {
    $salasanaajo3 = $con->prepare("UPDATE kronikka_kayttajat SET sahkoposti = ? WHERE kayttajanimi = ?");
    if( $salasanaajo3 &&
        $salasanaajo3->bind_param("ss", $sahkoposti, $kayttajanimi) &&
        $salasanaajo3->execute()
      ) {
        echo "Onnistui.";
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $salasanaajo3->close();
    }

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
        <form action="/home.php">
         <button type="submit">Siirry palveluun </button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>