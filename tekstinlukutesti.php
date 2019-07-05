<html>
    <body>
        
        
            <?php
            $palautusarray = array("taittoohje");
            $siistittyarray = array("taittoohje");
        
            $file_lines = file('taittoohje.txt');
            $ix = 1;
            foreach ($file_lines as $line) {
                $line = preg_replace('/\n/', "", $line);
                array_push($palautusarray,$line);
                //echo $ix.$line.'</br>';
                $ix++;
            }
        
            $i = 1;
            while($i < sizeof($palautusarray)){
                
                array_push($siistittyarray, $palautusarray[i]);
                $i = $i + 2;
            }
            
            
        
            $palautus = json_encode($palautusarray);
            echo $palautus;
            $fkp = fopen('taitto_ohje_2019.json', 'w');
            fwrite($fkp, $palautus);
            fclose($fkp);
            ?>
        
    </body>
</html>