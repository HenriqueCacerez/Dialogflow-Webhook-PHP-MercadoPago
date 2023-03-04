<?php
        

    $json_event = file_get_contents('php://input', true);
    $event      = json_decode($json_event);

        if($event->data->id){

            require_once __DIR__ . '/../../vendor/autoload.php';
            
            $payment = new \App\Controller\Payment();
            $payment->verify($event->data->id);
    
        }

