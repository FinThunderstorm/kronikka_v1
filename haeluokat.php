<?php
include "kirjautumistarkastus.php";
$vuosikerta = $_GET['vuosikerta'];

$luokkahaku = $con->prepare("SELECT * FROM kronikka_luokat WHERE vuosikerta = ?");
if( $luokkahaku &&
    $luokkahaku->bind_param("i", $vuosikerta) &&
    $luokkahaku->execute() &&
    $luokkahakutulos = $luokkahaku->get_result()
  ) {
    $palautusarray = array('luokat');
    foreach ($luokkahakutulos as $row) {
        $luokka = $row['luokka'];
        $luokka = mysqli_real_escape_string($con,$luokka);
        $ryhmanohjaaja = $row['ro'];
        $ryhmanohjaaja = mysqli_real_escape_string($con,$ryhmanohjaaja);
        array_push($palautusarray, $luokka, $ryhmanohjaaja);
    }
    $palautus = json_encode($palautusarray);
    echo $palautus;
} else {
    echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
}
$luokkahaku->close();
?>