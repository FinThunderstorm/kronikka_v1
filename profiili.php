<?php
include "kirjautumistarkastus.php";

?>
<!doctype html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1>
        <?php
            include "kayttajastatus.php";
        ?>
            <form action="/salasananvaihtaminen.php">
            <button type="submit">Vaihda salasana</button>
            </form><br>
            <form action="/sahkopostinvaihtaminen.php">
            <button type="submit">Vaihda sahkoposti</button>
            </form><br><br>
            
            <div style="border: 3px solid red; padding: 5px;">
            <p>Etkö halua omaa kuvaa kronikkaan? Vahvista valintasi alla. Voit vielä muuttaa valintaasi tämän jälkeen lataamalla oman profiilikuvan tai valitsemalla Wilma-kuva-vaihtoehdon. Oletuksena sinulla on Wilma-kuva. Tämä Wilma-kuva nappula palauttaa sen käyttöön ainoastaan silloin, kun oma kuva tai ei kuvaa on määritetty asetukseksi.</p>
            <form method="post" action="/eikuvaa.php">
                <input type="radio" name="kuva" value="ei"> Ei kuvaa<br>
                <input type="radio" name="kuva" value="wilma"> Wilma-kuva<br>
                <button type="submit" name="eikuvaa">Tallenna</button>
            </form><br></div>
        
    <?php   $profiilikuvahaku = $con->prepare("SELECT * FROM `kronikka_profiilikuvat` WHERE lisaaja = ?");
            if( $profiilikuvahaku &&
                $profiilikuvahaku->bind_param("s", $kayttajanimi) &&
                $profiilikuvahaku->execute() &&
                $profiilikuvahakutulos = $profiilikuvahaku->get_result()
              ) {
                foreach ($profiilikuvahakutulos as $row) {
                    $profiilikuvanimi = $row['profiilikuvanimi'];
                    $profiilikuvanimi = mysqli_real_escape_string($con,$profiilikuvanimi);
                }
                echo "Määritetty profiilikuvasi: <br />";
                $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                echo '<img src="'.$kuvasijainti.'" width="40%">';
            } else {
                echo "Tapahtui odottamaton virhe, yritä uudelleen...";
            }
            $profiilikuvahaku->close();

            echo "<br><p>Sinun profiilisi kommentit: <br>";
             echo '<table>';
            echo '<td>Lisäysaika</td>';
            echo '<td>Abiturientti</td>';
            echo '<td>Kommentti</td>';
        
            $kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE abi = ? ORDER BY id DESC");
            if( $kommenttihaku &&
                $kommenttihaku->bind_param("s", $kayttajanimi) &&
                $kommenttihaku->execute() &&
                $kommenttihakutulos = $kommenttihaku->get_result()
              ) {
                foreach ($kommenttihakutulos as $row) {
                    $lisaaja = $row['lisaaja'];
                    $lisaaja = mysqli_real_escape_string($con,$lisaaja);
                    $abi = $row['abi'];
                    $abi = mysqli_real_escape_string($con,$abi);
                    $kommentti = $row['kommentti'];
                    $kommentti = mysqli_real_escape_string($con,$kommentti);
                    $luokka = $row['luokka'];
                    $luokka = mysqli_real_escape_string($con,$luokka);
                    $lisaysaika = $row['lisaysaika'];
                    $lisaysaika = mysqli_real_escape_string($con,$lisaysaika);
                    $viestiid = $row['id'];
                    $viestiid = mysqli_real_escape_string($con,$viestiid);
                    
                    $lisaajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
                    if( $lisaajahaku &&
                        $lisaajahaku->bind_param("s", $lisaaja) &&
                        $lisaajahaku->execute() &&
                        $lisaajahakutulos = $lisaajahaku->get_result()
                      ) {
                        foreach ($lisaajahakutulos as $row) {
                            $lisaajanimi = $row['nimi'];
                            $lisaajanimi = mysqli_real_escape_string($con,$lisaajanimi);
                            $lisaajaluokka = $row['luokka'];
                            $lisaajaluokka = mysqli_real_escape_string($con,$lisaajaluokka);
                        }

                    } else {
                        echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
                    }
                    $lisaajahaku->close();
                    
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
                    if($abinimi == NULL){
                        $abinimi = $abi;
                    }
                    } else {
                        echo "2Tapahtui odottamaton virhe, yritä uudelleen...";
                    }
                    $abihaku->close();  
                    
                    echo "<tr>";
                    echo "<td>" . $lisaysaika . "</td>";
                    echo "<td>" . $luokka ." ". $abinimi . "</td>";
                    echo "<td>" . $kommentti . "</td>";
                    echo "</tr>";
                }

            } else {
                echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
            }
            $kommenttihaku->close();
            echo '</table>';     
        
        
        
        
        ?>
        
        
        
    </body>
</html>
<?php
include "footer.php";
?>