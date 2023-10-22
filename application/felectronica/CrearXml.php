<!DOCTYPE html>
<html lang="es">


<head>
      <title>CentraInve - Seleccionar Producto</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script> 
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
      <!--agregar bootstrap--->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="estilobarra.css">
        <!--agrega jscript -->
       
    

</head>

<body>
<div class="container">
<?php 

   

   /*configuracion de base de datos */
   require_once('ConexionBase.php');
   require_once('ConexionBaseap.php');

  

   //Operacion global
   $operacionactual;

   $operacion = $_GET["par"];
       
   $operacion = intval(preg_replace('/[^0-9]+/', '', $operacion), 10); 

   //echo $operacion;

   
   $operacionactual = $operacion;

   

   //echo $otradata;

   $tipofactura = "FACT";

   $fechaemision = "2022-07-15T22:08:08";

   $codigomoneda ;

   $nitemisor;
   $nombreemisor;
   $codigoestablecimiento;
   $nombrecomercial;
   $afiliacioniva;
   $direccionemisor;
   $codigopostal;  
   $municipio;
   $departamento;
   $pais;


   //variables para el cliente
   $nombrecliente;
   $nitcliente;
   $correoelectronicocliente;
   $direccioncliente;
   $municipiocliente;
   $departamentocliente;
   $paiscliente;
   $zipcliente;

   //variables para la configucion
   $direccion_factura;
   $token_factura;

   $errorgeneral;




   $fa;
   $fb;
   $fc;
   $totalfactura;
   $totaliva;
 

function configuracion(){
    global $direccion_factura;
    global $token_factura;
    global $errorgeneral;

    $conexion = basedatos::conectar();


    $sqlcon = "SELECT Direccion_Factura,Token_Factura FROM configuracion_fel";

    $resultado = $conexion->query($sqlcon);
    
    if($resultado->num_rows > 0){

        while($filar = $resultado-> fetch_assoc()){
            $direccion_factura = $filar['Direccion_Factura'];
            $token_factura = $filar['Token_Factura'];

         //   echo $direccion_factura;
            echo $token_factura;
        }


    }else{
        $errorgeneral = "No se encontro configuracion";
        echo $errorgeneral;
    }
    

}   


function datosencabezado() {
    $conexion = basedatos::conectar();
     global $nitemisor;
     global $codigomoneda;
     global $nombreemisor; 
     global $nombrecomercial;   
     global $afiliacioniva;  
     global $nombreemisor; 
     global $codigoestablecimiento;  
     global $direccionemisor;
     global $codigopostal;
     global $municipio;
     global $departamento;
     global $pais;

    $sql = "SELECT Nit,Nombre_Emisor,Codigo_Moneda,Codigo_Establecimiento,Direccion,Codigo_Postal,Municipio,Departamento,Pais,Nombre_Comercial,Afiliacion_Iva FROM datos_emisor WHERE Numero_Cliente = 1";

    $resultado = $conexion->query($sql);

    if($resultado->num_rows > 0){

        while($fila= $resultado -> fetch_assoc()){
              $codigomoneda = $fila['Codigo_Moneda'];
              $nitemisor = $fila['Nit'];
              $nombreemisor = $fila['Nombre_Emisor'];
              $nombrecomercial = $fila['Nombre_Comercial']; 
              $afiliacioniva = $fila['Afiliacion_Iva'];
              $nombreemisor = $fila['Nombre_Emisor'];
              $codigoestablecimiento = $fila['Codigo_Establecimiento']; 
              $direccionemisor = $fila['Direccion'];
              $codigopostal = $fila['Codigo_Postal'];
              $municipio = $fila['Municipio'];
              $departamento = $fila['Departamento'];
              $pais = $fila['Pais'];




             // echo 'mensaje ' .$nitemisor;

        }
    }else{

         echo 'error base' .  $conexion->error;

    }

    

}
 
