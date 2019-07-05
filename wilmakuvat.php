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
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="results.json"></script>
        <script src="html2canvas.min.js"></script>
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
            margin: 0;
        }
        </style>
        
    </head>
    <body style="background-color: white;">

        <?php
            $vuosikerta = 2019;
            $i = 0;
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
                    $i = 0;
                    
                    $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
                    if( $kayttajahaku &&
                        $kayttajahaku->bind_param("s", $luokka) &&
                        $kayttajahaku->execute() &&
                        $kayttajahakutulos = $kayttajahaku->get_result()
                      ) {
                        foreach ($kayttajahakutulos as $row) {
                            $kayttajanimi = $row['kayttajanimi'];
                            $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
                            
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
                                if($profiilikuvanimi == "" ){
                                    echo '<p style="color: green;">'.$kayttajanimi.'</p>';
                                    $i++;
                                } else if($profiilikuvanimi == "eikuvaa.png"){
                                    
                                }
                                
                                else {
                                    $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                                }
                                

                            } else {
                                echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
                            }
                            $profiilikuvahaku->close();

                            
                            
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
            echo '<p>'.$i.'</p>';
        
        ?>
    </body>
</html>
     