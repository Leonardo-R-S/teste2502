<?php
namespace Apoio;
use PDO;

/**
 * Controla todas as conexões de dados da API
 *
 * @author Anderson
 * @since 18/11/2017
 */
class Conexoes{
    public static function conectar170(){
        $dns = 'mysql:host=192.168.1.170;dbname=kronaone'; //246
        $user = 'integrador';
        $password = 'etltecno';
        
//        $dns = 'mysql:host=127.0.0.1;dbname=kronaone';
//        $user = 'root';
//        $password = '';
        try{
            $conn = new PDO($dns, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_TIMEOUT => "360"));
            return $conn;
        }catch(Execelption $ex){
            throw $ex;
        }
    }
}
