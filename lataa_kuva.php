<?php
    include "kirjautumistarkastus.php";

    //TARKASTETAAN, ONKO KUVA JO OLEMASSA?
        $kuvaolemassahaku = $con->prepare("SELECT * FROM kronikka_profiilikuvat WHERE lisaaja = ?");
        if( $kuvaolemassahaku &&
            $kuvaolemassahaku->bind_param("s", $kayttajanimi) &&
            $kuvaolemassahaku->execute() &&
            $kuvaolemassahakutulos = $kuvaolemassahaku->get_result()
          ) {
                foreach ($kuvaolemassahakutulos as $row) {
                    $lisaaja = $row['lisaaja'];
                    $lisaaja = mysqli_real_escape_string($con, $lisaaja);
                    $profiilikuvanimi = $row['profiilikuvanimi'];
                    $profiilikuvanimi = mysqli_real_escape_string($con, $profiilikuvanimi);
                    $kuvanumero = $row['id'];
                    $kuvavuosikerta = $row['vuosikerta'];
                }
                if($profiilikuvanimi !== NULL){
                
                $vanhakuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                if(unlink($vanhakuvasijainti)){
                    
                    $poistoajo = $con->prepare("DELETE FROM kronikka_profiilikuvat WHERE id = ?");
                    if( $poistoajo &&
                        $poistoajo->bind_param("i", $kuvanumero) &&
                        $poistoajo->execute()
                      ) {
                    } 
                    $poistoajo->close();
                }
                else if ($profiilikuvanimi == 'eikuvaa.png') {
                    $poistoajo = $con->prepare("DELETE FROM kronikka_profiilikuvat WHERE id = ?");
                    if( $poistoajo &&
                        $poistoajo->bind_param("i", $kuvanumero) &&
                        $poistoajo->execute()
                      ) {
                    } 
                    $poistoajo->close();
                }
            } }
        $kuvaolemassahaku->close();


    /*$kuvaolemassahaku = mysqli_query($con, "SELECT * FROM `kronikka_profiilikuvat` WHERE lisaaja = '$kayttajanimi'");
    if(mysqli_num_rows($kuvaolemassahaku) > 0)
        {
        while ($row = mysqli_fetch_array($kuvaolemassahaku))
          {
            $lisaaja = $row['lisaaja'];
            $profiilikuvanimi = $row['profiilikuvanimi'];
            $kuvanumero = $row['id'];
          }
        echo $lisaaja."<br />";
        echo $profiilikuvanimi."<br />";
        echo $kuvanumero."<br />";
        echo $vuosikerta."<br />";
        $vanhakuvanimi = './profiilikuvat/'.$profiilikuvanimi;
        if(unlink($vanhakuvasijainti)){
            echo "Vanha kuva poistettu: ".$vanhakuvanimi."<br />";
            $poistoajo = "DELETE FROM `kronikka_profiilikuvat` WHERE `id` = $kuvanumero";
            if(mysqli_query($con, $poistoajo)){
                echo 'Vanha tietokantatieto poistettu<br /><br />';
            }
            else{
                echo 'Tietokantatiedon poistossa virhe<br />';
            }
        }
        else{
            echo "Virhe poistossa (".$vanhakuvanimi.")"."<br />";
        }
    }*/
        
        //Aloitetaan kuvan lataaminen
        $kohdekansio = "profiilikuvat/";
        $kohdetiedosto = $kohdekansio.basename($_FILES["kuvaladattavaksi"]["name"]);
        $kuvalataustila = 1;
        $kuvatiedostotyyppi = strtolower(pathinfo($kohdetiedosto,PATHINFO_EXTENSION));

        //Kuvatiedoston aitouden tarkastus kuvakoon tarkastamisella
        if(isset($_POST["submit"])) {
            $tarkastus = getimagesize($_FILES["kuvaladattavaksi"]["tmp_name"]);
            if($tarkastus !== false) {
                $kuvalataustila = 1;
            } else {
                echo "Tiedosto ei ole kuva. Yritäppä uudestaan.<br />";
                $kuvalataustila = 0;
            }
        }
        // Kuvan kokorajoitus 20MB
        if ($_FILES["kuvaladattavaksi"]["size"] > 20000000) {
            echo "Kokorajoitus 20MB<br />";
            $kuvalataustila = 0;
        }

        // Varmistus, onko jäänyt kuvatiedostoa tallennustilaan
        if (file_exists($kohdetiedosto)) {
            echo "Tiedostonimi on varattu.<br />";
            $kuvalataustila = 0;
        }

        // Sallitaan vain jpg, jpeg, png ja gif formaatin kuvat
        if($kuvatiedostotyyppi != "jpg" && $kuvatiedostotyyppi != "png" && $kuvatiedostotyyppi != "jpeg"
        && $kuvatiedostotyyppi != "gif" ) {
            echo "Hyväksyttyjä kuvaformaatteja ovat vain jpg, jpeg, png ja gif.<br />";
            $kuvalataustila = 0;
        }

        // Tarkastetaan kuvalataustila
        if ($kuvalataustila == 0) {
            echo "Kuvaa ei ladattu. Yritäppä uudestaan.<br />";
        // kaikki ok, ladataan
        } else {
            if (move_uploaded_file($_FILES["kuvaladattavaksi"]["tmp_name"], $kohdetiedosto)) {
            } else {
                echo "Virhe. Yritä uudelleen.<br />";
            }
        }

        //Nimetään kuva käyttäjän mukaiseksi
        $vanhanimi = "./profiilikuvat/".basename( $_FILES["kuvaladattavaksi"]["name"]);
        $vanhanimi = mysqli_real_escape_string($con,$vanhanimi);
        $paate = pathinfo($vanhanimi, PATHINFO_EXTENSION);
        $paate = mysqli_real_escape_string($con,$paate);
        $uusinimi = "./profiilikuvat/".$kayttajanimi.".".$kuvatiedostotyyppi;
        $uusinimi = mysqli_real_escape_string($con,$uusinimi);
        $profiilikuvanimikantaan = $kayttajanimi.".".$kuvatiedostotyyppi;
        $profiilikuvanimikantaan = mysqli_real_escape_string($con,$profiilikuvanimikantaan);

        if(rename($vanhanimi,$uusinimi)){
        }else{
         echo 'Virhe uudelleennimeämisessä<br />';
        }

        //Ajetaan tieto uudesta kuvanimestä tietokantaan
        $kuvaajo = $con->prepare("INSERT INTO kronikka_profiilikuvat (vuosikerta, lisaaja, profiilikuvanimi)
        VALUES (?,?,?)");
        if( $kuvaajo &&
            $kuvaajo->bind_param("iss", $kayttajavuosikerta, $kayttajanimi, $profiilikuvanimikantaan) &&
            $kuvaajo->execute()
          ) {
            echo "<br />Profiilikuvasi on tallennettu:<br/>";
            echo "Vuosikerta: ".$kayttajavuosikerta."<br>";
            echo "Lisääjä: ".$kayttajakokonimi."<br/>";
            echo "Tiedostonimi: ".$vanhanimi."<br/>";
        } else {
            echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
        }
        $kuvaajo->close();


?>
<!DOCTYPE HTML html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <form action="/home.php">
            <button type="submit">Palaa alkuun</button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>