<?php
namespace App\Controller;

use App\Core\Render;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Controller\Base;

class Auth
{
    public function show_login(): void
    {
        $render = new Render("login", "backoffice");
        $render->render();
    }

    public function login(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){
                if(
                    isset($_POST['username']) &&
                    !empty($_POST['email']) &&
                    !empty($_POST['pwd']) &&
                    !empty($_POST['pwdConfirm']) &&
                    count($_POST) == 5
                ){
                    $this->check_login($_POST);
                }
        }

    }

    public function check_login($data){
        $user = new User();
        $email = strtolower(trim($data['email']));

        if(strlen($_POST["pwd"]) < 8 ||
            !preg_match('/[a-z]/', $_POST["pwd"] ) ||
            !preg_match('/[A-Z]/', $_POST["pwd"]) ||
            !preg_match('/[0-9]/', $_POST["pwd"])
        ){
            $errors[]="Votre mot de passe doit faire au minimum 8 caractères avec min, maj, chiffres";
        }

        if($_POST["pwd"] != $_POST["pwdConfirm"]){
            $errors[]="Votre mot de passe de confirmation ne correspond pas";
        }


        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="Votre email n'est pas correct";
        }

        if(!empty($errors)){
            $_SESSION['errors'] = $errors;
            header('Location: /loginForm'); 
            exit();
        }else{
            $user = $user->getOneBy(["email" => $email]);
            if($user["email"] === $email){
                $_SESSION = [
                        "id" => $user["id"],
                        "username" => $user["username"],
                        "email" => $user["email"]
                    ];
                $index = new Base();
                $index->index($user);
            }
        }
    }

    public function register(): void
    {
        new Render("register", "backoffice");
    }

    public function logout(): void
    {
        $_SESSION = [];
    }

    public function forgetPassword(){
        $render = new Render("forgetPassword","frontoffice");
        $render->render();
    }

    public function resetPassword(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
                if(
                    !empty($_POST['email']) &&
                    count($_POST) == 1
                ){
                    $user = new User();
                
                    $email = $_POST['email'];
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $errors[]="Votre email n'est pas correct";
                    }else{
                        $user = $user->getOneBy(["email" => $email]);
                        if($user && $user["email"] === $email){
                            $token = $user['token'];
                            $phpmailer = new PHPMailer();
                            $activationLink = "http://localhost:8080/activation?email=".$email."&token=".$token;
                            try{
                            $phpmailer->isSMTP();
                            $phpmailer->Host = 'mailpit';
                            $phpmailer->SMTPAuth = false;
                            $phpmailer->Port = 1025;


                            $phpmailer->setFrom('no-reply@example.com', 'My Website');
                            $phpmailer->addAddress($email, 'Test User');

                            // Email content
                            $phpmailer->isHTML(true);
                            $phpmailer->Subject = 'Mailtrap test email';
                            $phpmailer->Body = '
                                <h1>Hello!</h1>
                                <p>Please use this <strong>link</strong> to reset your password.</p><br><a href='.$activationLink.'>link</a>'
                            ;
                            $phpmailer->AltBody = 'This is a plain-text alternative for non-HTML email clients.';

                            $phpmailer->send();
                            echo "Email sent successfully!";
                            }catch (Exception $e) {
                                echo "Mailer Error: " . $phpmailer->ErrorInfo;
                            }
                        }else{
                        $errors[]="Votre email n'est pas correct";
                        $_SESSION['errors'] = $errors;
                        header('Location: /forgetPassword'); 
                        exit();
                        }
                    }

                }
        }
    }


    public function linkResetPassword(){
        if(isset($_GET["email"]) && isset($_GET["token"])){
            $email = $_GET["email"];
            $token = $_GET["token"];
            $user = new User();
            $user = $user->getOneBy(["email" => $email]);
            if($user["token"] === $token){
                $render = new Render("linkResetPassword", "backoffice");
                $render->assign("email",$email);
                $render->render();
            }else{
                echo "Rien";
            }

        }

    }

    public function updatePassword(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
                if(
                    !empty($_POST['pwd']) &&
                    !empty($_POST['pwdConfirm']) &&
                    !empty($_POST['email']) &&
                    count($_POST) == 3
                ){
                   if(strlen($_POST["pwd"]) < 8 ||
                    !preg_match('/[a-z]/', $_POST["pwd"] ) ||
                    !preg_match('/[A-Z]/', $_POST["pwd"]) ||
                    !preg_match('/[0-9]/', $_POST["pwd"])
                    ){
                        $errors[]="Votre mot de passe doit faire au minimum 8 caractères avec min, maj, chiffres";
                    }

                    if($_POST["pwd"] != $_POST["pwdConfirm"]){
                        $errors[]="Votre mot de passe de confirmation ne correspond pas";
                    }
                    if(!empty($errors)){
                        $_SESSION['errors'] = $errors;
                        $location = $_SERVER['HTTP_REFERER'];
                        header('Location:' . $location); 
                        exit();
                    }
                    $user = new User();
                    $user->updatePasswordEmail($_POST["email"],$_POST["pwd"]);
                    header("Location: /loginForm");
                    exit;
                }
        }
    }
}