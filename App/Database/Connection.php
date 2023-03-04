<?php

    namespace App\Database;

    class Connection {

        const DB_DRIVE = 'mysql';
        const DB_HOST  = 'localhost';
        const DB_USER  = 'root';
        const DB_PASS  = '';
        const DB_NAME  = 'dialogflow';

        static function getConn(){
            try {
                $pdo = new \PDO(self::DB_DRIVE.':host='.self::DB_HOST.';dbname='.self::DB_NAME.";charset=utf8", self::DB_USER, self::DB_PASS);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (\PDOException $e) {
                die('ERRO:' . $e->getMessage());
            }
        }
    }