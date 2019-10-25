/**
 * Autor: Alexandre Rodrigues <btt.ale@gmail.com>
 * Description: Responsável pela configuração dos elementos do DOM
 */

/**Função de inicialização do arquivo
 */
function AppConfig() {

    MainConfig();
    CarregaSidenav();
    CarregaMenuMobile();
    CarregaTabConfig();
    ValidaSenha();

}


/**Função de configuração da página inicial para cada tipo de login
 */
function MainConfig() {
    if (GetStore('log') == "USUARIO") {
        //Configura index como usuário padrão
        LogUser();
    }
    else if (GetStore('log') == "ADMINISTRADOR") {
        //Configura index como usuário administrativo
        LogAdm();
    }
    else {
        //Configura como sem usuário
        NoLog();
    }
}

/**
* Configura elementos do DOM para estado de login Sem Log
*/
function NoLog() {
    document.getElementById("menu-desktop-config").onclick = function () { showToast(); };
    document.getElementById("menu-mobile-config").onclick = function () { showToast(); };
    document.getElementById("sidenav-avatar").onclick = function () { showToast(); };
    document.getElementById("sidenav-user").onclick = function () { showToast(); };
    document.getElementById("sidenav-email").onclick = function () { showToast(); };
    document.getElementById("sidenav-inicio").onclick = function () { showToast(); };
    document.getElementById("sidenav-chamados").onclick = function () { showToast(); };
    document.getElementById("sidenav-ocorrencias").onclick = function () { showToast(); };
    document.getElementById("sidenav-requisicoes").onclick = function () { showToast(); };
    document.getElementById("sidenav-equipamentos").onclick = function () { showToast(); };
    document.getElementById("sidenav-ordens").onclick = function () { showToast(); };
    document.getElementById("sidenav-planos").onclick = function () { showToast(); };
}

/**
 * Configura elementos do DOM para estado de login Usuário
 */
function LogUser() {
    //Menu Desktop
    document.getElementById("menu-desktop-config").onclick = function () { MenuConfig(); };
    document.getElementById("menu-desktop-login").innerHTML = "LOGOUT<i id=\"menu-desktop-login-icon\" class=\"fas fa-sign-out-alt fa-3x\">";
    document.getElementById("menu-desktop-login").removeAttribute("href");
    document.getElementById("menu-desktop-login").className = "pointer";
    document.getElementById("menu-desktop-login").onclick = function () { LogoutQuestion(); };
    //Menu Mobile
    document.getElementById("menu-mobile-config").onclick = function () { MenuConfig(); };
    document.getElementById("menu-mobile-login").innerHTML = "LOGOUT<i id=\"menu-mobile-login-icon\" class=\"fas fa-sign-out-alt fa-3x\">";
    document.getElementById("menu-mobile-login").removeAttribute("href");
    document.getElementById("menu-mobile-login").className = "pointer";
    document.getElementById("menu-mobile-login").onclick = function () { LogoutQuestion(); };
    //Sidenav User
    document.getElementById("sidenav-avatar").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-user").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-user").innerHTML = "<span class=\"white-text\">" + GetStore('user') + "</span>"
    document.getElementById("sidenav-email").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-email").innerHTML = "<span class=\"white-text\">" + decodeURIComponent(GetStore('email')) + " </span>"
    //Sidenav Itens
    document.getElementById("sidenav-inicio").onclick = function () { showToast(); };
    document.getElementById("sidenav-chamados").onclick = function () { showToast(); };
    document.getElementById("sidenav-ocorrencias").onclick = function () { showToast(); };
    document.getElementById("sidenav-requisicoes").onclick = function () { showToast(); };
    document.getElementById("sidenav-equipamentos").onclick = function () { showToast(); };
    document.getElementById("sidenav-ordens").onclick = function () { showToast(); };
    document.getElementById("sidenav-planos").onclick = function () { showToast(); };

    document.getElementById("nomeUsuario-config").innerHTML = sessionStorage.getItem('user');
}

/**
 * Configura elementos do DOM para estado de login administrador
 */
