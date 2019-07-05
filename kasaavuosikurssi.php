<?php
include "kirjautumistarkastus.php";
$vuosikerta = $_GET['vuosikerta'];
$palautusarray = array($vuosikerta);
$kuvaarray = array($vuosikerta);

$luokkahaku = $con->prepare("SELECT * FROM kronikka_luokat WHERE vuosikerta = ?");
if( $luokkahaku &&
    $luokkahaku->bind_param("i", $vuosikerta) &&
    $luokkahaku->execute() &&
    $luokkahakutulos = $luokkahaku->get_result()
  ) {
    foreach ($luokkahakutulos as $row) {
        $luokka = $row['luokka'];
        $luokka = mysqli_real_escape_string($con,$luokka);
        $ryhmanohjaaja = $row['ro'];
        $ryhmanohjaaja = mysqli_real_escape_string($con,$ryhmanohjaaja);
        array_push($palautusarray,"luokka",$luokka);
        array_push($palautusarray, "ryhmanohjaaja", $ryhmanohjaaja);
        
        $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
        if( $kayttajahaku &&
            $kayttajahaku->bind_param("s", $luokka) &&
            $kayttajahaku->execute() &&
            $kayttajahakutulos = $kayttajahaku->get_result()
          ) {
            foreach ($kayttajahakutulos as $row) {
                $kayttajanimi = $row['kayttajanimi'];
                $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
                array_push($palautusarray, "kayttajanimi",$kayttajanimi);

                $abinimihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
                if( $abinimihaku &&
                    $abinimihaku->bind_param("s", $kayttajanimi) &&
                    $abinimihaku->execute() &&
                    $abinimihakutulos = $abinimihaku->get_result()
                  ) {

                    foreach ($abinimihakutulos as $row) {
                        $abinimi = $row['nimi'];
                        $abinimi = mysqli_real_escape_string($con,$abinimi);
                        array_push($palautusarray,"abinimi",$abinimi);
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
                    if($profiilikuvanimi == "eikuvaa.png"){
                        $kuvasijanti = "eikuvaa";
                    } else if($profiilikuvanimi != ""){
                        $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                    }else {
                        $kuvasijainti = 'wilma';
                    }
                    array_push($palautusarray,"kuvasijainti",$kuvasijainti);
                    array_push($kuvaarray,$kayttajanimi,$kuvasijainti); 

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
                        array_push($palautusarray,"kommentti",$kommentti); 
                    }
                } else {
                    echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
                }
                $kommenttihaku->close();
                
                
                
                
                
                
                
                
            }

        } else {
            echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
        }
        $kayttajahaku->close();
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }
    
} else {
    echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
}
$luokkahaku->close();
$kuvapalautus = json_encode($kuvaarray);
$palautus = json_encode($palautusarray);
$fkp = fopen('2019_kuvat.json', 'w');
$fp = fopen('2019.json', 'w');
fwrite($fp, $palautus);
fwrite($fkp, $kuvapalautus);
fclose($fkp);
fclose($fp);
?>