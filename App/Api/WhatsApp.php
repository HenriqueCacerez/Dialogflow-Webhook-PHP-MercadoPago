<?php

    namespace App\Api;

    class WhatsApp {

        private $header  = [];
        private $body    = [];
        private $buttons = [];

        function __construct(array $credencials){
          $this->header = array_merge(['Content-type: application/json'], $credencials);
        }

        function sendTextMessage($phoneNumber, $text){
            $this->body = [
                'number' => self::formatPhoneNumber($phoneNumber),
                'text'   => $text
            ];
            return $this->executeCurl('https://cluster.apigratis.com/api/v1/whatsapp/sendText');
        }

        function sendButtonMessage($phoneNumber, array $content, array $buttons = []){

            $buttons = $buttons ? $buttons : $this->buttons;

            $content['number'] = self::formatPhoneNumber($phoneNumber);
            $this->body = array_merge($content, ['buttons' => $buttons]);

            $response = $this->executeCurl('https://cluster.apigratis.com/api/v1/whatsapp/sendButton');
            $this->buttons = [];
            return $response;
        }

        function sendImage($phoneNumber, $image, $caption = null){
          return $this->sendFile64($phoneNumber, $image, $caption, 'image');
        }

        function sendPDF($phoneNumber, $pdf, $caption = null){
          return $this->sendFile64($phoneNumber, $pdf, $caption, 'pdf');
        }

        private function sendFile64($phoneNumber, $file, $caption = null, $type){

          $listFiles = [
            'image' => 'data:image/png;base64,',
            'pdf'   => 'data:application/pdf;base64,'
          ];

          $file         = file_get_contents($file);
          $fileToBase64 = base64_encode($file);

          $this->body = [
              'number'  => self::formatPhoneNumber($phoneNumber),
              'path'    => $listFiles[$type] . $fileToBase64,
              'caption' => $caption
          ];

          if(!$caption){
            unset($this->body['caption']);
          }

          return $this->executeCurl('https://cluster.apigratis.com/api/v1/whatsapp/sendFile64');
        }

        function getAllGroups(){
          $response = $this->executeCurl('https://cluster.apigratis.com/api/v1/whatsapp/getAllGroups');
          return $response['response']['groups'];
        }

        private function executeCurl(string $url){
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => $url,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode($this->body, true),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $this->header
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            return json_decode($response, true);
        }

        function createButton($id, $text){
            $this->buttons[] = ['id' => $id, 'text' => $text];
        }

        static function formatPhoneNumber($phoneNumber) {
            return preg_replace("/[^0-9]/", "", $phoneNumber);
        }

    }

    