function LogAdm() {
    //Menu Desktop
    document.getElementById("menu-desktop-config").onclick = function () { MenuConfig(); };
    document.getElementById("menu-desktop-login").innerHTML = "LOGOUT<i id=\"menu-desktop-login-icon\" class=\"fas fa-sign-out-alt fa-3x\">";
    document.getElementById("menu-desktop-login").removeAttribute("href");
    document.getElementById("menu-desktop-login").className = "pointer";
    document.getElementById("menu-desktop-login").onclick = function () { LogoutQuestion(); };
    //Menu Mobile
    document.getElementById("menu-mobile-config").onclick = function () { MenuConfig(); };
    document.getElementById("menu-mobile-login").innerHTML = "LOGOUT<i id=\"menu-mobile-login-icon\" class=\"fas fa-sign-out-alt fa-3x\">";
    document.getElementById("menu-mobile-login").removeAttribute("href");
    document.getElementById("menu-mobile-login").className = "pointer";
    document.getElementById("menu-mobile-login").onclick = function () { LogoutQuestion; };
    //Sidenav User
    document.getElementById("sidenav-avatar").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-user").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-user").innerHTML = "<span class=\"white-text\">" + GetStore('user') + "</span>"
    document.getElementById("sidenav-email").onclick = function () { MenuConfig(); };
    document.getElementById("sidenav-email").innerHTML = "<span class=\"white-text\">" + decodeURIComponent(GetStore('email')) + " </span>"
    //Sidenav Itens
    document.getElementById("sidenav-inicio").onclick = function () { showToast(); };
    document.getElementById("sidenav-chamados").onclick = function () { showToast(); };
    document.getElementById("sidenav-ocorrencias").onclick = function () { showToast(); };
    document.getElementById("sidenav-requisicoes").onclick = function () { showToast(); };
    document.getElementById("sidenav-equipamentos").onclick = function () { showToast(); };
    document.getElementById("sidenav-ordens").onclick = function () { showToast(); };
    document.getElementById("sidenav-planos").onclick = function () { showToast(); };
    document.getElementById("sidenav-administrador-item").style.display = "inline";

    document.getElementById("nomeUsuario-config").innerHTML = sessionStorage.getItem('user');
}

/**
 * Configura funcionamento do menu de configuração como abertura e fechamento e opções de 
 * limpeza e condicionamento.
 */
function MenuConfig() {
    //Cria variavel persistente
    MenuConfig.menuDisplay;
    //Atribui a variavel um estado caso seja a primeira atribuição
    if (MenuConfig.menuDisplay == undefined) {
        MenuConfig.menuDisplay = false;
    }
    //Abre menu de configurações e fecha a visualização da apresentação do app
    if (MenuConfig.menuDisplay == false) {
        MenuConfig.menuDisplay = true;
        document.getElementById("presentation-content").style.display = "none";
        document.getElementById("content-config").style.display = "block";
    }
    //Retorna ao estado inicial no condicionamento especificado
    else {
        MenuConfig.menuDisplay = false;
        document.getElementById("presentation-content").style.display = "";
        document.getElementById("content-config").style.display = "none";
    }
}

/**
 * Validação de senha nova
 */
function ValidaSenha()
{
    var pass1 = document.getElementById("passNew1-config");
    var pass2 = document.getElementById("passNew2-config");

    pass1.onkeyup = function(){
        if(!(pass1.value == pass2.value)){
            pass1.classList.add("invalid");
            pass2.classList.add("invalid");
        }
        else{
            pass1.classList.remove("invalid");
            pass2.classList.remove("invalid");
        }
    }

    pass2.onkeyup = function(){
        if(!(pass1.value == pass2.value)){
            pass1.classList.add("invalid");
            pass2.classList.add("invalid");
        }
        else{
            pass1.classList.remove("invalid");
            pass2.classList.remove("invalid");
        }
    }

}


/**
 * Carregamento das funcções materiliaze
 */
function CarregaSidenav() {
    var instance = new M.Sidenav(document.querySelector('.sidenav'));
}

/**
 * Carregamento das funcções materiliaze
 */
function CarregaMenuMobile() {
    var options = {};
    options.coverTrigger = false;
    options.constrainWidth = false;
    var dropdownMenuMobile = new M.Dropdown(document.querySelector('.dropdown-trigger'), options);
}

/**
 * Carregamento das funcções materiliaze
 */
function CarregaTabConfig() {

    var tab_config = new M.Tabs(document.querySelector('#tabs-config'), {
        onShow: function () {
            //Lista de forms do tab
            document.getElementById("form-config-user").reset();
            document.getElementById("form-config-senha").reset();
        }
    });
    
}



//Toast error
function showToast() {

    iziToast.error({
        title: 'ACESSO RESTRITO',
        message: 'Você precisa estar logado.',
        titleColor: 'red',
        icon: 'material-icons',
        iconText: 'block',
        iconColor: '#004D40',
        messageColor: '#004D40',
        position: 'center',
        timeout: '2000',
        layout: '2',

    });
}

//Toast Logout
function LogoutQuestion(event) {

    iziToast.question({
        timeout: 5000,
        close: false,
        overlay: true,
        toastOnce: true,
        id: 'question',
        //zindex: 999,
        transitionIn: 'flipInX',
        title: 'Logout',
        message: 'Você realmente deseja sair?',
        position: 'center',
        backgroundColor: '#ff4d4d',
        titleColor: 'white',
        messageColor: 'silver',
        iconColor: 'white',
        buttons: [
            ['<button><b>SIM</b></button>', function (instance, toast) {

                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                sessionStorage.clear();
                location.reload(true);
                

            }],
            ['<button>NÃO</button>', function (instance, toast) {

                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                event.stopImediatePropagation();
                event.preventDefault();

            }, true],
        ],
    });
}

//função get de cookies criados pelo servidor
function GetStore(name) {

    if (sessionStorage == null) {
        return false;
    }
    
    return sessionStorage.getItem(name);
}

