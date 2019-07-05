<?php
    include "kirjautumistarkastus.php";

    $nimi=$_POST['lomake_nimi'];
    echo "Nimi: ".$nimi."<br>";
    $luokka=$_POST['lomake_luokka'];
    echo "Luokka: ".$luokka."<br>";
    $salasana=$_POST['lomake_salasana'];
    echo "Salasana: ".$salasana."<br>";
    $oikeusryhma=$_POST['lomake_oikeusryhma'];
    echo "Oikeusryhmä: ".$oikeusryhma."<br>";
    echo "Vuosikerta: ".$kayttajavuosikerta."<br>";
    echo "Lisääjä: ".$kayttajanimi."<br>";

    $uusikayttajanimi = strtolower($nimi);
    $uusikayttajanimi = str_replace(' ', '', $uusikayttajanimi);
    echo "Käyttäjänimi: ".$kayttajanimi."<br>";
    
    $annettusalasana = $salasana;
    $salasana = password_hash($salasana, PASSWORD_DEFAULT);

    $kayttajaajo = $con->prepare("INSERT INTO kronikka_kayttajat (vuosikerta, luokka, nimi, kayttajanimi, salasana, oikeusryhma, lisaaja)
    VALUES (?,?,?,?,?,?,?)");
    if( $kayttajaajo &&
        $kayttajaajo->bind_param("issssss", $kayttajavuosikerta, $luokka, $nimi, $uusikayttajanimi, $salasana, $oikeusryhma, $kayttajanimi) &&
        $kayttajaajo->execute()
      ) {
        print "Uusi käyttäjä tallennettu: <br/>";
        print "Vuosikerta: ".$kayttajavuosikerta."<br/>";
        print "Luokka: ".$luokka."<br/>";
        print "Nimi: ".$nimi."<br/>";
        print "Käyttäjänimi: ".$uusikayttajanimi."<br/>";
        print "Salasana: ".$annettusalasana."<br/>";
        print "Oikeusryhmä: ".$oikeusryhma."<br/>";
        print "Lisääjä: ".$kayttajanimi."<br/>";
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $kayttajaajo->close();
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
        <form action="/uudenkayttajanlisaaminen.php">
         <button type="submit">Palaa takaisin lisäämään käyttäjiä </button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>