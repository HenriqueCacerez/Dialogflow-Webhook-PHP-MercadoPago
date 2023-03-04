<?php

    namespace App\Dao;
    use App\Database\Connection;

    class PaymentDAO {

        const TABLE = 'payments';

        static function findById($invoiceId){
            $query = "SELECT * FROM ".self::TABLE." WHERE id = :id LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':id', $invoiceId);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        }

        static function findByUserId($userId){
            $query = "SELECT * FROM ".self::TABLE." WHERE userId = :userId LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        }

        static function findByExternalReference($externalReference){
            $query = "SELECT * FROM ".self::TABLE." WHERE externalReference = :externalReference LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':externalReference', $externalReference);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        }

        static function saveInvoiceInDatabase(array $data){

            $conn = Connection::getConn();

            $query = "INSERT INTO ".self::TABLE." (userId, title, quantity, unitPrice, externalReference, linkPayment, status, date) VALUES (:userId, :title, :quantity, :unitPrice, :externalReference, :linkPayment, :status, :date)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':userId',            $data['userId']);
            $stmt->bindValue(':title',             $data['title']);
            $stmt->bindValue(':quantity',          $data['quantity']);
            $stmt->bindValue(':unitPrice',         $data['unitPrice']);
            $stmt->bindValue(':externalReference', $data['externalReference']);
            $stmt->bindValue(':linkPayment',       $data['linkPayment']);
            $stmt->bindValue(':status',            $data['status']);
            $stmt->bindValue(':date',              $data['date']);
            
            $stmt->execute();
            return $conn->lastInsertId() ? $conn->lastInsertId() : false;
        }

        static function updateStatusById($id, $status){
            $query = "UPDATE ".self::TABLE." SET status = :status, dateUpdate = :dateUpdate WHERE id = :id LIMIT 1";
            $stmt = Connection::getConn()->prepare($query);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':dateUpdate', date('Y-m-d H:i:s'));
            
            return $stmt->execute() ? true : false;
        }


    }