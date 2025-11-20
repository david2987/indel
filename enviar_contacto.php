<?php
require_once 'plugin/PHPMailer/src/Exception.php';
require_once 'plugin/PHPMailer/src/PHPMailer.php';
require_once 'plugin/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuración de reCAPTCHA
// NOTA: Reemplazar con tu clave secreta de Google reCAPTCHA v2
// Obtén tus claves en: https://www.google.com/recaptcha/admin
$recaptcha_secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Clave secreta de prueba - reemplazar con la real
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

// Verificar reCAPTCHA
// if (empty($recaptcha_response)) {
//     echo json_encode(['success' => false, 'message' => 'Por favor, complete el reCAPTCHA.']);
//     exit;
// }

$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_data = [
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$recaptcha_options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($recaptcha_data)
    ]
];

$recaptcha_context = stream_context_create($recaptcha_options);
$recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
$recaptcha_json = json_decode($recaptcha_result, true);

if (!$recaptcha_json['success']) {
    echo json_encode(['success' => false, 'message' => 'Error en la verificación de reCAPTCHA. Por favor, intente nuevamente.']);
    exit;
}

// Validar y sanitizar datos del formulario
$nombreCliente = isset($_POST['nombreCliente']) ? trim(htmlspecialchars($_POST['nombreCliente'])) : '';
$nombreEmpresa = isset($_POST['nombreEmpresa']) ? trim(htmlspecialchars($_POST['nombreEmpresa'])) : '';
$celular = isset($_POST['celular']) ? trim(htmlspecialchars($_POST['celular'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$mensaje = isset($_POST['mensaje']) ? trim(htmlspecialchars($_POST['mensaje'])) : '';

// Validaciones
$errors = [];

if (empty($nombreCliente)) {
    $errors[] = 'El nombre del cliente es requerido.';
}

if (empty($nombreEmpresa)) {
    $errors[] = 'El nombre de la empresa es requerido.';
}

if (empty($celular)) {
    $errors[] = 'El celular es requerido.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El email es requerido y debe ser válido.';
}

if (empty($mensaje)) {
    $errors[] = 'El mensaje es requerido.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Configurar PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    // NOTA: Ajustar según la configuración de tu servidor de correo
    $mail->isSMTP();
    $mail->Host = 'indelsrl.com.ar'; // Cambiar por tu servidor SMTP (ej: smtp.gmail.com, smtp.office365.com)   
    $mail->Port = 465; // Puerto SMTP (587 para TLS, 465 para SSL)
    
    // Si requiere autenticación SMTP, descomentar y configurar:
    $mail->SMTPAuth = true;
    $mail->Username = 'info@indelsrl.com.ar';
    $mail->Password = '21xskpO$1v95';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // o ENCRYPTION_SMTPS para SSL
    
    // Remitente
    $mail->setFrom($email, 'Información Comercial INDEL'.$nombreCliente);
    //  $mail->addReplyTo('info@indelsrl.com.ar', 'Información Comercial INDEL'.$nombreCliente);
    //  $mail->addReplyTo('comercial@indelsrl.com.ar', $nombreCliente);
     $mail->addCC('comercial@indelsrl.com.ar','Información Comercial INDEL'.$nombreCliente);
     $mail->addCC($email,'Información Comercial INDEL'.$nombreCliente);
    
    // Destinatario
    $mail->addAddress('info@indelsrl.com.ar', 'Información Comercial INDEL');
    
    // Contenido del email
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Nueva Consulta desde el Sitio Web - ' . $nombreEmpresa;
    
    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: 'Montserrat', Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #FE1E1E; color: #FFFFFF; padding: 20px; text-align: center; }
            .content { background-color: #F3F0F0; padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: 700; color: #6C6A6A; }
            .value { color: #000000; margin-top: 5px; }
            .message-box { background-color: #FFFFFF; padding: 15px; border-left: 4px solid #FE1E1E; margin-top: 15px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2 style='margin: 0;'>Nueva Consulta desde el Sitio Web</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Nombre del Cliente:</div>
                    <div class='value'>{$nombreCliente}</div>
                </div>
                <div class='field'>
                    <div class='label'>Nombre de la Empresa:</div>
                    <div class='value'>{$nombreEmpresa}</div>
                </div>
                <div class='field'>
                    <div class='label'>Celular:</div>
                    <div class='value'>{$celular}</div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>{$email}</div>
                </div>
                <div class='message-box'>
                    <div class='label'>Mensaje:</div>
                    <div class='value'>" . nl2br($mensaje) . "</div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "Nueva Consulta desde el Sitio Web\n\n" .
                     "Nombre del Cliente: {$nombreCliente}\n" .
                     "Nombre de la Empresa: {$nombreEmpresa}\n" .
                     "Celular: {$celular}\n" .
                     "Email: {$email}\n\n" .
                     "Mensaje:\n{$mensaje}";
    
    $mail->send();
    echo json_encode(['success' => true, 'message' => '¡Gracias por su consulta! Nos pondremos en contacto con usted a la brevedad.']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje. Por favor, intente nuevamente más tarde.'.$e]);
    // Log del error (opcional, para debugging)
    error_log("Error PHPMailer: " . $mail->ErrorInfo);
}

