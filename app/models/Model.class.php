<?php

require '../database/Connection.class.php';

abstract class Model 
{
    protected $connection;
    protected $table;

    /**
     * __construct
     *
     * @param string $Table Nome da tabela no banco de dados
     * @return void
     */
    public function __construct($Table)
    {
        $this->table = $Table;
        $this->connection = Connection::Connect();
    }

    /**
     * Add Adiciona objeto no banco de dados
     *
     * @param arrayObject $Data Objeto a ser salvo no banco de dados
     * @return boolean verdadeiro se concluido 
     * @return string mensagem de erro se não concluido
     */
    public function Add(array $Data)
    {
        try
        {
            $sql = "INSERT INTO " .$this->table. "(";
            $sql = $sql . implode(",",array_keys($Data)). ") VALUES( ";
            $sql = $sql .' :' .implode(',:',array_keys($Data)). ")";

            $add = $this->connection->prepare($sql);

            $add->execute($Data);
            
            return true;
            die();
        }
        catch(Exception $e)
        {
            if ($e->getCode() == 23000)
            {
                return 'Usuário já cadastrado!';
                die();
            }
        }
    }
    
    public function Read($Field, $Value)
    {
        try
        {
            $sql = "SELECT * FROM " .$this->table. " WHERE " .$Field. "=:" .$Field;
            $read = $this->connection->prepare($sql);
            $read->bindValue($Field,$Value);
            $read->execute();
            return $read->fetch();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function ReadAll()
    {
        try
        {
            $sql = "SELECT * FROM " .$this->table;
            $read = $this->connection->prepare($sql);
            $read->execute();
            return $read->fetchAll();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    abstract public function Delete();

}