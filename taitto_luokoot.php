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
    <body>
        
        <div class="sivu"  style="float: left;">
        <div class="sisaltoalue" id="jono">
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
                    
                    echo '<div class="luokkaotsikkoviiva" id="'.$i++.'"><h1 class="luokkaotsikko '.$luokka.'">'.$luokka.' - '.$ryhmanohjaaja.'</h1></div>';
                    
                    $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
                    if( $kayttajahaku &&
                        $kayttajahaku->bind_param("s", $luokka) &&
                        $kayttajahaku->execute() &&
                        $kayttajahakutulos = $kayttajahaku->get_result()
                      ) {
                        foreach ($kayttajahakutulos as $row) {
                            $kayttajanimi = $row['kayttajanimi'];
                            $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
                            echo '<div class="kehys" id="kehys'.$kayttajanimi.'" >';
                            
                            $abinimihaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi = ?");
                            if( $abinimihaku &&
                                $abinimihaku->bind_param("s", $kayttajanimi) &&
                                $abinimihaku->execute() &&
                                $abinimihakutulos = $abinimihaku->get_result()
                              ) {

                                foreach ($abinimihakutulos as $row) {
                                    $abinimi = $row['nimi'];
                                    $abinimi = mysqli_real_escape_string($con,$abinimi);
                                    echo '<h1 class="nimi '.$kayttajanimi.'" id="'.$i++.'">'.$abinimi.'</h1>';
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
                                if($profiilikuvanimi == ""){
                                    $kuvasijainti = './wilmakuvat/'.$kayttajanimi.'.jpg';
                                    echo '<img src="'.$kuvasijainti.'" class="profiilikuva '.$kayttajanimi.'" id="'.$i++.'"></img>';
                                } else if($profiilikuvanimi == "eikuvaa.png"){
                                    
                                } else {
                                    $kuvasijainti = './profiilikuvat/'.$profiilikuvanimi;
                                    echo '<img src="'.$kuvasijainti.'" class="profiilikuva '.$kayttajanimi.'" id="'.$i++.'"></img>';
                                }
                                

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
                                    echo '<p class="kommentti '.$kayttajanimi.'" id="'.$i++.'">'.$kommentti.'</p>'; 
                                }
                            } else {
                                echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
                            }
                            $kommenttihaku->close();
                            echo '</div>';
                            
                            
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
            echo '<img src="./takakansi.jpg" class="takakansi takakansi" id="'.$i++.'" onload="paivitaNaytto()"></img>';
            echo '<div id="taittoohjeet"></div>';
        
        ?>
        </div>
        </div>
        <div style="float: left; width: 125px; margin: 10px; background-color: white;">
            <p id="valmis"></p>
            <button onclick="luoUusiSivu()">Luo uusi sivu</button>
            <button onclick="luoAbiKehys()">Luo abin kehys</button>
            <button onclick="lisaaNykyiselleSivulle()">Nykyiselle sivulle</button>
            <button onclick="lisaaEdelliselleSivulle()">Edelliselle sivulle</button>
            <button onclick="ohita()">Ohita</button>
            <button onclick="palautaEdellinen()">Palauta</button>
            <button onclick="piilotaEdellinenSivu()">Piilota edellinen sivu</button>
            <button onclick="kasaaSivu()">Kasaa sivu</button>
            <button onclick="kasaaAbi()">Kasaa abi</button>
            
            <p id="sivulla">Sivulla: </p>
            <p id="indeksi">Indeksi: 0</p>
            <p id="abi">Abi: </p>
            <p id="tyyppi">Tyyppi: </p>
            <p id="korkeus">Korkeus: </p>
            <p id="tilaanyk">Tilaa nyk: </p>
            <p id="tilaaed">Tilaa ed: </p>
            <br>
            <br>
            <form action="javascript:muutaIndeksia()">
                  <input type="text" id="muutaindeksia" placeholder="Muuta indeksiksi"><br>
                  <input type="submit" value="Muuta">
            </form>
        </div>
        <div id="taitto" style="float: left; width:210mm; margin: 10px; background-color: green;">
            
        </div>
        
       
        
        
        <script type="text/javascript">
            var menossaSivulla = 2;
            var indeksi = 0;
            var sivunkorkeus = 1110;
            
            function getElementinKorkeus(elementtiID){
                var hakutunnus = "#"+elementtiID;
                return $(hakutunnus).height();
            }
            
            function luoUusiSivu(){
                var uusiSivu = document.createElement("DIV");
                menossaSivulla = menossaSivulla + 1;
                uusiSivu.id = "s"+menossaSivulla;
                uusiSivu.className = "sivu";
                document.getElementById("taitto").appendChild(uusiSivu);
                var uusiSisaltoAlue = document.createElement("DIV");
                var idNimi = "sisalto" + menossaSivulla;
                uusiSisaltoAlue.id = idNimi;
                uusiSisaltoAlue.className = "sisaltoalue";
                document.getElementById("s"+menossaSivulla).appendChild(uusiSisaltoAlue);
                if(menossaSivulla - 2 >0){
                    var piilotettava = menossaSivulla-2;
                    document.getElementById("s"+piilotettava).style.display="none";
                }
                paivitaNaytto();
                lisaaTaittoOhje("luouusisivu","","",menossaSivulla);
            }
            
            function paivitaNaytto(){
                document.getElementById("sivulla").textContent = "Sivulla: " + menossaSivulla;
                document.getElementById("indeksi").textContent = "Indeksi: " + indeksi;
                document.getElementById("abi").textContent = "Abi: " + document.getElementById(indeksi).className.split(" ")[1];
                document.getElementById("tyyppi").textContent = "Tyyppi: " + document.getElementById(indeksi).className.split(" ")[0];
                document.getElementById("korkeus").textContent = "Korkeus: " + getElementinKorkeus(indeksi);
                document.getElementById("tilaanyk").textContent = "Tilaa nyk: " + getSivullaTilaa(menossaSivulla);
                document.getElementById("tilaaed").textContent = "Tilaa ed: " + getSivullaTilaa(menossaSivulla-1);
                var abi = document.getElementById(indeksi).className.split(" ")[1];
                
            }
            
            function luoAbiKehys(){
                var abi = document.getElementById(indeksi).className.split(" ")[1];
                if(getAbiKehys(menossaSivulla) != null){
                    alert("Abikehys löytyy jo abille " + abi);
                    return;
                }
                
                if(abi == undefined){
                    alert("Et voi luoda kehystä luokkaotsikolle");
                    return;
                }
                
                if(getSivullaTilaa(menossaSivulla)< getElementinKorkeus(indeksi)){
                    alert("Et voi lisätä abikehystä täydelle sivulle");
                    return;
                }
                
                var aiempisivu = menossaSivulla-1;
                var tarkastuskehysid = abi+aiempisivu;
                var tarkastuskehys =  document.getElementById(tarkastuskehysid);
                if (typeof(tarkastuskehys) != 'undefined' && tarkastuskehys != null)
                {
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys-2";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    lisaaTaittoOhje("kehys-2","",abi,menossaSivulla);
                    return;
                } else{
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    lisaaTaittoOhje("kehys","",abi,menossaSivulla);
                    return;
                }
            }
            
            function lisaaNykyiselleSivulle(){
                var lisattava = document.getElementById(indeksi);
                if(getSivullaTilaa(menossaSivulla) < getElementinKorkeus(indeksi)){
                    alert("Ei mahdu, laita seuraavalle sivulle");
                }
                if(lisattava.className == "luokkaotsikkoviiva"){
                    getSisaltoAlue(menossaSivulla).appendChild(lisattava);
                } else{
                    if(getAbiKehys(menossaSivulla) == null){
                        alert("Lisää abikehys!");
                    }
                    getAbiKehys(menossaSivulla).appendChild(lisattava);
                }
                lisaaTaittoOhje(indeksi, document.getElementById(indeksi).className.split(" ")[0], document.getElementById(indeksi).className.split(" ")[1], menossaSivulla);
                indeksi = indeksi + 1;
                paivitaNaytto();
                 
            }
            
            function lisaaEdelliselleSivulle(){
                var edellinen = menossaSivulla-1;
                var lisattava = document.getElementById(indeksi);
                if(getSivullaTilaa(edellinen) < getElementinKorkeus(indeksi)){
                    alert("Ei mahdu, laita seuraavalle sivulle");
                } 
                if(lisattava.className == "luokkaotsikkoviiva"){
                    getSisaltoAlue(edellinen).appendChild(lisattava);
                } else{
                    if(getAbiKehys(edellinen) == null){
                        alert("Lisää abikehys!");
                    }
                    getAbiKehys(edellinen).appendChild(lisattava);
                }
                lisaaTaittoOhje(indeksi, document.getElementById(indeksi).className.split(" ")[0], document.getElementById(indeksi).className.split(" ")[1], edellinen);
                indeksi = indeksi + 1;
                paivitaNaytto();
                 
            }
            
            function kasaaSivu(){
                while(true){
                    
                    if(getAbiKehys(menossaSivulla) == "undefined" || getAbiKehys(menossaSivulla) == null){
                        luoAbiKehys();
                    }
                    if(getSivullaTilaa(menossaSivulla) < getElementinKorkeus(indeksi)){
                        break;
                    }
                    lisaaNykyiselleSivulle();
                }
            }
            
            /*function kasaaAbi(){
                var abi = document.getElementById(indeksi).className.split(" ")[1];
                while(true){
                    if(getAbiKehys(menossaSivulla) == "undefined" || getAbiKehys(menossaSivulla) == null){
                        luoAbiKehys();
                    }
                    if(getSivullaTilaa(menossaSivulla) < getElementinKorkeus(indeksi)){
                        break;
                    }
                    if(document.getElementById(indeksi).className.split(" ")[1]) == abi){
                        lisaaNykyiselleSivulle();
                    }
                }
                    
                   
                }*/
            
            
            
            function piilotaEdellinenSivu(){
                var piilotettava = menossaSivulla-1;
                document.getElementById("s"+piilotettava).style.display="none";
            }
            
            function palautaEdellinen(){
                indeksi = indeksi - 1;
                var palautettava = document.getElementById(indeksi);
                if(document.getElementById(indeksi).className.split(" ")[0] == "luokkaotsikkoviiva"){
                    var hakusana = "jono";
                } else {
                    var hakusana = "kehys"+document.getElementById(indeksi).className.split(" ")[1];
                }
                console.log(hakusana);
                var jono = document.getElementById(hakusana);
                jono.insertBefore(palautettava, jono.firstChild);
                var ohje = document.getElementById(indeksi+document.getElementById(indeksi).className.split(" ")[1]);
                ohje.parentNode.removeChild(ohje);
                paivitaNaytto();
            }
            
            function ohita(){
                document.getElementById(indeksi).style.display="none";
                indeksi = indeksi + 1;
                paivitaNaytto();
            }
            
            function lisaaTaittoOhje(indeksi, tyyppi, abi, sivu){
                var ohjekehys = document.getElementById("taittoohjeet");
                var ohjeTeksti = document.createTextNode(indeksi + " " + tyyppi + " " + " " + abi + " " + sivu);
                var ohjeP = document.createElement("p");
                ohjeP.id=indeksi+abi;
                ohjeP.appendChild(ohjeTeksti);
                ohjekehys.appendChild(ohjeP);
                console.log(indeksi + " " + tyyppi + " " + abi + " " + sivu)
            }
            
            function muutaIndeksia(){
                var muutettava = parseInt(document.getElementById("muutaindeksia").value);
                indeksi = muutettava
                console.log(muutettava);
                document.getElementById("muutaindeksia").value = "";
                paivitaNaytto();
            }
            
            function getAbiKehys(sivulta){
                var abi = document.getElementById(indeksi).className.split(" ")[1] + sivulta;
                return document.getElementById(abi);
            }
            
            function getSisaltoAlue(sivulta){
                var sisaltoalueenSivu = "sisalto" + sivulta;
                return document.getElementById(sisaltoalueenSivu);
            }
            
            function getSivullaTilaa(sivu){
                var sivuntunnus = "#sisalto"+sivu;
                return sivunkorkeus - $(sivuntunnus).height();
            }
            
            
            
        </script>
        <!--
        <script type="text/javascript">
             
            var sivuja, sivunkorkeus, menossaSivulla, menossaOlevanSivunTayttoAste;
            sivuja = 1;
            menossaSivulla = 0;
            sivunkorkeus = 1110;
            
            
            function luoAbinKehys(abi){
                var aiempisivu = menossaSivulla-1;
                var tarkastuskehysid = abi+aiempisivu;
                var tarkastuskehys =  document.getElementById(tarkastuskehysid);
                if (typeof(tarkastuskehys) != 'undefined' && tarkastuskehys != null)
                {
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys-2";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    return;
                } else{
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    return;
                }
            }
            
            function lisaaKommenttiSivulle(abi, kommenttiID){
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                if(getAbiKehys(abi+menossaSivulla).className == "kehys-2"){
                    var edellinen = menossaSivulla-1;
                    var edellisessaKuva = getAbiKehys(abi+edellinen).getElementsByTagName("img");
                    if(edellisessaKuva.length>0){
                        /*if(sivunkorkeus-getSivullaTavaraa(edellinen)-20>getElementinKorkeus(kommenttiID)){
                            var kommenttiP = document.getElementById(kommenttiID);
                            getAbiKehys(abi+edellinen).appendChild(kommenttiP);
                            console.log("Siirretään edelliselle sivulle...");
                        } else{
                            var kommenttiP = document.getElementById(kommenttiID);
                            getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                        }*/
                        console.log("Heitellääs edelliselle sivulle");
                        var kommenttiP = document.getElementById(kommenttiID);
                        getAbiKehys(abi+edellinen).appendChild(kommenttiP);
                        if(sivunkorkeus>getSivullaTavaraa(edellinen)){
                            var kommenttiP = document.getElementById(kommenttiID);
                            getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                        }
                        
                    } else{
                        if(sivunkorkeus-getSivullaTavaraa(edellinen)-20>getElementinKorkeus(kommenttiID)){
                            var kommenttiP = document.getElementById(kommenttiID);
                            getAbiKehys(abi+edellinen).appendChild(kommenttiP);
                            console.log("Siirretään edelliselle sivulle...");
                        } else{
                            var kommenttiP = document.getElementById(kommenttiID);
                            getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                        }
                    }
                    
                    
                    
                    
                } else {
                    if(sivunkorkeus-getSivullaTavaraa(menossaSivulla)-20>getElementinKorkeus(kommenttiID)){
                        var kommenttiP = document.getElementById(kommenttiID);
                        getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                    } else {
                        console.log("Sivu täynnä kommentin "+kommenttiID+" kohdalla.");
                        luoUusiSivu();
                        if(getAbiKehys(abi+menossaSivulla) == null){
                            luoAbinKehys(abi);
                        }
                        var kommenttiP = document.getElementById(kommenttiID);
                        getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                    }
                    
                }
                
                
                
                
                
                
                
                /*
                var haku = "profiilikuva "+abi;
                var kuva = document.getElementsByClassName(haku);
                if(kuva.length>0){
                    var abinprofiilikuvankorkeus = getElementinKorkeus(kuva[0].id);
                } else {
                    var abinprofiilikuvankorkeus = 0;
                }
                    
                var tilaa = sivunkorkeus-getSivullaTavaraa(menossaSivulla);
                var kommenttiP = document.getElementById(kommenttiID);
                getAbiKehys(abi+menossaSivulla).appendChild(kommenttiP);
                document.getElementById(kommenttiID).style.visibility = "hidden";
                
                var kommentinkorkeus = getElementinKorkeus(kommenttiID);
                
                console.log("Kommentin "+abi+kommenttiID+" korkeus "+kommentinkorkeus+", tilaa "+tilaa+", kehyksessä "+getAbiKehys(abi+menossaSivulla).id);
                console.log(kommentinkorkeus<tilaa);
                
                if(kommentinkorkeus<tilaa){
                    document.getElementById(kommenttiID).style.visibility = "visible";
                    return;
                } else{
                    console.log("Sivu täynnä kommentin "+kommenttiID+" kohdalla.");
                    luoUusiSivu();
                    lisaaKommenttiSivulle(abi, kommenttiID);
                    return;
                }*/
            }
            
            function lisaaNimiSivulle(abi, nimiID){
                if(sivunkorkeus-getSivullaTavaraa(menossaSivulla)<150){
                    luoUusiSivu();
                }
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                var nimiH = document.getElementById(nimiID);
                getAbiKehys(abi+menossaSivulla).appendChild(nimiH);
                document.getElementById(nimiID).style.visibility = "hidden";
                if(mahtuukoSivulle(nimiID)){
                    document.getElementById(nimiID).style.visibility = "visible";
                    return;
                } 
                else{
                    console.log("Sivu täynnä nimen "+abi+" kohdalla.");
                    $('#'+abi+menossaSivulla).remove();
                    luoUusiSivu();
                    lisaaNimiSivulle(abi, nimiID);
                    
                    return;
                }
            }
            
            function lisaaKuvaSivulle(abi, kuvaID){
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                var tilaa = sivunkorkeus-getSivullaTavaraa(menossaSivulla);
                var kuvaTagi = document.getElementById(kuvaID);
                getAbiKehys(abi+menossaSivulla).appendChild(kuvaTagi);
                document.getElementById(kuvaID).style.visibility = "hidden";
                var kuvankorkeus = getElementinKorkeus(kuvaID);
                if(kuvankorkeus<tilaa){
                    document.getElementById(kuvaID).style.visibility = "visible";
                    return;
                } else{
                    console.log("Sivu täynnä kuvan "+kuvaID+" kohdalla.");
                    luoUusiSivu();
                    lisaaKuvaSivulle(abi, kuvaID);
                    return;
                }
            }
            
            function lisaaLuokkaSivulle(luokkaID){
                if(sivunkorkeus-getSivullaTavaraa(menossaSivulla)<150){
                    luoUusiSivu();
                }
                var luokkaH = document.getElementById(luokkaID);
                getSisaltoAlue(menossaSivulla).appendChild(luokkaH);
                document.getElementById(luokkaID).style.visibility = "hidden";
                if(mahtuukoSivulle(luokkaID)){
                    document.getElementById(luokkaID).style.visibility = "visible";
                    return;
                } 
                else{
                    console.log("Sivu täynnä luokan "+luokkaID+" kohdalla.");
                    luoUusiSivu();
                    lisaaLuokkaSivulle(luokkaID);
                    return;
                }
            }
            
            
            function mahtuukoSivulle(elementtiID){
                if(getElementinKorkeus(elementtiID) + getSivullaTavaraa(menossaSivulla) < sivunkorkeus){
                    return true;
                } else{
                    return false;
                }
            }
            
            function luoUusiSivu(){
                var uusiSivu = document.createElement("DIV");
                menossaSivulla = menossaSivulla + 1;
                uusiSivu.id = "s"+menossaSivulla;
                uusiSivu.className = "sivu";
                document.body.appendChild(uusiSivu);
                var uusiSisaltoAlue = document.createElement("DIV");
                var idNimi = "sisalto" + menossaSivulla;
                uusiSisaltoAlue.id = idNimi;
                uusiSisaltoAlue.className = "sisaltoalue";
                document.getElementById("s"+menossaSivulla).appendChild(uusiSisaltoAlue);
                return;
            }
            
            function getAbiKehys(abi){
                return document.getElementById(abi);
            }
            
            function getSisaltoAlue(sivulta){
                var sisaltoalueenSivu = "sisalto" + sivulta;
                return document.getElementById(sisaltoalueenSivu);
            }
            
            function getSivullaTavaraa(sivu){
                var sivuntunnus = "#sisalto"+sivu;
                return $(sivuntunnus).height();
            }
            
            function getElementinKorkeus(elementtiID){
                var hakutunnus = "#"+elementtiID;
                return $(hakutunnus).height();
            }
            
            
            function kasaaVuosikurssi(){
                console.log("Valmistellaan taittamista");
                luoUusiSivu();

                //paakirjoitus
                luoUusiSivu();

                //ensimmainen sisaltosivu
                luoUusiSivu();

                getSisaltoAlue(1).innerHTML = '<p class="kommentti" id="kommentti_abi_1">Kansilehti</p>';
                getSisaltoAlue(2).innerHTML = '<p class="kommentti">Pääkirjoitus</p>';
                
                console.log("Kronikka ladattu, aloitetaan taittaminen");
                console.log("Done");
                console.log(document.getElementsByClassName("profiilikuva tuomasalanen").id);
                var i = 0;
                while(true){
                    var lajike = document.getElementById(i).className.split(" ");
                    if(lajike[0] == "nimi"){
                        lisaaNimiSivulle(lajike[1],i);
                        i = i + 1;
                    } else if(lajike[0] == "profiilikuva"){
                        lisaaKuvaSivulle(lajike[1],i);
                        i = i + 1;
                    } else if(lajike[0] == "kommentti"){
                        lisaaKommenttiSivulle(lajike[1],i);
                        i = i + 1;
                    } else if(lajike[0] == "luokkaotsikko"){
                        lisaaLuokkaSivulle(i);
                        i = i + 1;
                    } else if(lajike[0] == "takakansi"){
                        break;
                    }
                }
            }
            
            
            
            
            function old_kasaaVuosikurssi(vuosikurssi){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                    var vuosikurssi = JSON.parse(this.responseText);
                    var i = 1;
                    while(i<vuosikurssi.length){
                        //0 = vuosikurssi
                        //1=luokka
                        //3=ro
                        //5=abi
                        //7=nimi
                        //9=profiilikuvan sijainti
                        //11=kommentit array
                        //7=abi
                        //8=nimi
                        //9=profiilikuvan sijainti
                        //10=kommentit array
                        if(vuosikurssi[i] == "luokka"){
                            i = i + 1;
                            //lisaaLuokkaSivulle(vuosikurssi[i],vuosikurssi[i+1]);
                            console.log(vuosikurssi[i] +" "+vuosikurssi[i+2]);
                            i = i + 3;
                        }
                        if(vuosikurssi[i] == "kayttajanimi"){
                            i = i + 1;
                            var abi = vuosikurssi[i];
                            i = i + 1;
                        }
                        if(vuosikurssi[i] == "abinimi"){
                            i = i + 1;
                            lisaaNimiSivulle(abi,vuosikurssi[i],i);
                            i = i + 1;
                        }
                        if(vuosikurssi[i] == "kuvasijainti"){
                            i = i + 1;
                            console.log("lisataan abin "+abi+" kuvaa");
                            if(vuosikurssi[i] == "wilma" || vuosikurssi[i] == "eikuvaa"){
                                
                            } else{
                                lisaaKuvaSivulle(abi,vuosikurssi[i],i);
                            }
                            i = i + 1;
                        }
                        sleep(200);
                        if(vuosikurssi[i] == "kommentti"){
                            i = i + 1;
                            lisaaKommenttiSivulle(abi,vuosikurssi[i],i);
                            i = i + 1;
                        }
                        console.log(i);
                    }
                  }
                };
                xmlhttp.open("GET", "2019.json", true);
                xmlhttp.send();  
            }
            
           
            
        
            function sleep(milliseconds) {
              var start = new Date().getTime();
              for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds){
                  break;
                }
              }
            }

        </script>-->

    </body>
</html>