//bucar informacion del cliente
function informacioncliente(){
    global $operacionactual;
    global $numerocliente;

   global $nombrecliente;
   global $nitcliente;
   global $correoelectronicocliente;
   global $direccioncliente;
   global  $municipiocliente;
   global $departamentocliente;
   global $paiscliente;
   global $zipcliente; 
   global $fechaemision;




    //sacar la transaccion actual
    $conexionc = basedatosap::conectar();

    $sqlap = "SELECT 
    `phppos_sales`.`sale_id`,
   DATE( `phppos_sales`.`sale_time`) as fechahoy,
   TIME( `phppos_sales`.`sale_time`) as hora,
    `phppos_sales`.`customer_id`,
    `phppos_sales`.`employee_id`
     FROM `phppos_sales` WHERE sale_id = $operacionactual";

    

     $resultadop = $conexionc->query($sqlap);

    


     if($resultadop->num_rows > 0){

           //buscar el cliente
           while($filac = $resultadop->fetch_assoc()){
             
               $numerocliente = $filac['customer_id']; 
              // echo 'este es el cliente' . $numerocliente;     
              $fechasinhora = $filac['fechahoy'];
              $hora = $filac['hora'];

              $fechaemision = $fechasinhora . "T" . $hora;
             
             
           }

        
           
       //sacar los datos del cliente
       $sqlcus = "SELECT 
       `phppos_people`.`person_id`,
       `phppos_people`.`first_name`,
       `phppos_people`.`last_name`,
       phppos_people.`phone_number`,
       `phppos_people`.`email`,
       `phppos_people`.`address_1`,
       `phppos_people`.`phone_number`,
       `phppos_people`.`city`,
       phppos_people.`state`,
       phppos_people.zip
       FROM `phppos_people`
       WHERE person_id = $numerocliente"; 
       
       //echo $sqlcus;


       $resulatadocliente = $conexionc->query($sqlcus);

       if($resulatadocliente->num_rows > 0){
             
            while($filar = $resulatadocliente->fetch_assoc()){
                $nombrecliente = $filar['first_name'] . " " . $filar['last_name'];
                $nitcliente = $filar['phone_number'];
                $correoelectronicocliente = $filar['email'];
                $direccioncliente = $filar['address_1'];
                $municipiocliente = $filar['city'];
                $departamentocliente = $filar['state'];
                $zipcliente = $filar['zip'];    
                $paiscliente = "GT";
                     

               if($zipcliente==""){
                   $zipcliente = "e20001";
               }else{
                   $zipcliente = "";
               }

              // echo 'zipcliente' . $zipcliente;

            }  

       }
       


     }else{
        echo 'error base' .  $conexionc->error;

     }


}



