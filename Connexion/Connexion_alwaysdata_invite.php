<?php

class Database
{
    private static $dbHost = "mysql-joannesperret.alwaysdata.net";
    private static $dbName = "joannesperret_cours";
    private static $dbUser = "212504_invite";
    private static $dbUserPassword = "invite_12345";
    private static $connection = null; 
    public static function connect()
         {
             try
                       {
                         self::$connection = new PDO("mysql:host=" .self::$dbHost . ";dbname=" .self::$dbName,self::$dbUser,self::$dbUserPassword);
                       }
             catch(PDOException $e)
                       {
                          die($e->getMessage());
                       }
             return self::$connection;
         }
             public static function disconnect()
                       {
                         self::$connection = null;
                       }
}



  
?>

