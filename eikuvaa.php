<?php
    include "kirjautumistarkastus.php";

    $kuvatilanne = $_POST['kuva'];
    //TARKASTETAAN, ONKO KUVA JO OLEMASSA?
        $kuvaolemassahaku = $con->prepare("SELECT * FROM kronikka_profiilikuvat WHERE lisaaja = ?");
        if( $kuvaolemassahaku &&
            $kuvaolemassahaku->bind_param("s", $kayttajanimi) &&
            $kuvaolemassahaku->execute() &&
            $kuvaolemassahakutulos = $kuvaolemassahaku->get_result()
          ) {
                foreach ($kuvaolemassahakutulos as $row) {
                    $lisaaja = $row['lisaaja'];
                    $lisaaja = mysqli_real_escape_string($con, $lisaaja);
                    $profiilikuvanimi = $row['profiilikuvanimi'];
                    $profiilikuvanimi = mysqli_real_escape_string($con, $profiilikuvanimi);
                    $kuvanumero = $row['id'];
                    $kuvavuosikerta = $row['vuosikerta'];
                }}
                if($profiilikuvanimi !== NULL){
                
                $vanhakuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                $poistoajo = $con->prepare("DELETE FROM kronikka_profiilikuvat WHERE id = ?");
                if(unlink($vanhakuvasijainti)){
                    if( $poistoajo &&
                        $poistoajo->bind_param("i", $kuvanumero) &&
                        $poistoajo->execute()
                      ) {
                    }} else if ( $poistoajo &&
                        $poistoajo->bind_param("i", $kuvanumero) &&
                        $poistoajo->execute()
                      ) {
                    }
                    $poistoajo->close();
                }
            
        $kuvaolemassahaku->close();

        $ei = "ei";
        $eikuva = "eikuvaa.png";
        if($kuvatilanne == $ei){
            //Ajetaan tieto uudesta kuvanimestä tietokantaan
            $kuvatilanneajo = $con->prepare("INSERT INTO kronikka_profiilikuvat (vuosikerta, lisaaja, profiilikuvanimi)
            VALUES (?,?,?)");
            if( $kuvatilanneajo &&
            $kuvatilanneajo->bind_param("iss", $kayttajavuosikerta, $kayttajanimi, $eikuva) &&
            $kuvatilanneajo->execute()
            ) {
                echo "<br />Valintasi on tallennettu<br/>";
                echo '';
            }}
            else {
                echo "virhe...4";
            }
            $kuvatilanneajo->close();

        $wilma = "wilma";
        if($kuvatilanne == $wilma){
            //Ajetaan tieto uudesta kuvanimestä tietokantaan
            $kuvatilanneajo = $con->prepare("DELETE FROM kronikka_profiilikuvat WHERE id = ?");
            if( $kuvatilanneajo &&
            $kuvatilanneajo->bind_param("i", $kuvanumero) &&
            $kuvatilanneajo->execute()
            ) {
                echo "<br />Valintasi on tallennettu<br/>";
                echo '';
            }}
            else {
                echo "virhe...4";
            }
            $kuvatilanneajo->close();
?>
<!DOCTYPE HTML html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1>
        <form action="/home.php">
            <button type="submit">Palaa alkuun</button>
        </form>
    </body>
</html>
