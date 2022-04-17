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
            $mail->Password   = smtpPassword;               //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = smtpPort;                         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom(smtpSender, 'GCB-thetaPay');
            $mail->addAddress($email); 

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'ThetaPay Account verification';
            $mail->Body  = "<h1>Hello!</h1>
                            <p>Please click the link below to verify your email address.</p>
                            <br/>
                            <a href=$link>Click here please</a>
                            <br/><br/>
                            <p>If you did not request for this then, no further action is required.<p>
                            <br/>
                            <p>Regards, <br/> Thetapay </p>";

            
            if(!$mail->send()){
                return false;
            }else{
                return true;
            }
        }

        // reset password mail
        public function sendResetPasswordMail($email, $link){
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            //Server settings   
            // $mail->SMTPDebug = 1;               
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = smtpHost;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = smtpUsername;                     //SMTP username
            $mail->Password   = smtpPassword;             //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = smtpPort;                         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom(smtpSender, 'GCB-thetaPay');
            $mail->addAddress($email); 

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Reset Password - ThetaPay';
            $mail->Body  = "<h1>Hello!</h1>
                            <p>Please click the link below to reset your password.</p>
                            <br/>
                            <a href=$link>Click here please</a>
                            <br/><br/>
                            <p>If you did not request for this then, no further action is required.<p>
                            <br/>
                            <p>Regards, <br/> Thetapay </p>";

            if(!$mail->send()){
                return false;
            }else{
                return true;
            }
        }

        // transaction notification
        public function transactionNotify($user, $txn, $type, $amount){
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            //Server settings   
            // $mail->SMTPDebug = 1;               
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = smtpHost;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = smtpUsername;                     //SMTP username
            $mail->Password   = smtpPassword;             //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = smtpPort;                         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom(smtpSender, 'GCB-thetaPay');
            $mail->addAddress($user['email']); 

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Transaction Notification on A\c No '.$user['accountNo'].' - ThetaPay';
            $mail->Body  = '<div id=":nx" class="a3s aiL">
                        <table width="800" border="0">
                            <tbody>
                                <tr>
                                    <td width="800" height="100">
                                        <div style="display: flex; height: 100px; background-color: blue; color: white; align-items: center; padding: 2% 5%; justify-content: space-between;">
                                            <p style="font-size: 25px; font-weight: bold; font-family:"Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;">Transaction Notification</p>
                                            <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 30px; font-weight: bolder; font-style: italic;">thetaPay</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table width="800">
                            <tbody>
                                <tr>
                                    <td width="800" height="70" align="left" valign="center" colspan="7">
                                        <p align="justify">
                                            <font color="00597c" size="2"><i>
                                                    <font face="Verdana, Arial, Helvetica, sans-serif">Dear <b>'.$user['firstname'].' '.$user['lastname'].'</b>, this alert
                                                        was generated to notify you of recent activity on your account. </font>
                                                </i></font>
                                        </p>
                                        <p align="justify">
                                            <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><i>Detailed
                                                    information on this transaction is shown below:</i></font>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table width="800" border="0">
                            <tbody>
                                <tr bgcolor="B0E2FF">
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Account Nº:</b></font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$user['accountNo'].'&nbsp;&nbsp;
                                        </font>
                                    </td>
                                    <td width="80">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Transaction Type:</b>
                                        </font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                    
                                            '.$type.' &nbsp;&nbsp;
                    
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Description:</b>
                                        </font>
                                    </td>
                                    <td width="320" align="right" valign="center"><span style="font-size:11px;color:000000">
                                            <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$txn['description'].'&nbsp;&nbsp;</font>
                                        </span></td>
                                    <td width="80">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Amount:</b></font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"> GHS '.$amount.'&nbsp;&nbsp;
                                        </font>
                                    </td>
                                </tr>
                                <tr bgcolor="B0E2FF">
                                    <td width="80" height="42"><strong>
                                            <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">Transaction Date:
                                            </font>
                                        </strong></td>
                                    <td width="320" align="right" valign="center">
                                        <font face="Verdana, Arial, Helvetica, sans-serif"><span style="font-size:10px;color:000000">
                                                <font color="00597c" size="2">
                                                    <font color="00597c" size="2">'.$txn['createdAt'].'&nbsp;&nbsp;</font>
                                                </font>
                                            </span></font>
                                    </td>
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Reference:</b></font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                            '.$txn['description'].'&nbsp;&nbsp;</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Value Date:</b></font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$txn['createdAt'].'&nbsp;&nbsp;
                                        </font>
                                    </td>
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Ledger Balance:</b>
                                        </font>
                                    </td>
                                    <td width="320" height="42" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">GHS '.$user['balance'].'&nbsp;&nbsp;
                                        </font>
                                    </td>
                                </tr>
                                <tr bgcolor="B0E2FF">
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Transaction
                                                Location:</b></font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                    
                                            -&nbsp;&nbsp;
                    
                                        </font>
                                    </td>
                                    <td width="80" height="42">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Available Balance:</b>
                                        </font>
                                    </td>
                                    <td width="320" align="right" valign="center">
                                        <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif">GHS '.$user['balance'].'&nbsp;&nbsp;
                                        </font>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table width="800">
                            <tbody>
                                <tr>
                                    <td height="12">
                                        <p><span style="font-size:11px;color:00597c;padding-bottom:3px">
                                                <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><i>If you require
                                                        any clarification on this transaction or any of our services, please do not hesitate
                                                        to reach out to us via the contacts below or visit any of our branches for
                                                        assistance.</i></font>
                                            </span><span style="padding-bottom:3px">
                                                <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><i></i></font>
                                            </span>
                    
                                        </p>
                                        <p>
                                            <span style="font-size:11px;color:00597c;padding-bottom:3px">
                                                <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><i>Thank you for
                                                        choosing thetaPay, we occupy all angles of payment.</i></font>
                                            </span><span style="padding-bottom:3px">
                                                <font color="00597c" size="2" face="Verdana, Arial, Helvetica, sans-serif"><i></i></font>
                                            </span>
                    
                                        </p>
                                        <p><img style="display:block"
                                                src="https://mail.google.com/mail/u/0?ui=2&amp;ik=04efb4c87a&amp;attid=0.0.2&amp;permmsgid=msg-f:1724481379097535137&amp;th=17ee96ad76e262a1&amp;view=fimg&amp;fur=ip&amp;sz=s0-l75-ft&amp;attbid=ANGjdJ_6BmeKu9vJAY7nXujy9gD-Hy28bn-Hxkt4xQBkbGTgQ2njHRj3khhEWzWkVm3qICAgnNCkyIXfuPJUQeWy9zkp-FRhW7bxTjy_3fEfYrGA-S-IbgRDPB4LZKU&amp;disp=emb"
                                                alt="Ecobank logo" width="800" height="2" data-image-whitelisted="" class="CToWUd"></p>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>';

            if(!$mail->send()){
                return false;
            }else{
                return true;
            }
        }

    }
?>