<?php

namespace Config;

// Importa as classes do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carrega o carteiro (A pasta vendor que você acabou de baixar)
require_once __DIR__ . '/../vendor/autoload.php';

class Mail {
    
    /**
     * Função universal para disparar e-mails no sistema
     */
    public static function enviar($paraEmail, $paraNome, $assunto, $corpoHTML) {
        $mail = new PHPMailer(true);

        try {
            // CONFIGURAÇÕES DO SERVIDOR (Usando o Mailtrap para testes)
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->SMTPAuth   = true;
            $mail->Port       = 2525;
            
            // ⚠️ ATENÇÃO: Nós vamos preencher isso no Passo 5!
            $mail->Username   = '33cb8149aa2814'; 
            $mail->Password   = 'e93202d9533c8d';

            // REMETENTE E DESTINATÁRIO
            $mail->setFrom('nao-responda@medsaas.com', 'Clinica MedSaaS');
            $mail->addAddress($paraEmail, $paraNome);

            // CONTEÚDO DO E-MAIL
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $assunto;
            $mail->Body    = $corpoHTML;

            // Envia o e-mail
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            // Mandamos ele gritar o erro na tela para a gente ver!
            echo "🚨 ERRO DO PHPMAILER: " . $mail->ErrorInfo . "\n";
            error_log("Erro ao enviar e-mail para {$paraEmail}: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>