//configuracion();
datosencabezado();
informacioncliente();



    


    






 $fa = "<?xml version='1.0' encoding='UTF-8'?>";


    $fa = $fa . '<dte:GTDocumento xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xmlns:dte ="http://www.sat.gob.gt/dte/fel/0.2.0" Version = "0.1" > 

                 

                   <dte:SAT ClaseDocumento="dte"> 

            <dte:DTE ID="DatosCertificados"> 

                <dte:DatosEmision ID="DatosEmision"> 

                    <dte:DatosGenerales Tipo="'.$tipofactura. '" FechaHoraEmision ="' .$fechaemision. '"

                        CodigoMoneda = "' .$codigomoneda. '"/>

                    <dte:Emisor NITEmisor= "' .$nitemisor. '" NombreEmisor ="' .$nombreemisor. '" CodigoEstablecimiento ="'.$codigoestablecimiento.'"

                        NombreComercial="' .$nombrecomercial. '" AfiliacionIVA ="' . $afiliacioniva.'"> 

                        <dte:DireccionEmisor>

                            <dte:Direccion>'. $direccionemisor .'</dte:Direccion>

                            <dte:CodigoPostal>'. $codigopostal.'</dte:CodigoPostal>

                            <dte:Municipio>' . $municipio. '</dte:Municipio>

                            <dte:Departamento>' . $departamento. '</dte:Departamento>

                            <dte:Pais>' .$pais.'</dte:Pais>

                        </dte:DireccionEmisor>

                    </dte:Emisor>

                    <dte:Receptor NombreReceptor="' .$nombrecliente.'" IDReceptor ="' . $nitcliente .'" CorreoReceptor="' . $correoelectronicocliente .'">

                        <dte:DireccionReceptor>

                            <dte:Direccion>"' .$direccioncliente .' "</dte:Direccion>

                            <dte:CodigoPostal>20001</dte:CodigoPostal>

                            <dte:Municipio>'. $municipiocliente .'</dte:Municipio>

                            <dte:Departamento>'.$departamentocliente. '</dte:Departamento>

                            <dte:Pais>' . $paiscliente . '</dte:Pais>

                        </dte:DireccionReceptor>

                    </dte:Receptor>

                    <dte:Frases>

                        <dte:Frase TipoFrase="1" CodigoEscenario ="1" />

                    </dte:Frases>
                    <dte:Items>';
                   
                                        
                function buscardetalle(){

                   global $operacionactual; 
                   global $fa; 
                   global $totalfactura;
                   global $totaliva;                  
                    //recuperar la informacion
                    $conexionap = basedatosap::conectar();
   
                    $sqldetalle = "SELECT 
                    phppos_sales_items.sale_id,
                    phppos_sales_items.line,
                    phppos_sales_items.item_id,
                    phppos_items.name,
                    phppos_sales_items.quantity_purchased,
                    phppos_sales_items.item_unit_price  
                    FROM phppos_sales_items
                    INNER JOIN phppos_items
                    ON phppos_sales_items.item_id = phppos_items.item_id
                    WHERE phppos_sales_items.sale_id = $operacionactual";

                   // echo $sqldetalle;

                     
                   $detalle = $conexionap->query($sqldetalle);
                    
                  

                   //echo $sqldetalle;
                    
               

                   if($detalle->num_rows > 0){

                    $totalfactura = 0;
                        $totaliva = 0;
   
                       while($filanueva = $detalle -> fetch_assoc()){
                       
                   
   
                           $numerofila = $filanueva['line'];
                           $cantidadfila = $filanueva['quantity_purchased'];
                           $descripcionfila = $filanueva['name'];
                           $precio = $filanueva['item_unit_price'];
                          
                           $preciototal = $cantidadfila * $precio;
                           $preciototal = number_format($preciototal, 2, '.','');
                           $montograbable = $preciototal/1.12;
                           $montograbable = number_format($montograbable, 2, '.','');
                           $iva = ($preciototal - $montograbable);

                           $totalfactura = ($totalfactura + $preciototal);
                           $totaliva = ($totaliva + $iva);

                           

                          // echo $preciototal;

                           
                           
                          

   
                          $fa =  $fa . '<dte:Item  BienOServicio ="B" NumeroLinea="'. $numerofila . '"> 

                           <dte:Cantidad>'. $cantidadfila . '</dte:Cantidad>

                           <dte:UnidadMedida>UNI</dte:UnidadMedida>

                           <dte:Descripcion>' .$descripcionfila .'</dte:Descripcion>

                           <dte:PrecioUnitario>' .$precio. '</dte:PrecioUnitario>

                           <dte:Precio>' . $preciototal . '</dte:Precio>

                           <dte:Descuento>0.00</dte:Descuento>

                           <dte:Impuestos>

                               <dte:Impuesto>

                                   <dte:NombreCorto>IVA</dte:NombreCorto>

                                   <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable> 

                                   <dte:MontoGravable>' .$montograbable .'</dte:MontoGravable>

                                   <dte:MontoImpuesto>' .$iva .'</dte:MontoImpuesto>

                               </dte:Impuesto>

                           </dte:Impuestos>

                           <dte:Total>' . $preciototal .'</dte:Total>

                       </dte:Item>';
                   
                    
   
                       }
                   }else{
                            echo "no hay datos";

                   }
                      
                   
               }

             buscardetalle(); 
             //echo $fa;
             $totaliva = number_format($totaliva, 2, '.','');
             $totalfactura = number_format($totalfactura, 2,'.','');  

           $fa = $fa .'</dte:Items>

                   <dte:Totales>

                       <dte:TotalImpuestos>

                           <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto =" ' .$totaliva .'"/>

                        </dte:TotalImpuestos>

                        <dte:GranTotal>' . $totalfactura .'</dte:GranTotal>

                   </dte:Totales>

             

                </dte:DatosEmision>

            </dte:DTE>

           <dte:Adenda>

           <dtecomm:Informacion_COMERCIAL xmlns:dtecomm="https://www.digifact.com.gt/dtecomm" xsi:schemaLocation="https://www.digifact.com.gt/dtecomm">

             <dtecomm:InformacionAdicional Version="7.1234654163">

                 <dtecomm:REFERENCIA_INTERNA>FEL-1000' . $operacionactual .'</dtecomm:REFERENCIA_INTERNA>

                 <dtecomm:FECHA_REFERENCIA>' .$fechaemision .'</dtecomm:FECHA_REFERENCIA>

                 <dtecomm:VALIDAR_REFERENCIA_INTERNA>VALIDAR</dtecomm:VALIDAR_REFERENCIA_INTERNA>

                 <dtecomm:INFORMACION_ADICIONAL>

                         <dtecomm:Detalle Data="VENDEDOR" Value="0"/>

                          <dtecomm:Detalle Data="OTROS" Value="0"/>

                          <dtecomm:Detalle Data="OBSERVACIONES" Value="' .$operacionactual.'"/>

                 </dtecomm:INFORMACION_ADICIONAL>
                
                
                 </dtecomm:InformacionAdicional>

           </dtecomm:Informacion_COMERCIAL>

           </dte:Adenda>

        </dte:SAT>

    </dte:GTDocumento>';

    



          
         
     $url="https://felgttestaws.digifact.com.gt/gt.com.fel.api.v3/api/FelRequestV2?NIT=000044653948&TIPO=CERTIFICATE_DTE_XML_TOSIGN&FORMAT=XML,PDF&USERNAME=TECNOSOLAT_TEST";
    //  $url = "$direccion_factura";     
      //echo $url;


    $ch = curl_init();



    if(!$ch){

        die("Coludn't initialize a cURL handle");

    }



    curl_setopt($ch, CURLOPT_URL,$url);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    curl_setopt($ch,CURLOPT_TIMEOUT,60);

    curl_setopt($ch,CURLOPT_POST,true);

                                                                                   
  curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml','Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IkdULjAwMDA0NDY1Mzk0OC5URUNOT1NPTEFUX1RFU1QiLCJuYmYiOjE2NTY4MTg2MDIsImV4cCI6MTY4NzkyMjYwMiwiaWF0IjoxNjU2ODE4NjAyLCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQ5MjIwIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0OTIyMCJ9.9OR-hKhlgvUahflMarmuYvxZZtqgMU792jGBDpZ6DZQ'));
  // curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml','Authorization:'.$token_factura. ''));


   // curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml'));

    curl_setopt($ch,CURLOPT_POSTFIELDS,$fa);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);

    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);

    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

    curl_setopt($ch, CURLOPT_TIMEOUT,120);





    $result = curl_exec($ch);







    $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

   
    


 



   curl_close($ch);

 // settype($fa,'string');

 //echo nl2br($fa);
 //echo $fb;
 //echo $fc;

  
 // var_dump($fa);

  //print_r($fa);

  //printf($fa)
   
   //echo 'segundo mensaje ' . $nitemisor;



    //echo 'HTTP CODE ' . $httpcode;


     $medcod = json_decode($result);

     $codigo = $medcod -> Codigo;
     $mensaje   = $medcod->Mensaje;
     $autorizacion = $medcod-> Autorizacion;
     $serie =  $medcod -> Serie;
     $numero = $medcod -> NUMERO;
     $nitcomprador = $medcod -> NIT_COMPRADOR;
     $nombrecomprador = $medcod -> NOMBRE_COMPRADOR;
     $fechacertificacion = $medcod -> Fecha_de_certificacion;
     //echo $mensaje;

     $respuesta;

     if($codigo==3010){
         global $respuesta;

         $respuesta = "FACTURA NO AUTORIZADA. RAZON: podria NO tener un NIT VALIDO";
         
     }elseif($codigo==9022){
         $respuesta = "FACTURA NO AUTORIZADA. RAZON: el numero de referencia ya fue enviado anteriormente";
     }elseif($codigo==1){
         $respuesta = "FACTURA AUTORIZADA";

         

     
     }

     //escribir el log
     $conexion = basedatos::conectar();


     $sqlup = "INSERT INTO logcertificacion(nooperacion,fechacertificacion,codigorespuesta,mensaje,autorizacion,serie,numero,nit,nombre)
              VALUES('$operacionactual',NOW(),'$codigo','$mensaje','$autorizacion','$serie','$numero','$nitcomprador','$nombrecomprador')"; 

 

      $conexion->query($sqlup);            

