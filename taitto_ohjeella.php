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
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <script type="application/javascript" src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
            margin: 0;
        }
        </style>
        
    </head>
    <body>
        
        
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
                                    echo '<img src="'.$kuvasijainti.'" class="profiilikuva '.$kayttajanimi.'" id="'.$i++.'" style="max-height:139px;"></img>';
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
            echo '<img src="./takakansi.jpg" class="takakansi takakansi" id="'.$i++.'" onload="lataaJSON(lue)"></img>';
            
        
        ?>
        <button id="tulostanappi" onclick="tulostaSivuPDF()">Tulosta sivu 1</button>
        <p id="menossaSivullaNaytto">Menossa sivulla: 1</p>
        
        <div id="taitto">
            
        </div>
        
       
        
        
        <script type="text/javascript">
            var menossaSivulla = 1;
            var indeksi = 0;
            var sivunkorkeus = 1110;
            
            function tulostaSivuPDF(){
                var sivu = document.getElementById('s'+menossaSivulla);
                var asetukset = {
                  margin:       0,
                  filename:     'sivu-'+menossaSivulla+'.pdf',
                  image:        { type: 'png', quality: 1 },
                  html2canvas:  { scale: 5 },
                  jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                html2pdf().set(asetukset).from(sivu).save();
                document.getElementById("menossaSivullaNaytto").textContent = "Menossa sivulla: "+menossaSivulla;
                menossaSivulla = menossaSivulla + 1;
                document.getElementById("tulostanappi").textContent = "Tulosta sivu "+menossaSivulla;
            }
            
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
                //paivitaNaytto();
                //lisaaTaittoOhje("luouusisivu","","",menossaSivulla);
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
            
            
            
            function lataaJSON(callback) {   
                var xobj = new XMLHttpRequest();
                xobj.overrideMimeType("application/json");
                xobj.open('GET', 'taitto_ohje_2019.json', true); // Replace 'my_data' with the path to your file
                xobj.onreadystatechange = function () {
                      if (xobj.readyState == 4 && xobj.status == "200") {
                        // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
                        callback(xobj.responseText, taita);
                      }
                };
                xobj.send(null);  
            }

            function lue(t, callback){
                var ohjeet = [];
                var i = 1;
                var json = JSON.parse(t);

                while(i<json.length){
                    ohjeet = ohjeet.concat(json[i]);
                    i = i + 2;
                }
                callback(ohjeet);
            }
            
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
            
            function taita(ohjeet){
                var i;
                luoUusiSivuArvoilla(1);
                var kansi = new Image();
                kansi.src = "./kansi2019.png";
                kansi.style = "width: 100%;";
                document.getElementById("s1").style = "padding: 0;";
                var kannenturhasisalto = document.getElementById("sisalto1");
                kannenturhasisalto.parentNode.removeChild(kannenturhasisalto);
                var kannenturhasivunumero = document.getElementById("sivunumero1");
                kannenturhasivunumero.parentNode.removeChild(kannenturhasivunumero);
                document.getElementById("s1").appendChild(kansi);
                luoUusiSivuArvoilla(2);
                
                /*LISÄTÄÄN ALKUPUHE*/
                
                
                var alkupuheOtsikkoH1 = document.createElement("H1");
                var alkupuheTeksti1 = document.createElement("P");
                var alkupuheTeksti2 = document.createElement("P");
                var alkupuheTeksti3 = document.createElement("P");
                var alkupuheTeksti4 = document.createElement("P");
                var alkupuheNimet = document.createElement("P");
                
                

                alkupuheOtsikkoH1.appendChild(alkupuheOtsikko);
                alkupuheTeksti1.appendChild(alkupuheKappaleYksi);
                alkupuheTeksti2.appendChild(alkupuheKappaleKaksi);
                alkupuheTeksti3.appendChild(alkupuheKappaleKolme);
                alkupuheTeksti4.appendChild(alkupuheKappaleNelja);

                getSisaltoAlue(2).appendChild(alkupuheOtsikkoH1);
                getSisaltoAlue(2).appendChild(alkupuheTeksti1);
                getSisaltoAlue(2).appendChild(alkupuheTeksti2);
                getSisaltoAlue(2).appendChild(alkupuheTeksti3);
                getSisaltoAlue(2).appendChild(alkupuheTeksti4);
                getSisaltoAlue(2).appendChild(alkupuheNimet);
                
                var abiryhmakuva = new Image();
                abiryhmakuva.src="./abitryhmakuva.jpeg";
                abiryhmakuva.style="width: 100%;";
                getSisaltoAlue(2).appendChild(abiryhmakuva);
                
                for(i = 0; i < ohjeet.length; ++i) {
                    var ohje = ohjeet[i].split(" ");
                    if(ohje[0] == "luouusisivu"){
                        console.log("luouusisivu");
                        luoUusiSivuArvoilla(ohje[1]);
                    } else if(ohje[0] == "kehys"){
                        console.log("kehys: "+ohje[1]+", sivu: "+ohje[2]);
                    } else if(ohje[0] == "kehys-2"){
                        console.log("kehys-2: "+ohje[1]+", sivu: "+ohje[2]);
                    } else {
                        console.log("ID: "+ohje[0]+", abi: " + ohje[2] +     ", sivu: "+ohje[3]);
                    }
                }
                luoUusiSivuArvoilla(99);
                var tekijat = document.createElement("DIV");
                tekijat.innerHTML = '';
                getSisaltoAlue(99).appendChild(tekijat);
                
                luoUusiSivuArvoilla(100);
                var takakansi = new Image();
                takakansi.src = "./takakansi2019.png";
                takakansi.style = "width: 100%;";
                document.getElementById("s100").style = "padding: 0;";
                var takakannenturhasisalto = document.getElementById("sisalto100");
                takakannenturhasisalto.parentNode.removeChild(takakannenturhasisalto);
                var takakannenturhasivunumero = document.getElementById("sivunumero100");
                takakannenturhasivunumero.parentNode.removeChild(takakannenturhasivunumero);
                document.getElementById("s100").appendChild(takakansi);
                luoKehykset(ohjeet, siirraTavara);
            }
            
            function luoUusiSivuArvoilla(sivu){
                /* TOIMIVAA KOODIA ELÄ HUKKOO
                var uusiSivu = document.createElement("DIV");
                uusiSivu.id = "s"+sivu;
                uusiSivu.className = "sivu";
                document.getElementById("taitto").appendChild(uusiSivu);
                var uusiSisaltoAlue = document.createElement("DIV");
                var idNimi = "sisalto" + sivu;
                uusiSisaltoAlue.id = idNimi;
                uusiSisaltoAlue.className = "sisaltoalue";
                document.getElementById("s"+sivu).appendChild(uusiSisaltoAlue);*/
                
                
                var uusiSivu = document.createElement("DIV");
                uusiSivu.id = "s"+sivu;
                if(sivu % 2 == 0){
                    uusiSivu.className = "sivu-vasen";
                } else {
                    uusiSivu.className = "sivu";
                }
                document.getElementById("taitto").appendChild(uusiSivu);
                var uusiSisaltoAlue = document.createElement("DIV");
                var idNimi = "sisalto" + sivu;
                uusiSisaltoAlue.id = idNimi;
                uusiSisaltoAlue.className = "sisaltoalue";
                document.getElementById("s"+sivu).appendChild(uusiSisaltoAlue);
                
                var sivunumeroTeksti = document.createTextNode(sivu);
                var sivunumeroP = document.createElement("P");
                sivunumeroP.appendChild(sivunumeroTeksti);
                if(sivu % 2 == 0){
                    sivunumeroP.className = "sivunumero-vasen";
                } else{
                    sivunumeroP.className = "sivunumero";
                }
                sivunumeroP.id = "sivunumero"+sivu;
                uusiSivu.appendChild(sivunumeroP);
                
                var reunaKuva = new Image();
                var reunaDIV = document.createElement("DIV");
                if(sivu % 2 == 0){
                    reunaKuva.src = "./vihreatausta_vasen.svg";
                    reunaDIV.className = "taustakuva-vasen";
                } else {
                    reunaKuva.src = "./vihreatausta.svg";
                    reunaDIV.className = "taustakuva";
                }
                reunaDIV.appendChild(reunaKuva);
                uusiSivu.appendChild(reunaDIV);
                
            }
            
            function luoKehykset(ohjeet, callback){
                console.log("luodaan kehykset");
                var i;
                for(i = 0; i < ohjeet.length; ++i) {
                    var ohje = ohjeet[i].split(" ");
                    if(ohje[0] == "luouusisivu"){
                        //console.log("luouusisivu");
                    } else if(ohje[1] == "luokkaotsikkoviiva"){
                        var lisattava = document.getElementById(ohje[0]);
                        getSisaltoAlue(ohje[3]).appendChild(lisattava);
                    }else if(ohje[0] == "kehys"){
                        //console.log("kehys: "+ohje[1]);
                        luoAbiKehysArvoilla(ohje[0], ohje[1], ohje[2]);
                    } else if(ohje[0] == "kehys-2"){
                        //console.log("kehys-2: "+ohje[1]);
                        luoAbiKehysArvoilla(ohje[0], ohje[1], ohje[2]);
                    } else {
                        //console.log("ID: "+ohje[0]+", sivu: "+ohje[3]);
                    }
                }
                callback(ohjeet);
            }
            
            function luoAbiKehysArvoilla(tyyppi, abi, sivu){
                var kehys = document.createElement("DIV");
                kehys.id = abi+sivu;
                kehys.className = tyyppi;
                getSisaltoAlue(sivu).appendChild(kehys);
                return;
            }
            
            function getAbiKehysNimella(abi, sivulta){
                return document.getElementById(abi+sivulta);
            }
            
            function siirraTavara(ohjeet){
                console.log("siirretään tavaraa");
                var i;
                for(i = 0; i < ohjeet.length; ++i) {
                    var ohje = ohjeet[i].split(" ");
                    if(ohje[0] == "luouusisivu"){
                        //console.log("luouusisivu");
                    } else if(ohje[0] == "kehys"){
                        //console.log("kehys: "+ohje[1]);
                    } else if(ohje[0] == "kehys-2"){
                        //console.log("kehys-2: "+ohje[1]);
                    } else {
                        //console.log("ID: "+ohje[0]+", abi: " + ohje[2] +     ", sivu: "+ohje[3]);
                        var lisattava = document.getElementById(ohje[0]);
                        if(ohje[1] == "luokkaotsikkoviiva"){
                            //getSisaltoAlue(ohje[3]).appendChild(lisattava);
                        } else {
                            if(ohje[3] % 2 == 0){
                                if(ohje[1] == "profiilikuva"){
                                    lisattava.className = "profiilikuva-vasen";
                                } else if(ohje[1] == "nimi") {
                                    lisattava.className = "nimi-vasen";
                                } else{}
                            }
                            getAbiKehysNimella(ohje[2], ohje[3]).appendChild(lisattava);
                        }
                    }
                }
            }
        </script>
    </body>
</html>


