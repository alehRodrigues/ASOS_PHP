<?php

require 'Model.class.php';

final class User extends Model
{
    private $id = null;
    private $user = null;
    private $email = null;
    private $log = 'USUARIO';
    private $password = null;
    private $created = null;
    private $last_access = null;
    private $permissoes = array(

        'chamados' => "1-1-0-1",
        'ocorrencias' => "1-1-1-1",
        'requisicoes' => "1-1-1-0",
        'equipamentos' => "0-1-0-0",
        'ordens' => "0-1-0-0",
        'planos' => "0-1-0-0",

    );

    /**
     * __construct Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('usuarios');
        
    }

    public function ParseUser()
    {
        return array(

            'id' => $this->id,
            'user' => $this->user,
            'email' => $this->email,
            'log' => $this->log,
            'password' => $this->password,
            'created' => $this->created,
            'last_access' => $this->last_access,
            'permissoes' => json_encode($this->permissoes),

        );
    }

    private function SetHashSenha($Senha)
    {
        return password_hash($Senha,PASSWORD_DEFAULT);
    }

    public function UpdateDataLogin()
    {
        try
        {
            $sql = "UPDATE " .$this->table. " SET last_access=:last_access WHERE email=:email";
            $data = $this->connection->prepare($sql);
            $data->bindValue(":email",$this->email);
            date_default_timezone_set("America/Sao_Paulo");
            $data->bindValue(":last_access",date("Y-m-d H:i:s"));
            $data->execute();

            if($data->rowCount() == 1)
            {
                return true;
            }
            
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function UpdateSimple($Field, $Value, $Email)
    {
        try
        {
            $sql = "UPDATE ". $this->table . " SET ".$Field."=:" .$Field. " WHERE email=:email";
            $update = $this->connection->prepare($sql);
            $update->bindValue($Field,$Value);
            $update->bindValue(":email",$Email);
            $update->execute();

            if($update->rowCount() == 1)
            {
                return true;
            }
        }
        catch (Excepetion $e)
        {
            return $e->getMessage();
        }

    }

    public function Delete()
    {
        try
        {
            $sql = 'DELETE FROM ' . $this->table . 'WHERE email = :email';
            $delete = $this->connection->prepare($sql);
            $delete->bindValue(":email",$this->email);
            $delete->execute();

            return true;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

     /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of userName
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of userName
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of log
     */ 
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set the value of log
     *
     * @return  self
     */ 
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get the value of senha
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $this->SetHashSenha($password);

        return $this;
    }

    /**
     * Get the value of created
     */ 
    public function getCreated()
    {
        return $this->created;
    }

        /**
     * Get the value of last_access
     */ 
    public function getLast_access()
    {
        return $this->last_access;
    }

    /**
     * Set the value of last_access
     *
     * @return  self
     */ 
    public function setLast_access($last_access)
    {
        $this->last_access = $last_access;

        return $this;
    }

   /**
     * Get the value of permissoes
     */ 
    public function getPermissoes()
    {
        return $this->permissoes;
    }

    /**
     * Set the value of permissoes
     *
     * @return  self
     */ 
    public function setPermissoes($permissoes)
    {
        $this->permissoes = $permissoes;

        return $this;
    }

    
    
    




   

    
}