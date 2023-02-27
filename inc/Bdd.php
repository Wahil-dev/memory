<?php
    class Bdd {
        protected $server_name;
        protected $username;
        protected $db_password;
        protected $dbname;
        protected $cnx;

        public function __construct($server_name = "localhost", $username = "root", $db_password = "", $dbname = "classes") {
            if(!session_id()) {
                session_start();
            }

            $this->server_name = $server_name;
            $this->username = $username;
            $this->db_password = $db_password;
            $this->dbname = $dbname;
            
            $this->connexion();
        }

        private function connexion() {
            try {
                $this->cnx = new PDO("mysql:host=$this->server_name; dbname=$this->dbname", $this->username, $this->db_password);
                $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "connexion réussie";

            } catch (PDOException $e){
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }