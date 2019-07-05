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
        <p>Kommenttien hallinta</p>
        <?php
            echo '<table>';
            echo '<td>Lisäysaika</td>';
            echo '<td>Lisääjä</td>';
            echo '<td>Abiturientti</td>';
            echo '<td>Kommentti</td>';
        
            //abihaku
            $kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE vuosikerta = ? ORDER BY id DESC");
            if( $kommenttihaku &&
                $kommenttihaku->bind_param("s", $kayttajavuosikerta) &&
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
                    echo "<td>".$lisaajaluokka ." ". $lisaajanimi . "</td>";
                    echo "<td>" . $luokka ." ". $abinimi . "</td>";
                    echo "<td>" . $kommentti . "</td>";
                    echo '<td><form action="/muokkaakommenttia.php" method="post">
                            <button type="submit" name="viestiid" value="'.$viestiid.'">Muokkaa</button>
                            </form></td>';
                    echo '<td><form action="/poistakommentti.php" method="post">
                            <button type="submit" name="viestiid" value="'.$viestiid.'">Poista</button>
                            </form></td>';
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