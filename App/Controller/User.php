<?php

    namespace App\Controller;
    use App\Dao\UserDAO;

    class User {
        private $id;
        private $name;
        private $whatsAppNumber;

        function __construct($id = null){
            $id ? $this->findById($id) : false;
        }

        function findById(int $id){
            $select = UserDAO::findById($id);
            $select ? $this->setAttributes($select) : false;
        }

        function findByWhatsAppNumber($whatsApp){
            $select = UserDAO::findByWhatsAppNumber($whatsApp);
            $select ? $this->setAttributes($select) : false;
        }

        private function setAttributes(array $select){
            $this->id             = $select['id'];
            $this->name           = $select['name'];
            $this->whatsAppNumber = $select['whatsapp_number'];
        }

        function getId(){
            return $this->id;
        }

        function getName(){
            return $this->name;
        }

        function getWhatsAppNumber(){
            return $this->whatsAppNumber;
        }

        function register($whatsApp, $name){

            // Validar número de WhatsApp
            $whatsApp = preg_replace('/\D/', '', $whatsApp);

            if(!preg_match('/^\+?\d{10,15}$/', $whatsApp)){
                throw new \Exception('Informe um número de WhatsApp válido. Tente novamente.');
            }

            // Validar nome do usuário
            if (!is_string($name) || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
                throw new \Exception('Informe um nome válido. Tente novamente.');
            }

            if (strlen($name) > 50) {
                throw new \Exception('O nome informado é muito grande! Tente novamente.');
            }

            // Verifica se o usuário já foi cadastrado no banco de dados.
            $this->findByWhatsAppNumber($whatsApp);

            // Se não existir cadastro, salvará no banco de dados.
            if(!$this->getId()){
                $userId = UserDAO::register($whatsApp, $name);
                $userId ? $this->findById($userId) : throw new \Exception("Erro ao realizar o cadastro. Tente novamente.");
            }

        }


    }