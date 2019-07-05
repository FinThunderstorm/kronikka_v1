<?php
include "kirjautumistarkastus.php";
$kayttajanimi = $_GET['kayttajanimi'];

$kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE abi = ? ORDER BY id DESC");
if( $kommenttihaku &&
    $kommenttihaku->bind_param("s", $kayttajanimi) &&
    $kommenttihaku->execute() &&
    $kommenttihakutulos = $kommenttihaku->get_result()
  ) {
    $palautusarray = array('kommentit');
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