<?php 

$numerooperacion = $_GET['autorizacion'];
$operacionactual = $_GET['operacion'];


$url="https://felgttestaws.digifact.com.gt/gt.com.fel.api.v3/api/FelRequest?NIT=000044653948&TIPO=SHARED_GETDTEINFO&FORMAT=XML,PDF&GUID=". $numerooperacion ."&USERNAME=TECNOSOLAT_TEST";
$ch = curl_init();

echo $url;

if(!$ch){
    die("Coludn't initialize a cURL handle");
}

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_TIMEOUT,60);
curl_setopt($ch,CURLOPT_HTTPGET,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IkdULjAwMDA0NDY1Mzk0OC5URUNOT1NPTEFUX1RFU1QiLCJuYmYiOjE2NTY4MTg2MDIsImV4cCI6MTY4NzkyMjYwMiwiaWF0IjoxNjU2ODE4NjAyLCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQ5MjIwIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0OTIyMCJ9.9OR-hKhlgvUahflMarmuYvxZZtqgMU792jGBDpZ6DZQ'));

// curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml'));
//curl_setopt($ch,CURLOPT_POSTFIELDS,$xmldata);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);


$result = curl_exec($ch);



$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

$myarray = json_decode($result);
//var_dump($myarray);

foreach($myarray as $indice => $descripcion){
    if($indice=="ResponseDATA3"){
       // $data = base64_encode($descripcion);
        file_put_contents('Factura.pdf',base64_decode($descripcion));

        //echo $data;
    }

    $filename = basename('Factura.pdf');
    $filepath = $filename;
  
    $filename =  $operacionactual . $filename;

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/pdf");
    header("Content-Transfer-Encoding: binary");

    readfile($filepath);
    
  //  echo "$indice: $descripcion <br>";
}

//echo  $result;

curl_close($ch);

//echo 'HTTP CODE ' . $httpcode;


?>