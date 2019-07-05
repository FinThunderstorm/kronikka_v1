<?php
$viestiid = $_POST['viestiid'];

$viestihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE id = ?");
if( $viestihaku &&
    $viestihaku->bind_param("i", $viestiid) &&
    $viestihaku->execute() &&
    $viestihakutulos = $viestihaku->get_result()
  ) {
    foreach ($viestihakutulos as $row) {
        $lisaaja = $row['lisaaja'];
        $lisaaja = mysqli_real_escape_string($con,$lisaaja);
        $abi = $row['abi'];
        $abi = mysqli_real_escape_string($con,$abi);
        $kommentti = $row['kommentti'];
        $kommentti = mysqli_real_escape_string($con,$kommentti);
        $luokka = $row['luokka'];
        $luokka = mysqli_real_escape_string($con,$luokka);
        $lisaysaika = $row['lisaysaika'];
    }

} else {
    echo "Tapahtui odottamaton virhe, yritä uudelleen...";
}
$viestihaku->close();
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

} else {
    echo "2Tapahtui odottamaton virhe, yritä uudelleen...";
}
$abihaku->close();  
?>