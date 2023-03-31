<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/private/config.php';

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;

function sendEmail($etiqueta, $sender_email){
    $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', BLUE_KEY);

    $apiInstance = new TransactionalEmailsApi(new \GuzzleHttp\Client(), $config);

    $email = new SendSmtpEmail();
    $email['subject'] = 'Etiqueta Mondial Relay Pedido';
    $email['htmlContent'] = '<html><body><h1>Esto es una prueba</h1></body>
                <p>Tu etiqueta es: </p>'.$etiqueta.'</html>';
    $email['sender'] = array('name' => 'Yoreleo', 'email' => 'info@yoreleo.es');
    $email['to'] = array(array('email' => $sender_email));

    $result = $apiInstance->sendTransacEmail($email);

    /*$email = new SendSmtpEmail();
    $email->setSubject('Asunto del correo electrónico');
    $email->setHtmlContent('<p>Contenido del correo electrónico</p>');
    $email->setSender(array('name' => 'Nombre del remitente', 'email' => 'remitente@tudominio.com'));
    $email->setTo(array(array('email' => 'destinatario@tudominio.com')));

    // Enviar el correo electrónico usando la función wp_mail
    wp_mail('', '', '', array(
    'sendinblue_template_id' => '',
    'sendinblue_template_data' => array(
        'email' => $email
    )
    ));*/
}

