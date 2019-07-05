<?php
    include "kirjautumistarkastus.php";
    $sahkoposti=$_POST['lomake_sahkoposti'];
    $sahkoposti = mysqli_real_escape_string($con,$sahkoposti);
    if($sahkoposti !== NULL) {
    $salasanaajo3 = $con->prepare("UPDATE kronikka_kayttajat SET sahkoposti = ? WHERE kayttajanimi = ?");
    if( $salasanaajo3 &&
        $salasanaajo3->bind_param("ss", $sahkoposti, $kayttajanimi) &&
        $salasanaajo3->execute()
      ) {
        print "Sähköposti päivitetty.<br/>";
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