/**
 * Author: Alexandre Rodrigues <btt.ale@gmail.com>
 * Description: Gerencia ações de usuário como primeiro acesso, login, update e delete
 */

function MainLogin() {
    document.getElementById("novoAcesso").onsubmit = function(){return UserRequest('PrimeiroAcesso');};
    document.getElementById("loginAcesso").onsubmit = function(){return UserRequest('Login');};
    document.getElementById("form-config-user").onsubmit = function(){return UserRequest('UpdateUser');};
    document.getElementById("form-config-senha").onsubmit = function(){return UserRequest('UpdatePassword');};
}

function UserRequest(RequestType){
    
    switch (RequestType) {
        case 'PrimeiroAcesso':
            document.getElementById('progress-primeiro_acesso').style = 'display:inline-block';
            document.getElementById('btn-primeiro_acesso').classList.add('disabled');
            PrimeiroAcesso();
            break;

        case 'Login':
            document.getElementById('progress-login').style = 'display:inline-block';
            document.getElementById('btn-login').classList.add('disabled');
            Login();
            break;
            
            case 'UpdateUser':
            document.getElementById('progress-user-config').style = 'display:inline-block';
            document.getElementById('btn-user-config').classList.add('disabled');
            Update('user',document.getElementById('user-config').value);
            break;

            case 'UpdatePassword':
            document.getElementById('progress-user-password').style = 'display:inline-block';
            document.getElementById('btn-user-password').classList.add('disabled');
            Update('password',document.getElementById('passNew2-config').value);
            break;
            
            case 'Delete':
            
            break;
        }
        
        return false;
    }
    
    function PrimeiroAcesso(){
        
        var request = 'PrimeiroAcesso';
        
        //Elementos do form
        var user = document.getElementById("primeiro-acesso-user");
        var email = document.getElementById("primeiro-acesso-email");
        var userData = {};
        userData['user'] = user.value;
        userData['email'] = email.value; 
        
        var token = "";
        var permiss = "";
        
        var data = btoa(request) + '.' + btoa(JSON.stringify(userData)) + '.' + token + '.' + btoa(permiss) + '.' + btoa("");
        
        SendRequest(data);
    }
    
    function Login(){
        
        if(document.getElementById('esqueci-senha').checked)
        {
            var request = 'UpdatePassword';
            
            //Elementos do form
            var email = document.getElementById("login-email");
            var userData = {};
            userData['email'] = email.value; 
            
            var token = "";
            var permiss = "";
        }
        else
        {
            var request = 'Login';
            
            //Elementos do form
            var password = document.getElementById("login-pass");
            var email = document.getElementById("login-email");
            var userData = {};
            userData['password'] = password.value;
        userData['email'] = email.value; 

        var token = "";
        var permiss = "";
    }
    

    var data = btoa(request) + '.' + btoa(JSON.stringify(userData)) + '.' + token + '.' + btoa(permiss) + '.' + btoa("");

    SendRequest(data);

}

function Update(Field, Value)
{
    var request = Field;

    var userData = {};

    userData['user'] = sessionStorage.getItem('user');
    userData['email'] = sessionStorage.getItem('email');
    userData['log'] = sessionStorage.getItem('log');

    var token = sessionStorage.getItem('token');
    var permiss = sessionStorage.getItem('permiss');

    var data = btoa(request) + '.' + btoa(JSON.stringify(userData)) + '.' + token + '.' + btoa(permiss) + '.' + btoa(Value);

    SendRequest(data);

}

