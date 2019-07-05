<?php
include "config.php";

if(isset($_POST['but_submit'])){
    
    $sahkoposti = $_POST['sahkoposti'];
    $sahkoposti = mysqli_real_escape_string($con,$sahkoposti);
    $kirjautumishaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE sahkoposti=?");
    if( $kirjautumishaku &&
        $kirjautumishaku->bind_param("s", $sahkoposti) &&
        $kirjautumishaku->execute() &&
        $kirjautumishakutulos = $kirjautumishaku->get_result()
        ) {
            foreach ($kirjautumishakutulos as $row) {
                $kayttajanimi = $row['kayttajanimi'];
                $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
            }
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $hash = password_hash($kayttajanimi, PASSWORD_DEFAULT);
    $hash = mysqli_real_escape_string($con,$hash);

    $aihe = 'Salasanan palautus';
    $viesti = 'Olet pyytänyt itsellesi uutta salasanaa Klassikan Kronikka-palveluun. Ole hyvä, ja käytä alla olevaa linkkiä asettaaksesi itsellesi uuden salasanan. <a href="http://kronikka.klassikka.ovh/palautetunsalasananvaihtaminen.php?kt='.$kayttajanimi.'&h='.$hash.'">Klikkaa tästä palauttaaksesi salasanasi</a><br><br>Terveisin, Klassikan Kronikkatiimi';
    $headers = "From: Klassikan Kronikkatiimi <kronikka@klassikka.ovh>\r\n";
    $headers .= "Reply-To: kronikka@klassikka.ovh\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    mail($sahkoposti, $aihe, $viesti, $headers);
    $kirjautumishaku->close();
    echo "Salasanasi resetointipyyntö on lähetetty sähköpostiisi.";
    
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
        <p>Oletko unohtanut salasanasi?</p>
        <form method="post" action="">
            <input name="sahkoposti" placeholder="Sähköpostiosoitteesi" required /><br>
            <button type="submit" name="but_submit" id="but_submit">Lähetä</button>
        </form><br>
        <form action="index.php">
            <button type="submit">Palaa takaisin kirjautumiseen</button>
        </form>
    </body>
</html>