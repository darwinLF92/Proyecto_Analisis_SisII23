<?php 

   

    class basedatosap{



        public static function conectar(){

           $base = new mysqli('localhost','root','cocorococon','apdls');

           $base->query("SET NAMES 'utf8'");

           return $base;

           



        }



    }







    







?>