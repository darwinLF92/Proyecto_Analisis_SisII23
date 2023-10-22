<?php 

   

   /*configuracion de base de datos */
   require_once('ConexionBase.php');
   require_once('ConexionBaseap.php');

  





   //echo $otradata;

   $tipofactura = "FCAM";

   $fechaemision = "2022-07-09T22:08:08";

   $codigomoneda ;

   $nitemisor;
   $nombreemisor;
   $codigoestablecimiento;
   $fa;
   $fb;
   $fc;

   $totalfactura;
   $totaliva;
 



function datosencabezado() {
    $conexion = basedatos::conectar();
     global $nitemisor;
     global $codigomoneda;
     global $nombreemisor;    

    $sql = "SELECT Nit,Nombre_Emisor,Codigo_Moneda,Codigo_Establecimiento,Direccion,Codigo_Postal,Municipio,Departamento,Pais,Nombre_Comercial,Afiliacion_Iva FROM datos_emisor WHERE Numero_Cliente = 1";

    $resultado = $conexion->query($sql);

    if($resultado->num_rows > 0){

        while($fila= $resultado -> fetch_assoc()){
              $codigomoneda = $fila['Codigo_Moneda'];
              $nitemisor = $fila['Nit'];
              $nombreemisor = $fila['Nombre_Emisor'];


              echo 'mensaje ' .$nitemisor;

        }
    }else{

         echo 'error base' .  $conexion->error;

    }

}
   

datosencabezado();


//echo 'mensaje ' .$nitemisor;

    


    



