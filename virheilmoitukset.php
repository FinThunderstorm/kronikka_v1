<?php
$saatuvirheilmoitus = $_GET['error'];

//Oikeudet eivät riitä katselemiseen
$oikeudetvirhe = "oikeudet";
if ($saatuvirheilmoitus == $oikeudetvirhe){
    echo "Sinulla ei ole riittävästi oikeuksia katselemiseen";
}

?>
