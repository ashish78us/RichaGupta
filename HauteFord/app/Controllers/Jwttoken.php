<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\Helpers\phpmailer\PHPMailer;


class Jwttoken extends Controller
{

     protected static Jwttoken $obj_Jwttoken;
     public static function getJwttoken(): Object {
         if (!isset(self::$obj_Jwttoken)) {
         self::$obj_Jwttoken = new Jwttoken();
         }
         return self::$obj_Jwttoken;
     } 

     public static function startForgotPassword(){
        $objJwttoken=self::getJwttoken();

        if (!$_POST) {
            header('HTTP/1.1 405');
        }
        if (!empty($_POST['loginid']) && !empty($_POST['emailid']) && !empty($_POST['newPassword']) && !empty($_POST['confirmNewPassword']
         ))
        {
            $loginid=$_POST['loginid'];
            $email=$_POST['emailid'];
            $newPassword=$_POST['newPassword'];
            $user = new User();
            $user=$user->getUserByUsername($loginid);
            if($user!=null){
                $storedEmail=$user->email;
                if ($email==$storedEmail){
                    //check if there is a already a token for this user
                    $jwttoken = self::getJwttoken();
                    $jwtTokenrecord=$jwttoken->model->getRecordByUsername($user->username);
                    $route = parse_url($_SERVER['REQUEST_URI']); 
                                //var_dump($route);
                                $elements = explode('/', rtrim($route['query'], '/'));
                                //var_dump($elements);
                    if($jwtTokenrecord==null || ($jwtTokenrecord->expiry<time() || $jwtTokenrecord->used!=0)){ 
                        if($jwtTokenrecord!=null) {$jwttoken->model->deleteById($jwtTokenrecord->id);}
                            //Creating JWT token
                            $key = "HauteFord123@_key";
                            $payload = [
                                'username' => $loginid,
                                'email' => $email,
                                'new_password' =>  $newPassword,
                                'timestamp' => time()                   
                            ];    
                            $jwt = JWT::encode($payload, $key, 'HS256');
                            $now = time();
                            $now=$now+3600;
                            $comment="Token created on " . date("Y-m-d h:i:sa", time()). ". Will expire in 24 hours.";
                            $jwttoken_data = [
                                'username' => $loginid,
                                'token' => $jwt,
                                'expiry' => $now,               
                                'used' => 0,
                                'comment' => $comment
                            ];
                            $jwtTokeninsert=$objJwttoken->model->insertToken($jwttoken_data); 
                            if ($jwtTokeninsert!=null) {
                                
                                
                                $passwordResetLink="http://localhost".$route['path']."?".$elements[0]."/".$elements[1]."/"."validateToken"."/".$jwt;
                            } 
                        }//Token already exist for user and not expired. 
                        else {//Previous token still valid 
                            //create existing password link and send the same again
                            $passwordResetLink="http://localhost".$route['path']."?".$elements[0]."/".$elements[1]."/"."validateToken"."/".$jwtTokenrecord->token;
                        } 
                        //Send email notification
                        $mail = new PHPMailer();
                                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                                
                                $mail->isSMTP();                                      // Set mailer to use SMTP
                                $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                $mail->Username = 'yureka20015@gmail.com';                 // SMTP username
                                $mail->Password = 'bckybdxfvrbcktnj';                           // SMTP password
                                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                                $mail->Port = 587;                                    // TCP port to connect to
                                
                                $mail->From = 'yureka20015@gmail.com';
                                $mail->FromName = 'HauteFord Application';
                                $mail->addAddress($user->email, $user->username);     // Add a recipient
                                $mail->addAddress('richa107@gmail.com');               // Name is optional
                                //$mail->addReplyTo('info@example.com', 'Information');
                                //$mail->addCC('cc@example.com');
                                //$mail->addBCC('bcc@example.com');
                                
                                $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
                                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                                $mail->isHTML(true);                                  // Set email format to HTML
                                
                                $mail->Subject = 'Password Reset Link';
                                $mail->Body    = $passwordResetLink;
                                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                
                                if(!$mail->send()) {
                                    echo 'Message could not be sent.';
                                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                                } else {
                                    echo 'Message has been sent';
                                }
                
                }
                else {Output::createAlert("Username and email don't match");}
            }
            else {
                Output::createAlert("User does not exist"); 
            }  
        }
        else {
            Output::createAlert("Something went wrong");
        }
     } 

     public static function validateToken($recJwtoken){        
        $key = "HauteFord123@_key";
        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($recJwtoken, new Key($key, 'HS256'));
        $jwttoken = self::getJwttoken();  
        $jwtTokenrecord=$jwttoken->model->getRecordByToken($decoded->username,$recJwtoken);
        if ($jwtTokenrecord!=null){
            if($jwtTokenrecord->expiry>time()){
                if($jwtTokenrecord->used!=1){
                //Output::createAlert("token is valid and expiry is=".$jwtTokenrecord->expiry);
                $user = User::getUser();
                $getUser=$user->model->getByUsernameAndEmail($decoded->username,$decoded->email);
                    if($getUser!=null){                    
                        $getUser->password = password_hash($decoded->new_password, PASSWORD_DEFAULT);
                        $user->model->update($getUser); 
                        $jwtTokenrecord->used=1;
                        $jwtTokenrecord->comment=$jwtTokenrecord->comment ." Passwords was reset successfully on ".date("Y-m-d h:i:sa", time());
                        $jwttoken->model->update($jwtTokenrecord) ;                    
                        Output::createAlert("Password updated Successfully. You can now login with new password.");
                    }
                    else {//User does not exist
                        Output::createAlert("User does not exist any more");
                    } 
                }   
                else {//$jwtTokenrecord->used!=1
                    Output::createAlert("Link has already been used");
                }    
            }
            else{Output::createAlert("Token Expired. Create new request.");}
            
        }//$jwttoken!=null
     }     
}
