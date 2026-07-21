<?php

require_once __DIR__ . "/../config/env.php";

class Database
{

    private $host;
    private $database;
    private $username;
    private $password;
    private $charset;


    private $connection = null;



    public function __construct()
    {

        $config = require __DIR__ . "/../config/database.php";


        $this->host = $config["host"];

        $this->database = $config["database"];

        $this->username = $config["username"];

        $this->password = $config["password"];

        $this->charset = $config["charset"];
    }




    public function connect()
    {


        if ($this->connection !== null) {
            return $this->connection;
        }



        try {


            $dsn =
                "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";



            $this->connection = new PDO(

                $dsn,

                $this->username,

                $this->password,

                [

                    PDO::ATTR_ERRMODE =>
                    PDO::ERRMODE_EXCEPTION,


                    PDO::ATTR_DEFAULT_FETCH_MODE =>
                    PDO::FETCH_ASSOC,


                    PDO::ATTR_EMULATE_PREPARES =>
                    false

                ]

            );



            return $this->connection;
        } catch (PDOException $e) {

            error_log($e->getMessage());


            http_response_code(500);


            echo json_encode([

                "status" => false,

                "message" => "Database connection failed"

            ]);


            exit;
        }
    }
}
