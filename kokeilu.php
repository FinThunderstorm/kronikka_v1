<?php

include "kirjautumistarkastus.php";
$syotetty = "juusto123";

$abihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE id NOT IN (1,9,10,11,12,13,14,15,16) ");
if( $abihaku &&
    $abihaku->execute() &&
    $abihakutulos = $abihaku->get_result()
  ) {
    foreach ($abihakutulos as $row) {
        $abi = $row['kayttajanimi'];
        $abi = mysqli_real_escape_string($con,$abi);
        $salasana = $row['salasana'];
        $salasana = mysqli_real_escape_string($con,$salasana);
        echo "virhe: ".$abi." -> ";
        $uusihash = password_hash($syotetty, PASSWORD_DEFAULT);

        $salasanaajo = $con->prepare("UPDATE kronikka_kayttajat SET salasana = ? WHERE kayttajanimi = ?");
        if( $salasanaajo &&
            $salasanaajo->bind_param("ss", $uusihash, $abi) &&
            $salasanaajo->execute()
          ) {
            print "Salasana päivitetty.<br/>";
        } else {
            echo "Tapahtui odottamaton virhe, yritä uudelleen...";
        }
        $salasanaajo->close();
        
    }

} else {
    echo "Tapahtui odottamaton virhe, yritä uudelleen...";
}
$abihaku->close();

