<?php

include '../includes/mail/mailer/PHPMailer.php';
include '../includes/mail/mailer/SMTP.php';
include '../includes/mail/mailer/Exception.php';

final class SendMail
{
    public static function Enviar($user,$email,$senha, $template, $Assunto)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->CharSet = 'UTF-8';
        date_default_timezone_set("America/Sao_Paulo");

        require 'PHPMailerAutoload.php';

        //Create a new PHPMailer instance
        //$mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        //$mail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
               'verify_peer_name' => false,
                'allow_self_signed' => true
           )
        );

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "btt.ale@gmail.com";

        //Password to use for SMTP authentication
        $mail->Password = "Casatremere01";

        //Set who the message is to be sent from
        $mail->setFrom('btt.ale@gmail.com', 'Alexandre Rodrigues');

        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');

        //Set who the message is to be sent to
        $mail->addAddress($email,$user);

        //Set the subject line
        $mail->Subject = $Assunto.' - SISMAT';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(str_replace(array("#USUARIO#","#SENHA#", "#EMAIL#", "#DATA#"),array($user,$senha,$email, date("d-m-Y H:i:s")),self::my_file_get_contents($_SERVER['HTTP_HOST'].'/sismat.v3/app/includes/mail/templates/' .$template)));

        $mail->isHTML(true);

        //$mail->Body = "Este é um teste";

        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return true;
            //echo "Message sent!";
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }

        //Section 2: IMAP
        //IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
        //Function to call which uses the PHP imap_*() functions to save messages:      https://php.net/manual/en/book.imap.php
        //You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels,         this can
        //be useful if you are trying to get this working on a non-Gmail IMAP server.
        function save_mail($mail) {
            //You can change 'Sent Mail' to any other folder or tag
            $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
        
            //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
            $imapStream = imap_open($path, $mail->Username, $mail->Password);
        
            $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
            imap_close($imapStream);
        
            return $result;
        }
    }

    private static function my_file_get_contents( $site_url ){
        $ch = curl_init();
        $timeout = 5; // set to zero for no timeout
        curl_setopt ($ch, CURLOPT_URL, $site_url);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        ob_start();
        curl_exec($ch);
        curl_close($ch);
        $file_contents = ob_get_contents();
        ob_end_clean();
        return $file_contents;
        }
}

