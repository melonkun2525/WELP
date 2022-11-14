<?php

/**
 * 
 * @package database
 * 
 * @author Yoshida Kento
 * 
 * @since PHP7.2
 * 
 * @version 1.0
 * 
 * データベースに関連するすべての処理をここで行う。
 * 
*/
class database {

    private $db_handler;
    private $db_statement;
    private $sql;
    private $bind_array = [];

    public function connect(){
        $config = include('../config/config.php');
        try{
            $this->db_handler = new PDO(
                "mysql:host=".$config["DB_HOST"].";dbname=".$config["DB_DATABASE"],
                $config["DB_USER"],
                $config["DB_PASSWORD"],
                [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
                ]
            );
            date_default_timezone_set("Asia/Tokyo");
        }catch(\PDOException $e){
            if($config["DEBUG_MODE"]){
                echo $e->getMessage();
                exit;
            }
        }
        unset($config);
    }

    public function setSQL($sql){
        $this->sql = $sql;
    }

    public function setBindArray($array){
        $this->bing_array = $array;
    }

    public function execute(){
        try{
            if(empty($this->db_handler)){
                $this->connect();
            }
            $this->db_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_statement = $this->db_handler->prepare($this->sql);
            foreach($bind_array as $key => $value){
                $this->db_statement->bindParam($key, $value);
            }
            $this->db_statement->execute();
        }catch(Exception $e){
            //エラー処理
        }
    }

    public function fetch(){
        return $this->db_statement->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(){
        return $this->db_statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
