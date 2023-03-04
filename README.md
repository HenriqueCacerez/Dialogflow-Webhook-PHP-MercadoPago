<img width="70px" src="https://seeklogo.com/images/D/dialogflow-logo-534FF34238-seeklogo.com.png" align="right" />
<img width="80px" src="https://logospng.org/download/mercado-pago/logo-mercado-pago-icone-1024.png" align="right" />

# Dialogflow Webhook PHP - Mercado Pago
> Este é um código em PHP para estabelecer um webhook em seu agente do Dialogflow. Combinado à API do Mercado Pago e API Brasil, ele possibilita a criação de links de pagamento e a emissão de notificações via WhatsApp.

***
<!-- CONFIGURAÇÃO - DIALOGFLOW ES -->
<details>
  <summary>Configuração - Dialogflow ES</summary>
  <ol><br>
    <li>
      <b>Criando uma Intent de Teste.</b>
      <p>Crie uma intent chamada <b>pagamento</b> <img width="150px" src="https://i.ibb.co/0sKdzQp/Screenshot-2023-03-03-at-17-48-37-Dialogflow.png"> Ative nesta intent em "Fulfillment" a opção <b>"Enable webhook call of this intent"</b>.</p>
      <ul>
        <p align="center">
          <img src="https://i.ibb.co/nbqD0Qq/Screenshot-2023-03-03-at-17-48-09-Dialogflow.png">
        </p>
      </ul>
    </li>
    <li>
      <b>Parâmetros</b>
      <p>Crie dois parâmetros. <b>whatsapp</b> e <b>name</b>.</p>
      <ul>
        <p align="center">
          <img src="https://i.ibb.co/chmS5Jz/Screenshot-2023-03-04-at-17-22-56-Dialogflow.png">
        </p>
      </ul>
    </li>
  </ol>
</details>


<!-- CONFIGURAÇÃO - MERCADO PAGO -->
<details>
  <summary>Configuração - Mercado Pago</summary>
  <ol><br>
    <li>
      <b>Criando uma nova aplicação.</b>
      <p>Acesse <a href="https://www.mercadopago.com.br/developers/panel/">https://www.mercadopago.com.br/developers/panel/</a>, faça o login em sua conta do Mercado Pago e crie uma nova aplicação.</p>
      <ul>
        <p align="center">
          <img src="https://i.ibb.co/5Lym47z/Captura-da-Web-3-3-2023-165227-www-mercadopago-com-br.png" alt="Size Limit CLI" width="738">
        </p>
      </ul>
    </li>
    <li>
      <b>Pegando as Credenciais</b>
      <ul>
        <p>Após criar a sua aplicação, anote as suas credenciais. Neste exemplo, iremos utilizar apenas o <b>Access Token</b>.</p>
        <p align="center">
          <img src="https://i.ibb.co/ydjnPnF/Screenshot-2023-03-03-at-17-01-33-Credenciais-1.png" alt="Size Limit CLI" width="738">
        </p>
      </ul>
    </li>
    <li>
      <b>Notificações Webhooks</b>
      <ul>
        <p>Informe a URL na qual o Mercado Pago enviará as notificações POST HTTP, sempre que um evento de pagamento for realizado. Neste exemplo, as notificações serão enviadas para <b>"mp/notification.php"</b>.</p>
        <p align="center">
          <img src="https://i.ibb.co/5YQPCNc/Screenshot-2023-03-03-at-17-09-26-Notifica-es-webhooks.png" alt="Size Limit CLI" width="500"><br><br>
          <img src="https://i.ibb.co/SVTFN7m/Screenshot-2023-03-03-at-17-14-39-Notifica-es-webhooks.png" alt="Size Limit CLI" width="738">
        </p>
      </ul>
    </li>
    <li>
      <b>Atualizando o <b>Access Token</b> em <b>"Payment.php"</b></b>
      <ul>
        <p>Por fim, atualize a const <b>ACCESS_TOKEN</b> em <b>"App/Controller/Payment.php"</b></p>
      </ul>
    </li>
  </ol>
</details>

<!-- CONFIGURAÇÃO - API BRASIL -->
<details>
  <summary>Configuração - API Brasil (WhatsApp)</summary><br>
  <p>Para enviar notificações sobre o status de pagamento aos seus clientes através do WhatsApp, utilizaremos a plataforma da <a href="https://apibrasil.com.br">API Brasil</a>. Através dela, é possível ter acesso aos recursos necessários de forma gratuita. Acesse <a href="https://plataforma.apibrasil.com.br/auth/register">https://plataforma.apibrasil.com.br/auth/register</a> para realizar o cadastro e vincular o seu número de WhatsApp.</p>
</details>

***
## Instalação
Instale todas as dependências necessárias via composer: `composer install`.

---

## 📚 Dependências

- [eristemena/dialogflow-fulfillment-webhook-php](https://github.com/eristemena/dialogflow-fulfillment-webhook-php)
- [mercadopago/dx-php](https://github.com/mercadopago/sdk-php)

---

## Funcionalidades

- [x] Cria o link de pagamento.
- [x] Registra o usuário no banco de dados.
- [x] Armazena as informações do pagamento no banco de dados.
- [x] Atualiza o status do pagamento automaticamente após a aprovação.
- [x] Envia uma mensagem via WhatsApp ao usuário notificando-o sobre a aprovação do pagamento.