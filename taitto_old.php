<?php
include "kirjautumistarkastus.php";
?>
<!doctype html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
        <link media="print" href="style.css" />
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
        }
        </style>
        
    </head>
    <body>
        
        <?php
            include "etukansi.php";
            include "paakirjoitus.php";
            echo '<div style="width: 210mm;" align="left" class="reuna">';
            
            $vuosikerta = 2019;
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
                    
                    echo '<div class="luokannimijaro"><h1 class="luokannimijaro">'.$luokka.' - '.$ryhmanohjaaja.'</h1></div>';
                    
                    $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
                    if( $kayttajahaku &&
                        $kayttajahaku->bind_param("s", $luokka) &&
                        $kayttajahaku->execute() &&
                        $kayttajahakutulos = $kayttajahaku->get_result()
                      ) {
                        foreach ($kayttajahakutulos as $row) {
                            $kayttajanimi = $row['kayttajanimi'];
                            $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
                            include 'tulostaprofiilikayttajanimella.php';
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
            echo '</div>';
            include "takakansi.php";
        
        ?>
        
        <script>
            document.getElementsById("kronikka").height();
        
        
        </script>
        
        
        
    </body>
</html>


