<?php 

   

    class basedatos{



        public static function conectar(){

           $base = new mysqli('104.156.62.240','nigcomgt_dflores','!Forti%32','nigcomgt_bcertifica');

           $base->query("SET NAMES 'utf8'");

           return $base;

           



        }



    }







    







?>