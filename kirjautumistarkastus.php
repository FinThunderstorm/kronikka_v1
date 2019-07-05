<?php
include "config.php";

    // Check user login or not
    if(!isset($_SESSION['kayttajanimi'])){
        header('Location: index.php');
    }

    // logout
    if(isset($_POST['but_logout'])){
        session_destroy();
        header('Location: index.php');
    }
    
    //HAETAAN KÄYTTÄJÄKOHTAISIA TIETOJA
    $kayttajanimi = $_SESSION['kayttajanimi'];
    $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
    $avattusivu = $_SERVER['PHP_SELF'];
    $avattusivu = mysqli_real_escape_string($con,$avattusivu);
    
    //käyttäjätietojen haku
    $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
    if( $kayttajahaku &&
        $kayttajahaku->bind_param("s", $kayttajanimi) &&
        $kayttajahaku->execute() &&
        $kayttajahakutulos = $kayttajahaku->get_result()
      ) {
        foreach ($kayttajahakutulos as $row) {
            $kayttajavuosikerta = $row['vuosikerta'];
            $kayttajavuosikerta = mysqli_real_escape_string($con,$kayttajavuosikerta);
            $kayttajaluokka = $row['luokka'];
            $kayttajaluokka = mysqli_real_escape_string($con,$kayttajaluokka);
            $kayttajakokonimi = $row['nimi'];
            $kayttajakokonimi = mysqli_real_escape_string($con,$kayttajakokonimi);
            $kayttajasahkoposti = $row['sahkoposti'];
            $kayttajasahkoposti = mysqli_real_escape_string($con,$kayttajasahkoposti);
            $kayttajaoikeusryhma = $row['oikeusryhma'];
            $kayttajaoikeusryhma = mysqli_real_escape_string($con,$kayttajaoikeusryhma);
            $kayttajaekakirjautuminen = $row['ekakirjautuminen'];
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $kayttajahaku->close();

    //sivulle tarvittavan oikeuden haku
    $tarvittavaoikeushaku = $con->prepare("SELECT * FROM kronikka_oikeudet WHERE sivu = ? AND vuosikerta = ?");

    if( $tarvittavaoikeushaku &&
        $tarvittavaoikeushaku->bind_param("si", $avattusivu, $kayttajavuosikerta) &&
        $tarvittavaoikeushaku->execute() &&
        $tarvittavaoikeushakutulos = $tarvittavaoikeushaku->get_result()
      ) {
        foreach ($tarvittavaoikeushakutulos as $row) {
            $tarvittavaoikeusnumero = $row['tarvittavaoikeusnumero'];
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $tarvittavaoikeushaku->close();

    //sivulle tarvittavan oikeusryhmän haku
    $tarvittavaoikeusryhmahaku = $con->prepare("SELECT * FROM kronikka_oikeusryhmat WHERE oikeusnumero = ?");

    if( $tarvittavaoikeusryhmahaku &&
        $tarvittavaoikeusryhmahaku->bind_param("i", $tarvittavaoikeusnumero) &&
        $tarvittavaoikeusryhmahaku->execute() &&
        $tarvittavaoikeusryhmahakutulos = $tarvittavaoikeusryhmahaku->get_result()
      ) {
        foreach ($tarvittavaoikeusryhmahakutulos as $row) {
            $tarvittavaoikeusryhma = $row['oikeusryhma'];
            $tarvittavaoikeusryhma = mysqli_real_escape_string($con,$tarvittavaoikeusryhma);
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $tarvittavaoikeusryhmahaku->close();

    //haetaan kayttajan oikeusryhmaa vastaava oikeusnumero   
    $kayttajaoikeusnumerohaku = $con->prepare("SELECT * FROM kronikka_oikeusryhmat WHERE oikeusryhma = ?");

    if( $kayttajaoikeusnumerohaku &&
        $kayttajaoikeusnumerohaku->bind_param("s", $kayttajaoikeusryhma) &&
        $kayttajaoikeusnumerohaku->execute() &&
        $kayttajaoikeusnumerohakutulos = $kayttajaoikeusnumerohaku->get_result()
      ) {
        foreach ($kayttajaoikeusnumerohakutulos as $row) {
            $kayttajaoikeusnumero = $row['oikeusnumero'];
            $kayttajaoikeusnumero = mysqli_real_escape_string($con,$kayttajaoikeusnumero);
        }

    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $kayttajaoikeusnumerohaku->close();
    
    if($kayttajaekakirjautuminen == 0 && $avattusivu !== "/salasananvaihtaminen.php" && $_POST['ss_submit'] !== "sstallennettu"){
        echo '<script>window.location.replace("/salasananvaihtaminen.php");</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=salasananvaihtaminen.php"></noscript>';
        echo "<br><b>Salasana on vaihdettava ennen kuin voit jatkaa.</b><br><br>";
    }

    if($kayttajaoikeusnumero < $tarvittavaoikeusnumero){
        echo '<script>window.location.replace("/home.php");</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=home.php"></noscript>';
        echo "<br><b>Pääsy evätty</b><br><br>";
    }
   
    include "virheilmoitukset.php";
?>

