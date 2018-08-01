<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Title </title>
</head>

<body>
    <?php
        $string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
        $dico = explode("\n", $string);
        //print_r($dico);
        echo "Nombre de mot dans le dictionnaire: ".count($dico)."<br>";
        function is15 ($vStr){
            return strlen($vStr)==15;
        }
        echo "Nombre de mot de 15 caractères: ".count(array_filter($dico, "is15"))."<br>";
    
        function containW ($vStr){
            return (strpos($vStr,'w')!==false);
        }
        echo "Nombre de mot contenant la lettre W: ".count(array_filter($dico,'containW'))."<br>";
    
        function endsWithQ ($vStr){
            return substr($vStr, -1)=='q';
        }
        echo "Nombre de mot finissant par Q: ".count(array_filter($dico,'endsWithQ'))."<br>";
        
        $string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
        $brut = json_decode($string, true);
        $top = $brut["feed"]["entry"]; # liste de films
        echo "<h2>top 10 films : </h2>";
        for($i=0;$i<10;$i++){
            echo $top[$i]['im:name']["label"]."<br>";
        }
        echo "<h2>classement du film « Gravity »</h2>";
        foreach ($top as $idx => $value){
            if($value['im:name']['label']=="Gravity"){
                echo $idx;
            }
        }
        echo "<br>";
        echo "<h2>Films sortis avant 2000: </h2>";
        function isBefore2000($mov){
            return date("Y",strtotime($mov["im:releaseDate"]["label"]))<2000;
        }
        echo count(array_filter($top,'isBefore2000'))."<br>";
        echo "<h2> Film ...</h2>";
        $nameold=$top[0]['im:name']["label"];
        $dateold=strtotime($top[0]["im:releaseDate"]["label"]);
        $namerec=$top[0]['im:name']["label"];
        $daterec=strtotime($top[0]["im:releaseDate"]["label"]);
        foreach($top as $idx => $value){
            $yearcurrent = strtotime($value["im:releaseDate"]["label"]);
            if($yearcurrent > $daterec){
                $daterec = $yearcurrent;
                $namerec = $value['im:name']["label"];
            }
            if($yearcurrent < $dateold){
                $dateold = $yearcurrent;
                $nameold = $value['im:name']["label"];
            }
        }
        echo "... le plus vieux : ".$nameold." le ".date("d/m/Y",$dateold)."<br>";
        echo "... le plus récent : ".$namerec." le ".date("d/m/Y",$daterec)."<br>";
        echo "<h2>catégorie de film le plus représenté: </h2>";
        $arrcat = array_count_values(array_column(array_column(array_column($top, 'category'), 'attributes'),'label'));
        echo array_search(max($arrcat), $arrcat)."<br>";
        echo "<h2>réalisateur le plus présent dans le top100: </h2>";
        $arrreal = array_count_values(array_column(array_column($top, 'im:artist'),'label'));
        echo array_search(max($arrreal), $arrreal)."<br>";
        echo "<h2>Combien cela coûterait-il d'acheter le top10 sur iTunes ? de le louer ?</h2>";
        $price = 0;
        $priceloc = 0;
        for($i=0;$i<10;$i++){
            $price+=$top[$i]["im:price"]["attributes"]["amount"];
            if(isset($top[$i]["im:rentalPrice"])){
                $priceloc+=$top[$i]["im:rentalPrice"]["attributes"]["amount"];
            }else{
                $priceloc+=$top[$i]["im:price"]["attributes"]["amount"];
            }
        }
        echo "Prix à l'achat: ".$price."<br>";
        echo "Prix à la location (ou achat pour ceux qu'on ne peut louer): ".$priceloc."<br>";
        echo "<h2>Le mois ayant vu le plus de sorties au cinéma</h2>";
        $arrdate = array_column(array_column($top,'im:releaseDate'),'label');
        function getMonth($var){
            return date("F",strtotime($var));
        }
        $arrMonth = array_count_values(array_map("getMonth", $arrdate));
        $arrfullmonth = array_keys($arrMonth,max($arrMonth));
        foreach($arrfullmonth as $key){
            echo $key."&nbsp;";
        }
        echo "<br>";
        echo "<h2> les 10 meilleurs films à voir en ayant un budget limité</h2>";
        //$arrPrice = array_column(array_column(array_column($top, 'im:price'),'attributes'),'amount');
        $arrPrice=[];
        foreach($top as $value){
            if (isset($value['im:rentalPrice']["attributes"]["amount"])){
                array_push($arrPrice,$value['im:rentalPrice']["attributes"]["amount"]);
            } else{
                array_push($arrPrice,$value['im:price']["attributes"]["amount"]);
            }
        }
        asort($arrPrice);
        $arridx = array_keys($arrPrice);
        $arridx = array_slice($arridx,0,10);
        $totprice=0;
        foreach ($arridx as $value){
            echo $top[$value]['im:name']["label"]." : ".$arrPrice[$value]."<br>";
            $totprice+=$arrPrice[$value];
        }
        echo "prix total: ".$totprice." €";
    ?>
</body>

</html>