function SendRequest(data){

    var http = new XMLHttpRequest();
    http.onreadystatechange = function(){
        
        if (this.readyState === 4 && this.status === 200) {
            
            var Response = http.responseText.split(".");
            var Access = Response[0];
            var MessageError = Response[4];
            switch (Access) {
                
                case "AcessoAprovado":
                document.getElementById("novoAcesso").reset();
                modal_login.close();
                document.getElementById('progress-primeiro_acesso').style = 'display:none';
                document.getElementById('btn-primeiro_acesso').classList.remove('disabled');
                EmailEnviado();
                break;
                
                case "PasswordRecuperado":
                document.getElementById("loginAcesso").reset();
                document.getElementById("login-pass").removeAttribute("disabled", "false");
                document.getElementById("label-login-pass").innerHTML = "Informe sua senha";
                document.getElementById("btn-login").innerHTML = 'Entrar<i id="icon-btn-login" class="material-icons right">send</i>';
                modal_login.close();
                document.getElementById('progress-login').style = 'display:none';
                document.getElementById('btn-login').classList.remove('disabled');
                PasswordRecuperado();
                break;
                
                case "AcessoNegado":
                document.getElementById("novoAcesso").reset();
                document.getElementById("loginAcesso").reset();
                modal_login.close();
                document.getElementById('progress-primeiro_acesso').style = 'display:none';
                document.getElementById('btn-primeiro_acesso').classList.remove('disabled');
                document.getElementById('progress-login').style = 'display:none';
                document.getElementById('btn-login').classList.remove('disabled');
                AcessoError(MessageError);
                break;
                
                case "UserAtualizado":
                document.getElementById("form-config-user").reset();
                var User = JSON.parse(atob(Response[1]));
                var Token = Response[2];
                var Permiss = atob(Response[3]);
                sessionStorage.setItem('user', User['user']);
                sessionStorage.setItem('email', User['email']);
                sessionStorage.setItem('log', User['log']);
                sessionStorage.setItem('token', Token);
                sessionStorage.setItem('permiss', Permiss);
                document.getElementById('progress-user-config').style = 'display:none';
                document.getElementById('btn-user-config').classList.remove('disabled');
                document.getElementById('nomeUsuario-config').innerHTML = User['user'];
                document.getElementById("sidenav-user").innerHTML = "<span class=\"white-text\">" + GetStore('user') + "</span>"
                Atualizado("O seu nome de usuário foi alterado.");
                break;
                
                case "USUARIO":
                document.getElementById("loginAcesso").reset();
                modal_login.close();
                var User = JSON.parse(atob(Response[1]));
                var Token = Response[2];
                var Permiss = atob(Response[3]);
                sessionStorage.setItem('user', User['user']);
                sessionStorage.setItem('email', User['email']);
                sessionStorage.setItem('log', User['log']);
                sessionStorage.setItem('token', Token);
                sessionStorage.setItem('permiss', Permiss);
                document.getElementById('progress-login').style = 'display:none';
                document.getElementById('btn-login').classList.remove('disabled');
                AcessoMessage();
                break;
                
                case "ADMINISTRATIVO":
                
                break;
                
                default:
                document.getElementById("novoAcesso").reset();
                document.getElementById("loginAcesso").reset();
                document.getElementById("form-config-user").reset();
                modal_login.close();
                document.getElementById('progress-primeiro_acesso').style = 'display:none';
                document.getElementById('btn-primeiro_acesso').classList.remove('disabled');
                
                document.getElementById('progress-login').style = 'display:none';
                document.getElementById('btn-login').classList.remove('disabled');
                
                document.getElementById('progress-user-config').style = 'display:none';
                document.getElementById('btn-user-config').classList.remove('disabled');
                alert(http.responseText);
                break;
            }
        }
    }

    //Abre a comunicação
    http.open("POST","../app/controllers/user.controller.php",true);
    //Configura cabeçalho
    http.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
    //Envia a solicitação ao servidor por meio de POST
    http.send(JSON.stringify(data));
    
    
}

//Função de aviso de erro no primeiro acesso
function AcessoError(message){

    iziToast.error({
        title: 'PRIMEIRO ACESSO BLOQUEADO',
        message: 'Falha no primeiro acesso - ERROR ( ' +message+')',
        titleColor: 'red',
        icon:'material-icons',
        iconText:'block',
        iconColor:'#004D40',
        messageColor: '#004D40',
        position: 'center',
        timeout: '7000',
        layout: '2',
    });
}

//Função de aviso de email enviado
function EmailEnviado(){

    iziToast.success({
        title: 'ACESSO AUTORIZADO',
        message: 'Verifique seu email para ter acesso ao sistema.',
        position: 'center',
        timeout: '4000',
        layout: '2'
    });
}

//Função de aviso de email enviado
function PasswordRecuperado(){

    iziToast.success({
        title: 'SUA SENHA FOI ALTERADA COM SUCESSO!',
        message: 'Verifique seu email para ter acesso a ela.',
        position: 'center',
        timeout: '4000',
        layout: '2'
    });
}

//Função de aviso de email enviado
function Atualizado(message){

    iziToast.success({
        title: 'Sua atualização foi salva com sucesso!',
        message: message,
        position: 'center',
        timeout: '4000',
        layout: '2'
    });
}

//Função de aviso de email enviado
function AcessoMessage(){

    iziToast.success({
        title: 'BEM VINDO ' + sessionStorage.getItem('user') + ", VOCÊ SERÁ LOGADO COMO - [" + sessionStorage.getItem('log') +"].",
        message: 'Mantenha atualizado seus dados e sua senha após o primeiro acesso.',
        position: 'topCenter',
        timeout: '5000',
        layout: '2',
        onClosed: function (){window.location.replace("index.html");}
    });

}