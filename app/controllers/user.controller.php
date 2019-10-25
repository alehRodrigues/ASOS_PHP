<?php
header('Content-Type: application/json; charset=utf-8');

require '../models/User.class.php';
require '../helpers/token/Token.class.php';

$U = new UserControl();
$U->Control();

final class UserControl
{

    private $Request64;
    private $Request64Split;

    private $request;
    private $userClient;
    private $token;
    private $permiss;
    private $value;

    private $response;
    private $responseAccess = 'AcessoNegado';
    private $responseUser = array(

        'user' => "",
        'email' => "",
        'log' => "",
    );
    private $responseToken = null;
    private $responsePermiss = null;
    private $responseError = null;

    public function Control()
    {
        $this->Request64 = file_get_contents('php://input');
        $this->Request64Split = $this->Request_Trim($this->Request64);

        $this->request = base64_decode($this->Request64Split[0]);
        $this->userClient = json_decode(base64_decode($this->Request64Split[1]));
        $this->token = $this->Request64Split[2];
        $this->permiss = $this->Request64Split[3];
        $this->value = base64_decode($this->Request64Split[4]);
        
        $this->SeletorLogin();
        
    }

    private function Request_Trim($Data)
    {
        return $this->request = explode('.',$Data);
    }

    private function SeletorLogin()
    {
        switch ($this->request) {
            case 'PrimeiroAcesso':
                $this->PrimeiroAcesso();
                break;

            case 'Login':
                $this->Login();
                break;

            case 'UpdatePassword':
                $this->UpdatePassword(false);
                break;

            case 'user':
                $this->Update('user');
                break;

            case 'password':
                $this->UpdatePassword($this->value);
                break;

            case 'Delete':
                $this->Deletar();
                break;
        }
    }

    private function PrimeiroAcesso()
    {
        
        $USER = new User();
        $USER->setUser(strtoupper($this->userClient->user));
        $USER->setEmail($this->userClient->email);
        require '../includes/GeraSenha.php';
        require '../includes/mail/send_mail.class.php';
        
        $PASSWORD = GeraSenha();
        $USER->setPassword($PASSWORD);
        $ADD = $USER->Add($USER->ParseUser());
        
        if($ADD == 1)
        {
            $MAIL = SendMail::Enviar($USER->getUser(), $USER->getEmail(), $PASSWORD,"primeiro_acesso.mail.html", "NOVO ACESSO");

            if($MAIL)
            {
                $this->responseAccess = 'AcessoAprovado';
                echo $this->ResponseMount();
                die();
            }else
                {
                    $this->responseError = $MAIl;
                    echo $this->ResponseMount();
                    die();
                }
        }else
            {
                $this->responseError = $ADD;
                echo $this->ResponseMount();
                die();
            }
    }

    private function Login()
    {
        $DataUser = new User();
        $DataUser->setEmail($this->userClient->email);
        $DataUserObj = $DataUser->Read('email',$this->userClient->email);

        if(is_object($DataUserObj))
        {
            if(password_verify($this->userClient->password,$DataUserObj->password))
            {
                $Update = $DataUser->UpdateDataLogin();

                if($Update == true)
                {
                    $this->responseAccess = $DataUserObj->log;
                    $this->responseUser['user'] = $DataUserObj->user;
                    $this->responseUser['email'] = $DataUserObj->email;
                    $this->responseUser['log'] = $DataUserObj->log;
                    $this->responsePermiss = $DataUserObj->permissoes;

                    $tk = new Token($DataUserObj);
                    $this->responseToken = $tk->GetToken();
                    
                    echo $this->ResponseMount();
                    die();
                }else
                    {
                        $this->responseError = $Update;
                        
                        echo $this->ResponseMount();
                        die();
                    }
            }else
                {
                    $this->responseError = 'Senha ou usuário inválido.';
                    echo $this->ResponseMount();
                    die();
                }
        }else
            {
                $this->responseError = 'Senha ou usuário inválido.';
                echo $this->ResponseMount();
                die();
            }
    }

