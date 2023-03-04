<?php

    require_once __DIR__ . '/../vendor/autoload.php';
   
    use Dialogflow\WebhookClient;
    use App\Controller\User;
    use App\Controller\Payment;

    $agent  = new WebhookClient(json_decode(file_get_contents('php://input'), true));
    $intent = $agent->getIntent();
    
    switch ($intent) {
        case 'Default Welcome Intent':
            $agent->reply("Testando conexão com o webhook.");
        break;

        case 'pagamento':
            $parameters     = $agent->getParameters();
            $whatsAppNumber = $parameters['whatsapp']; // ex: +55 (11) 99999-9999 ou 5511999999999.
            $userName       = $parameters['name'];    // nome do cliente.

            try {
                $user = new User();
                $user->register($whatsAppNumber, $userName);

                $payment = new Payment();
                $payment->findByUserId($user->getId());
                
                if(!$payment->getStatus()){

                    $payment->createPayment([
                        'userId'            => $user->getId(),
                        'title'             => "Nome do Produto",
                        'description'       => "Descrição sobre este pagamento.",
                        'unitPrice'         => 9.99,
                        'quantity'          => 1,
                        'externalReference' => rand(0000000000, 9999999999)
                    ]);

                    $agent->reply("Acabei de gerar a sua fatura.\n\nRealize o pagamento pelo link abaixo:");
                    $agent->reply($payment->getLinkPayment());

                } else if($payment->getStatus() === 'pending'){
                    $agent->reply("Você já têm uma fatura pedente.\n\nRealize o pagamento pelo link a seguir:");
                    $agent->reply($payment->getLinkPayment());
                
                } else if($payment->getStatus() === 'approved'){
                    $agent->reply("Sua fatura já foi paga! :)");
                }

            } catch (\Exception $e) {
                // mensagem de erro.
                $agent->reply($e->getMessage());
            }

        break;
        
    }

    header('Content-type: application/json');
    echo json_encode($agent->render());