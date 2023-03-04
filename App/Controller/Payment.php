<?php

    namespace App\Controller;
    use App\Dao\PaymentDAO;
    use MercadoPago;

    class Payment {

        private User $user;
        private $id;
        private $title;
        private $description;
        private $quantity;
        private $unitPrice;
        private $linkPayment;
        private $externalReference;
        private $status;
        private $date;

        // SandBox or Production
        const ACCESS_TOKEN = "YOUR_MERCADO_PAGO_ACCESS_TOKEN";

        function __construct(int $id = null){
            $id ? $this->findById($id) : false;
        }

        function findById(int $id){
            $select = PaymentDAO::findById($id);
            $select ? $this->setAttributes($select) : false;
        }

        function findByUserId(int $userId){
            $select = PaymentDAO::findByUserId($userId);
            $select ? $this->setAttributes($select) : false;
        }

        function findByExternalReference($externalReference){
            $select = PaymentDAO::findByExternalReference($externalReference);
            $select ? $this->setAttributes($select) : false;
        }

        function setAttributes(array $select){
            $this->user               = new User($select['userId']);
            $this->id                 = $select['id'];
            $this->title              = $select['title'];
            $this->quantity           = $select['quantity'];
            $this->unitPrice          = $select['unitPrice'];
            $this->linkPayment        = $select['linkPayment'];
            $this->externalReference  = $select['externalReference'];
            $this->status             = $select['status'];
            $this->date               = $select['date'];
        }

        function getUser(){
            return $this->user;
        }

        function getId(){
            return $this->id;
        }

        function getTitle(){
            return $this->title;
        }

        function getDescription(){
            return $this->description;
        }

        function getQuantity(){
            return $this->quantity;
        }

        function getUnitPrice(){
            return $this->unitPrice;
        }

        function getLinkPayment(){
            return $this->linkPayment;
        }

        function getStatus(){
            return $this->status;
        }

        function getDate(){
            return $this->date;
        }

        function createPayment(array $data){

            $invoiceId = PaymentDAO::saveInvoiceInDatabase([
                'userId'            => $data['userId'],
                'title'             => $data['title'],
                'quantity'          => $data['quantity'],
                'unitPrice'         => $data['unitPrice'],
                'externalReference' => $data['externalReference'],
                'linkPayment'       => self::generateLinkPayment($data),
                'status'            => "pending",
                'date'              => date('Y-m-d H:i:s')
            ]);

            $invoiceId ? $this->findById($invoiceId) : throw new \Exception("Erro ao criar a fatura");
        }

        private static function generateLinkPayment(array $data){

            // Configura credenciais. Production or SandBox AccessToken
            MercadoPago\SDK::setAccessToken(self::ACCESS_TOKEN);
  
            // Cria um objeto de preferência
            $preference = new MercadoPago\Preference();
        
            $preference->payment_methods = array(
                "excluded_payment_types" => array(
                    array("id" => "ticket") // remove "boleto" como método de pagamento.
                ),
                // parcelas
                "installments" => 1
            );
        
            // Cria um item na preferência
            $item                           = new MercadoPago\Item();
            $item->title                    = $data['title'];
            $item->quantity                 = $data['quantity'];
            $item->unit_price               = $data['unitPrice'];
            $item->currency_id              = "BRL";
            $preference->external_reference = $data['externalReference'];
            $preference->items = array($item);
            $preference->save();
            
            $linkPayment = $preference->init_point;
            return $linkPayment;
      }


        public function verify($paymentId){
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.mercadopago.com/v1/payments/".$paymentId."?access_token=".self::ACCESS_TOKEN,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_RETURNTRANSFER => true
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response, true);

            if(isset($response['status'])){

                $invoice = new Payment();
                $invoice->findByExternalReference($response['external_reference']);
                    
                if($response['status'] == "approved" AND !empty($invoice->getId())){

                    // Atualiza o status do pagamento no banco de dados.
                    PaymentDAO::updateStatusById($invoice->getId(), "approved");

                    // API BRASIL - Envia a notificação para o usuário avisando que o pagamento foi aprovado.
                    // https://plataforma.apibrasil.com.br/plataforma/myaccount/apicontrol
                    $credencials = [ 
                        'SecretKey:     YOUR_SECRET_KEY',
                        'PublicToken:   YOUR_PUBLIC_TOKEN',
                        'DeviceToken:   YOUR_DEVICE_TOKEN',
                        'Authorization: Bearer YOUR_BEARER_TOKEN'
                    ];

                    $whatsApp = new \App\Api\WhatsApp($credentials);

                    $message = "Olá, ".$invoice->getUser()->getName()."! Seu pagamento foi aprovado! ✅";
                    $whatsApp->sendTextMessage($invoice->getUser()->getWhatsAppNumber(), $message);
                }
            }
        }


    }