<?php

    namespace App\Dao;
    use App\Database\Connection;

    class UserDAO {

        const TABLE = 'users';

        static function findByWhatsAppNumber($whatsApp){
            $query = "SELECT * FROM ".self::TABLE." WHERE whatsapp_number = :whatsapp_number LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':whatsapp_number', $whatsApp);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        }

        static function findById($userId){
            $query = "SELECT * FROM ".self::TABLE." WHERE id = :id LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        }

        static function register($whatsApp, $name){

            $conn = Connection::getConn();

            $query = "INSERT INTO ".self::TABLE." (name, whatsapp_number) VALUES (:name, :whatsapp_number)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':whatsapp_number', $whatsApp);
            $stmt->execute();

            return $conn->lastInsertId() ? $conn->lastInsertId() : false;
        }


    }