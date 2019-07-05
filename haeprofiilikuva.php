<?php
include "kirjautumistarkastus.php";
$kayttajanimi = $_GET['kayttajanimi'];

$profiilikuvanimi = "";
$profiilikuvahaku = $con->prepare("SELECT * FROM `kronikka_profiilikuvat` WHERE lisaaja = ?");
if( $profiilikuvahaku &&
    $profiilikuvahaku->bind_param("s", $kayttajanimi) &&
    $profiilikuvahaku->execute() &&
    $profiilikuvahakutulos = $profiilikuvahaku->get_result()
  ) {
    $palautusarray = array('profiilikuvan sijainti');
    foreach ($profiilikuvahakutulos as $row) {
        $profiilikuvanimi = $row['profiilikuvanimi'];
        $profiilikuvanimi = mysqli_real_escape_string($con,$profiilikuvanimi);
    }
    if($profiilikuvanimi == "" || $profiilikuvanimi == "eikuvaa.png"){
        
    } else {
        $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
    }
    array_push($palautusarray,$kuvasijainti); 
    $palautus = json_encode($palautusarray);
    echo $palautus;
} else {
    echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
}
$profiilikuvahaku->close();
?>