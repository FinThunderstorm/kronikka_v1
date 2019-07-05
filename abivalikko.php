<?php
    include "config.php";
    $luokka = $_GET['q'];
    $luokka = mysqli_real_escape_string($con,$luokka);
    echo 'Abiturientti: <select name="abi" id="abi" required>';
    echo '           <option value="">Valitse '.$luokka.'-luokasta</option>';
    //abihaku
    $abihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
    if( $abihaku &&
        $abihaku->bind_param("s", $luokka) &&
        $abihaku->execute() &&
        $abihakutulos = $abihaku->get_result()
      ) {
        foreach ($abihakutulos as $row) {
            $abi = $row['kayttajanimi'];
            $abi = mysqli_real_escape_string($con,$abi);
            $abinimi = $row['nimi'];
            $abinimi = mysqli_real_escape_string($con,$abinimi);
            echo "<option value=".$abi.">" . $abinimi . "</option>";
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $abihaku->close();
    echo '</select><br> ';
?>