$fe = "hola"; 


 $fa = "< # ?xml version='1.0' encoding='UTF-8'?>";


    $fa = $fa . '< # dte:GTDocumento xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     

                 # xmlns:dte ="http://www.sat.gob.gt/dte/fel/0.2.0" Version = "0.1" > 

                   < # dte:SAT ClaseDocumento="dte"> 

            < # dte:DTE ID="DatosCertificados"> 

                <# dte:DatosEmision ID="DatosEmision"> 

                    <# dte:DatosGenerales Tipo="'.$tipofactura. '" FechaHoraEmision ="' .$fechaemision. '"

                        CodigoMoneda = "' .$codigomoneda. '"/>

                    < # dte:Emisor NITEmisor= "' .$nitemisor. '" NombreEmisor ="Allan Bonilla" CodigoEstablecimiento ="1"

                        NombreComercial="EL COLOCHO SONRIENTE" AfiliacionIVA ="GEN"> 

                        <# dte:DireccionEmisor>

                            <# dte:Direccion>2 CALLE 10-21 Z. 1<#/dte:Direccion>

                            <# dte:CodigoPostal>01001<#/dte:CodigoPostal>

                            <# dte:Municipio>GUATEMALA<#/dte:Municipio>

                            <# dte:Departamento>GUATEMALA<#/dte:Departamento>

                            <# dte:Pais>GT<#/dte:Pais>

                        </# dte:DireccionEmisor>

                    </# dte:Emisor>

                    <# dte:Receptor NombreReceptor="FRANCISCO JAVIER CENTENO MARTINEZ " IDReceptor ="5932068" CorreoReceptor="gerencia.tecnosola@gmail.com">

                        <# dte:DireccionReceptor>

                            <# dte:Direccion>18 AVENIDA 18-63 ZONA 5  FUENTES DEL VALLE II <#/dte:Direccion>

                            <# dte:CodigoPostal>01001<#/dte:CodigoPostal>

                            <# dte:Municipio>VILLA NUEVA<#/dte:Municipio>

                            <# dte:Departamento>GUATEMALA<#/dte:Departamento>

                            <# dte:Pais>GT<#/dte:Pais>

                        </# dte:DireccionReceptor>

                    </# dte:Receptor>

                    <# dte:Frases>

                        <# dte:Frase TipoFrase="1" CodigoEscenario ="1" />

                    </# dte:Frases>
                    <# dte:Items>';
                   
                                        
                function buscardetalle(){

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
                    WHERE phppos_sales_items.sale_id = 2";
                     
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
                           //a
                           $preciototal = $cantidadfila * $precio;
                           $preciototal = number_format($preciototal, 2);
                           $montograbable = $preciototal/1.12;
                           $montograbable = number_format($montograbable, 2);
                           $iva = ($preciototal - $montograbable);

                           $totalfactura = ($totalfactura + $preciototal);
                           $totaliva = ($totaliva + $iva);
                         
   
                          $fa =  $fa . '<# dte:Item  BienOServicio ="B" NumeroLinea="'. $numerofila . '"> 

                           <# dte:Cantidad>'. $cantidadfila . '<#/dte:Cantidad>

                           < # dte:UnidadMedida>UNI<#/dte:UnidadMedida>

                           < # dte:Descripcion>"' .$descripcionfila .'"<#/dte:Descripcion>

                           < # dte:PrecioUnitario>' .$precio. '<#/dte:PrecioUnitario>

                           //a
                           < # dte:Precio>' . $preciototal . '<#/dte:Precio>

                           < # dte:Descuento>0.00<#/dte:Descuento>

                           < # dte:Impuestos>

                               <# dte:Impuesto>

                                   < # dte:NombreCorto>IVA<#/dte:NombreCorto>

                                   < # dte:CodigoUnidadGravable>1<#/dte:CodigoUnidadGravable> 

                                   < # dte:MontoGravable>' .$montograbable .'<#/dte:MontoGravable>

                                   < # dte:MontoImpuesto>' .$iva. '<#/dte:MontoImpuesto>

                               </ # dte:Impuesto>

                           </ # dte:Impuestos>

                           < # dte:Total>' .$preciototal .'<#/dte:Total>

                       </ # dte:Item>';
                   
                    
   
                       }
                   }else{
                            echo "no hay datos";

                   }
                      
                   
               }

             buscardetalle(); 
             //echo $fa;

             $totaliva = number_format($totaliva, 2);
             $totalfactura = number_format($totalfactura, 2);

           $fa = $fa .'</ # dte:Items>

                   <#dte:Totales>

                       <#dte:TotalImpuestos>

                           <#dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto ="' . $totaliva.'"/>

                        </#dte:TotalImpuestos>

                        <#dte:GranTotal>' . $totalfactura .'<#/dte:GranTotal>

                   </#dte:Totales>

                  <#dte:Complementos>

                     <#dte:Complemento

                       #xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0"

                       #URIComplemento="cfc" NombreComplemento="FCAMB" IDComplemento="ID"

                       #xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0 GT_Complemento_Cambiaria-0.1.0.xsd">

                      <#cfc:AbonosFacturaCambiaria Version="1">

                         <#cfc:Abono>

                             <#cfc:NumeroAbono>1<#/cfc:NumeroAbono>

                             <#cfc:FechaVencimiento>2021-05-19<#/cfc:FechaVencimiento>

                             <#cfc:MontoAbono>190.37<#/cfc:MontoAbono>

                         </#cfc:Abono>

                       </#cfc:AbonosFacturaCambiaria>

                     </#dte:Complemento>

                   </#dte:Complementos>

                </#dte:DatosEmision>

            </#dte:DTE>

           <#dte:Adenda>

           <#dtecomm:Informacion_COMERCIAL xmlns:dtecomm="https://www.digifact.com.gt/dtecomm" xsi:schemaLocation="https://www.digifact.com.gt/dtecomm">

             <#dtecomm:InformacionAdicional Version="7.1234654163">

                 <#dtecomm:REFERENCIA_INTERNA>FEL-6798000006<#/dtecomm:REFERENCIA_INTERNA>

                 <#dtecomm:FECHA_REFERENCIA>2022-04-30T15:56:31<#/dtecomm:FECHA_REFERENCIA>

                 <#dtecomm:VALIDAR_REFERENCIA_INTERNA>VALIDAR<#/dtecomm:VALIDAR_REFERENCIA_INTERNA>

                 <#dtecomm:INFORMACION_ADICIONAL>

                          <#dtecomm:Detalle Data="FECHAVENCIMIENTO" Value ="2022-04-30"/>

                          <#dtecomm:Detalle Data="VENDEDOR" Value="Dorothy L. Beck"/>

                          <#dtecomm:Detalle Data="OTROS" Value="0"/>

                          <#dtecomm:Detalle Data="OBSERVACIONES" Value="593206-8"/>

                 <#/#dtecomm:INFORMACION_ADICIONAL>

                
             <#/#dtecomm:InformacionAdicional>

           <#/#dtecomm:Informacion_COMERCIAL>

           <#/#dte:Adenda>

        <#/#dte:SAT>

    <#/#dte:GTDocumento>';

    






    $url="https://felgttestaws.digifact.com.gt/gt.com.fel.api.v3/api/FelRequestV2?NIT=000044653948&TIPO=CERTIFICATE_DTE_XML_TOSIGN&FORMAT=XML,PDF&USERNAME=TECNOSOLAT_TEST";



    $ch = curl_init();



    if(!$ch){

        die("Coludn't initialize a cURL handle");

    }



    curl_setopt($ch, CURLOPT_URL,$url);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    curl_setopt($ch,CURLOPT_TIMEOUT,60);

    curl_setopt($ch,CURLOPT_POST,true);

    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml','Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IkdULjAwMDA0NDY1Mzk0OC5URUNOT1NPTEFUX1RFU1QiLCJuYmYiOjE2NTY4MTg2MDIsImV4cCI6MTY4NzkyMjYwMiwiaWF0IjoxNjU2ODE4NjAyLCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQ5MjIwIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0OTIyMCJ9.9OR-hKhlgvUahflMarmuYvxZZtqgMU792jGBDpZ6DZQ'));



   // curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/xml'));

    curl_setopt($ch,CURLOPT_POSTFIELDS,$fa);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);

    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);

    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);





    $result = curl_exec($ch);







    $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

   
    


  // echo  $result;



   curl_close($ch);

 // settype($fa,'string');

 echo nl2br($fa);
 //echo $fb;
 //echo $fc;

  
 // var_dump($fa);

  //print_r($fa);

 // printf($fa)
   
   //echo 'segundo mensaje ' . $nitemisor;



    //echo 'HTTP CODE ' . $httpcode;




?>
                
              