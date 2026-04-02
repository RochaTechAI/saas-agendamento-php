<?php

namespace Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mail
{
    public static function enviar(string $paraEmail, string $paraNome, string $assunto, string $corpoHTML): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host     = getenv('MAIL_HOST') ?: 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port     = (int) (getenv('MAIL_PORT') ?: 2525);
            $mail->Username = getenv('MAIL_USER') ?: '';
            $mail->Password = getenv('MAIL_PASS') ?: '';

            $mail->setFrom(
                getenv('MAIL_FROM') ?: 'nao-responda@medsaas.com',
                getenv('MAIL_FROM_NAME') ?: 'MedSaaS'
            );
            $mail->addAddress($paraEmail, $paraNome);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $assunto;
            $mail->Body    = $corpoHTML;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Falha ao enviar e-mail para {$paraEmail}: {$mail->ErrorInfo}");
            return false;
        }
    }
}