    private function ResponseMount()
    {
        $this->responseAccess = $this->responseAccess;
        $this->responseUser = base64_encode(json_encode($this->responseUser));
        $this->responseToken = $this->responseToken;
        $this->responsePermiss = base64_encode($this->responsePermiss);
        $this->responseError = $this->responseError;

        return $this->responseAccess . '.' .$this->responseUser. '.' .$this->responseToken. '.' .$this->responsePermiss. '.' .$this->responseError;
    }

    private function UpdatePassword($NewPassword) 
    {
        $UserUpdate = new User();
        $UserUpdate->setEmail($this->userClient->email);
        require '../includes/GeraSenha.php';
        require '../includes/mail/send_mail.class.php';
        
        if (!$NewPassword)
        {
            if(Token::ValidateToken($this->token))
            {
                $PASSWORD = $NewPassword;
            }
            else
            {
                echo Token::ValidateToken($this->token);
                die();
            }
        }
        else
        {
            $PASSWORD = GeraSenha();
        }
        $UserUpdate->setPassword($PASSWORD);
        $UPDATEPASSWORD = $UserUpdate->UpdateSimple("password", $UserUpdate->getPassword(), $UserUpdate->getEmail());
        
        if($UPDATEPASSWORD)
        {
            $MAIL = SendMail::Enviar("", $UserUpdate->getEmail(), $PASSWORD,"recupera_senha.mail.html", "RECUPERAÇÃO DE SENHA");

            if($MAIL)
            {
                $this->responseAccess = 'PasswordRecuperado';
                echo $this->ResponseMount();
                die();
            }else
                {
                    $this->responseError = $MAIl;
                    echo $this->ResponseMount();
                    die();
                }
        }else
            {
                $this->responseError = $UPDATEPASSWORD;
                echo $this->ResponseMount();
                die();
            }
    }

    private function Update($Field)
    {
        if(Token::ValidateToken($this->token))
        {

            $this->responseToken = $this->token;
            
            $UserUpdate = new User();
            $UserUpdate->setEmail($this->userClient->email);
            
            switch ($Field) {
                case 'user':
                $UserUpdate->setUser(strtoupper($this->value));
                $UPDATE = $UserUpdate->UpdateSimple($Field,$UserUpdate->getUser(),$UserUpdate->getEmail());
                
                if($UPDATE)
                {
                    $this->responseAccess = 'UserAtualizado';
                    $this->responseUser['user'] = $UserUpdate->getUser();
                    $this->responseUser['email'] = $this->userClient->email;
                    $this->responseUser['log'] = $this->userClient->log;
                    $this->responsePermiss = base64_decode($this->permiss);
                    echo $this->ResponseMount();
                    die();
                    }
                    else
                    {
                        $this->responseError = $UPDATE;
                        echo $this->ResponseMount();
                        die();
                    }

                break;

                case 'password':
                $UPDATE = $UserUpdate->UpdatePassword();
                break;
            }
        }
        else
        {
            echo Token::ValidateToken($this->token);
        }
    }

    private function Deletar()
    {

    }

    
        /**
         * String de Acesso
         * Informações de nome e email + Permissões ou falso
         * Token ou falso
         * Mensagem de erro ou falso
         */
    

}

/**
 * Funções do controller
 * Define o tipo de request ("Primeiro Acesso", "Login", "Update", "Delete")
 * Para primeiro Acesso: 
 *  Cria Usuario
 *  Define senha aleatória
 *  Envia email de Acesso
 *  Salva Usuario no banco de dados
 *  Devolve a condição de acesso
 * 
 * Para Login
 *  Lê usuario do banco de dados
 *  Valida senha
 *  Cria token de Validação
 *  Monta token de informação
 *  Responde com o token de informação
 * 
 * Para Update:
 *  Separa o token de informação em token de Validação
 *  Valida o token de Validação
 *  Atualiza o token de Validação
 *  Update do banco de dados 
 *  Atualiza token de informação 
 *  Responde com o token de informação atualizado 
 * 
 * Para Deletar:
 *  Separa o token de informação e Validação
 *  Valida o token de Validação
 *  Devolve Logout
 * 
 */





