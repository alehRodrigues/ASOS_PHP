<?php

require '../Config.php';

final class Token
{
    protected $id;
    protected $usuario;
    protected $email;
    protected $log;

    protected $dataCriacao;
    protected $dataValidade;
    protected $dataLogout;

    protected $atualizacoes = 0;
    
    protected $secret;


    public function __construct($Usr)
    {
        
        $this->id = $Usr->ID;
        $this->usuario = $Usr->user;
        $this->email = $Usr->email;
        $this->log = $Usr->log;

        $this->ConfigData($this->dataCriacao, $this->dataValidade);

        $this->secret = sha1($this->id . SECRET);
        
        $this::SaveLog($this);
    }

    protected function ConfigData(&$DataCriacao, &$DataValidade)
    {
        $TEMPO_VALIDADE = "+10 minutes"; //MINUTOS
        date_default_timezone_set("America/Sao_Paulo");
        $DataCriacao = date("d-m-Y H:i:s");
        $DataValidade = date("d-m-Y H:i:s",strtotime($TEMPO_VALIDADE));
        $this->dataLogout = $DataValidade;
    }

    private static function SaveLog($Token)
    {
        $caminho = 'C:\laragon\www\sismat.v3\log\\' . date("d-m-Y");
        if(!is_dir($caminho))
        {
            mkdir($caminho . '\\');
        }        
        $file = $caminho . '\\' . $Token->id .' - ' . $Token->atualizacoes . '.json';
        
        $file = fopen($file,"w");
        $content = (array) $Token;
        $content = json_encode($content);
        $content = str_replace('\u0000*\u0000','',$content);
        fwrite($file, $content);
        fclose($file);
        
        
    }

    public function GetToken()
    {
        $content = json_encode((array)$this);
        $content = str_replace('\u0000*\u0000','',$content);
        return base64_encode($content);
    }

    public static function ValidateToken(&$Token)
    {
        date_default_timezone_set("America/Sao_Paulo");
        $tokenJson = base64_decode($Token);
        $tokenObj = (object)json_decode($tokenJson,true);
        //print_r($tokenJson);
        $hash = sha1($tokenObj->id . SECRET);
        
        /* echo $hash .' - hash <br>';
        echo $tokenObj->secret .' - secret <br>';
        echo $tokenObj->dataValidade .' - Validade <br>';
        echo  date("d-m-Y H:i:s") .' - Data <br>'; */
        
        if(($hash === $tokenObj->secret) && (date("d-m-Y H:i:s") < $tokenObj->dataValidade))
        {
            
            $tokenObj->dataValidade = date("d-m-Y H:i:s",strtotime("+10 minutes"));
            $tokenObj->atualizacoes += 1;
            /* print_r($tokenObj); */
            self::SaveLog($tokenObj);
            return true;
        }
        else
        {
            
            return 'não deu';
        }
    }
    
   
}

