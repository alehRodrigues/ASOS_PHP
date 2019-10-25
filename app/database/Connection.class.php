<?php

final class Connection
{
    public static function Connect()
    {
        $config = require '../Config.php';

        try
        {
            $pdo = new PDO("mysql:host={$config['db']['host']}; dbname={$config['db']['dbname']};   charset={$config['db']['charset']}",$config['db']['username'],$config['db']    ['password']);
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, pdo::FETCH_OBJ);

        return $pdo;
        die();
    }
}