?>
 
 <div class="row  p-3 mb-2 bg-primary text-white">
        <div class = "col-md-2 col-sm-2"></div>
         <div class = "col-md-2 col-sm-2">Digifact</div>
         <div class = "col-md-4 col-sm-4">Certificacion de Facturas</div>
         <div class = "col-md-4 col-sm-4">Operacion: <?php echo $operacionactual; ?></div>

 </div>

 <div class="row">
       <div class = "col-md-1 col-sm-1"></div>
       <div class = "col-md-10 col-sm-10">

       <div class="tablab border border-primary mt-3">
                  <div class="row col-md-12 col-sm-12">
                        <table class="table table-striped">
                              <thead>                                 
                                    <th>Codigo Respuesta</th> 
                                    <th>Mensaje de respuesta</th> 
                                    <th></th>                                 

                              </thead>
                              <tbody>
                                    <tr>
                                      <td><?php echo $codigo; ?></td>
                                      <td><?php echo $mensaje; ?> </td>
                                      <td><?php echo $respuesta; ?> </td>
                                    </tr>
                                    <tr>

                                    </tr>

                              </tbody>
                            
                              </tbody>
                        </table>    
               


       </div>
       <div class = "col-md-1 col-sm-1"> </div>

 </div> 
  <div class="row">
      <div class ="col-md-12"></div>
  </div>

 <div class="row col-md-12 col-sm-12 mt-12">
     <div class = "col-md-2 col-sm-2"></div>
     <div class = "col-md-4 col-sm-2"><?php if($codigo==1){echo '<input class="btn btn-success" type="submit" style="width:100%" value="Descargar PDF" onclick="fpdf()";>';} ?></div>
     <div class = "col-md-4 col-sm-4"><button class="btn btn-info bi bi-box-arrow-left" style="width:100%;"  onclick="regresar()">Regresar</button></div>
 </div>

 <div class="row md-12">
       <div class = "col-md-1 col-sm-1"></div>
       <div class = "col-md-10 col-sm-10">

       <div class="tablab border border-primary mt-3">
                  <div class="row col-md-12 col-sm-12">
                        <table class="table table-striped">
                              <thead>                                 
                                    <th>Serie</th> 
                                    <th>Numero</th> 
                                    <th>Nit</th>
                                    <th>Nombre</th>
                                    <th>Fecha Certificacion</th>
                                    <th>Autorizacion<th>  

                              </thead>
                              <tbody>
                       
                                      <td><?php echo $serie; ?></td>
                                      <td><?php echo $numero; ?> </td>
                                      <td><?php echo $nitcomprador; ?> </td>
                                      <td><?php echo $nombrecomprador; ?> </td>
                                      <td><?php echo $fechacertificacion; ?> </td>
                                      <td><?php echo $autorizacion; ?> </td>
                                    </tr>
                                    <tr>

                                    </tr>

                              </tbody>
                            
                              </tbody>
                        </table>    
               


       </div>
       <div class = "col-md-1 col-sm-1"> </div>

 </div>

 <!--envio a pdf --->

 <form name="enviarpdf" action="pdf.php" method="GET">
      <input type="hidden" id="numeroautorizacion" name="autorizacion">
      <input type="hidden" id="operactual" name="operacion">

 </form>


 <script>
            function fpdf(){
                  
                
                  nfactura = '<?php echo $autorizacion; ?>';
                  noperacion =  '<?php echo $operacionactual ?>';

                  document.getElementById("numeroautorizacion").value = nfactura;
                  document.getElementById("operactual").value = noperacion;
                  
                  document.enviarpdf.submit();


                      
                
            }

            function regresar(){
                window.history.back();
            }
      </script>

</div>
</body>

<div class= "row border primary">
       <div class="col-md-2 col-sm-2">Log respuesta</div>  
       <div class="col-md-10 col-sm-10"><?php echo $result; ?></div>  

</div>

</html>     
     
              