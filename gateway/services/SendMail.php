<?php


    require './services/phpmailer/src/PHPMailer.php';
    require './services/phpmailer/src/SMTP.php';
    require './services/phpmailer/src/Exception.php';

    // mailing variables

    class MailingService{
        public function sendMail($email, $link){
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            //Server settings   
            // $mail->SMTPDebug = 1;               
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = smtpHost;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = smtpUsername;                     //SMTP username
            $mail->Password   = smtpPassword;                         //SMTP password
            $mail->SMTPSecure = 'ssl';                      //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('therence@saasfluxgh.com', 'GCB-thetaPay');
            $mail->addAddress($email); 

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'ThetaPay Account verification';
            $mail->Body  = "<h1>Hello!</h1>
                            <p>Please click the link below to verify your email address.</p>
                            <br/>
                            <a href=$link>Click here please</a>
                            <br/><br/>
                            <p>If you did not create an account, no further action is required.<p>
                            <br/>
                            <p>Regards, <br/> Thetapay </p>";

            if(!$mail->send()){
                return false;
            }else{
                return true;
            }
        }

        
    }
?>