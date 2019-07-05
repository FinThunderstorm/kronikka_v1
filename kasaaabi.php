<?php
include "kirjautumistarkastus.php";
$kayttajanimi = $_GET['kayttajanimi'];
$palautusarray = array($kayttajanimi);

$abinimihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
if( $abinimihaku &&
    $abinimihaku->bind_param("s", $kayttajanimi) &&
    $abinimihaku->execute() &&
    $abinimihakutulos = $abinimihaku->get_result()
  ) {
    
    foreach ($abinimihakutulos as $row) {
        $abinimi = $row['nimi'];
        $abinimi = mysqli_real_escape_string($con,$abinimi);
        array_push($palautusarray,$abinimi);
    }
} else {
    echo "2Tapahtui odottamaton virhe, yritä uudelleen...";
}
$abinimihaku->close();

$profiilikuvanimi = "";
$profiilikuvahaku = $con->prepare("SELECT * FROM `kronikka_profiilikuvat` WHERE lisaaja = ?");
if( $profiilikuvahaku &&
    $profiilikuvahaku->bind_param("s", $kayttajanimi) &&
    $profiilikuvahaku->execute() &&
    $profiilikuvahakutulos = $profiilikuvahaku->get_result()
  ) {
    foreach ($profiilikuvahakutulos as $row) {
        $profiilikuvanimi = $row['profiilikuvanimi'];
        $profiilikuvanimi = mysqli_real_escape_string($con,$profiilikuvanimi);
    }
    if($profiilikuvanimi == "" || $profiilikuvanimi == "eikuvaa.png"){
        
    } else {
        $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
    }
    array_push($palautusarray,$kuvasijainti); 

} else {
    echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
}
$profiilikuvahaku->close();

$kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE abi = ? ORDER BY id DESC");
if( $kommenttihaku &&
    $kommenttihaku->bind_param("s", $kayttajanimi) &&
    $kommenttihaku->execute() &&
    $kommenttihakutulos = $kommenttihaku->get_result()
  ) {
    foreach ($kommenttihakutulos as $row) {
        $kommentti = $row['kommentti'];
        $kommentti = mysqli_real_escape_string($con,$kommentti);
        $kommentti = preg_replace('/\\\n/', "", $kommentti);
        $kommentti = preg_replace('/\\\r/', " ", $kommentti);
        $kommentti = preg_replace('/rnrn/', "", $kommentti);
        $kommentti = preg_replace('/\\\/', "", $kommentti);
        array_push($palautusarray,$kommentti);  
    }
    $palautus = json_encode($palautusarray);
    echo $palautus;
} else {
    echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
}
$kommenttihaku->close();
?>