<?php
    require("Bdd.php");
    
    class Players extends Bdd {
        private $id;
        protected $login;
        protected $password;
        protected $click;
        protected $score;
        protected $best_score;
        protected $ranking;

        protected $tbname;
        public $conn;

        public function __construct() {
            $this->conn = Parent::__construct();
            $this->tbname = "players";
            $this->best_score = 0;
            $this->ranking = 0;
            $this->click = 0; //number of click
        }

        /* ---------------- Others Methods ------------------ */
        public function register($login, $password) {
            $sql = "INSERT INTO ".$this->get_table_name()."(login, password, best_score, ranking) VALUES(?, ?, ?, ?)";
            $best_score = $this->get_best_score();
            $ranking = $this->get_ranking();
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->bindParam(3, $best_score);
            $req->bindParam(4, $ranking);
            $req->execute();
            
            if($req->rowCount()) {
                return true;
            }
            return false;
        }

        public function connect($login, $password) {
            $sql = "SELECT * FROM ".$this->get_table_name()." WHERE login = ? && password = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->execute();

            if($req->rowCount()) {
                $player_obj = $req->fetchObject();
                $this->update_local_data($player_obj);
                $_SESSION["user"] = $player_obj;
                return true;
            } 
            return false;
        }
        
        public function is_exist($login) {
            $sql = "SELECT * FROM ".$this->get_table_name()." WHERE login = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->execute();

            if($req->rowCount()) {
                return true;
            }
            return false;
        }

        public function is_connected() {
            if($this->id) {
                return true;
            }
            return false;
        }

        public function update_local_data($data) {
            foreach($data as $key => $value) {
                $this->$key = $value;
            }
        }

        public function disconnect() {
            $this->delete_properties();
            session_unset();
            session_destroy();
        }

        public function delete() {
            $sql = "DELETE FROM ".$this->get_table_name()." WHERE id = '$this->id'";
            $req = $this->conn->query($sql);

            $this->disconnect();//pour les déconnexion
            return $req;
        }

        protected function delete_properties() {
            foreach(array_keys((array)$this->get_properties()) as $key) {
                $this->$key = null;
            }
        }

        /* ---------------- Getters Methods ------------------- */
        public function get_properties() {
            if(isset($_SESSION["user"])) {
                return $_SESSION["user"];
            }
            return false;
        }

        public function get_id() {
            return $this->id;
        }

        public function get_login() {
            return $this->login;
        }

        public function get_score() {
            return $this->score;
        }

        public function get_best_score() {
            return $this->best_score;
        }

        public function get_ranking() {
            return $this->ranking;
        }

        public function get_number_of_click() {
            return $this->click;
        }

        protected function get_table_name() {
            return $this->tbname;
        }

        /* ---------------- Setters Methods ------------------- */
        public function set_score($score) {
            $this->score = $score;
            if($this->get_score() < $this->get_best_score()) {
                $this->set_best_score();
            }
        }

        protected function set_best_score() {
            $new_best_score = $_SESSION["user"]->best_score = $this->best_score = $this->get_score();
            $sql = "UPDATE ".$this->get_table_name()." SET best_score = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $new_best_score);
            $req->execute();
        }

        public function set_click() {
            $this->click++;
        }
    }

    $player = new Players();


