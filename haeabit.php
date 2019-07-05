<?php
include "kirjautumistarkastus.php";
$luokka = $_GET['luokka'];

$kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
if( $kayttajahaku &&
    $kayttajahaku->bind_param("s", $luokka) &&
    $kayttajahaku->execute() &&
    $kayttajahakutulos = $kayttajahaku->get_result()
  ) {
    $palautusarray = array('abit');
    foreach ($kayttajahakutulos as $row) {
        $kayttajanimi = $row['kayttajanimi'];
        $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
        array_push($palautusarray, $kayttajanimi);
    }
    $palautus = json_encode($palautusarray);
    echo $palautus;
} else {
    echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
}
$kayttajahaku->close();
?>