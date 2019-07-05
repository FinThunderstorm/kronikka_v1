<?php
include "kirjautumistarkastus.php";
$kayttajanimi = $_GET['kayttajanimi'];

$abinimihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
if( $abinimihaku &&
    $abinimihaku->bind_param("s", $kayttajanimi) &&
    $abinimihaku->execute() &&
    $abinimihakutulos = $abinimihaku->get_result()
  ) {
    $palautusarray = array('nimi');
    foreach ($abinimihakutulos as $row) {
        $abinimi = $row['nimi'];
        $abinimi = mysqli_real_escape_string($con,$abinimi);
        array_push($palautusarray,$abinimi);
    }
    $palautus = json_encode($palautusarray);
    echo $palautus;

} else {
    echo "2Tapahtui odottamaton virhe, yritä uudelleen...";
}
$abinimihaku->close();
?>