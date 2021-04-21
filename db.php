<?php
class db
{
    public function connect()
    {
        define('DB_HOST', "18.229.16.146");
        define('DB_USER', "gonder");
        define('DB_PASSWORD', "G0nder!#200&");
        define('DB_NAME', "Carneiro");
        define('DB_DRIVER', "sqlsrv");

        $pdoConfig  = DB_DRIVER . ":" . "Server=" . DB_HOST . ";";
        $pdoConfig .= "Database=" . DB_NAME . ";";

        try {
            if (!isset($dbConnection)) {
                $dbConnection =  new PDO($pdoConfig, DB_USER, DB_PASSWORD);
                $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $dbConnection;
        } catch (PDOException $e) {
            $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem .= "\nErro: " . $e->getMessage();
            throw new Exception($mensagem);
        }
    }
}
