<?php
    require("Bdd.php");
    
    class Players extends Bdd {
        private $id;
        protected $login;
        protected $password;
        protected $coup;
        protected $score;
        protected $best_score;
        protected $ranking;

        protected $tbname;
        public $conn;

        public function __construct() {
            $this->conn = Parent::__construct();
            $this->tbname = "players";

            $this->best_score = 99;
            $this->ranking = 99;
        }

        /* ---------------- Others Methods ------------------ */
        public function register($login, $password) {
            $sql = "INSERT INTO ".$this->get_table_name()."(login, password, best_score, ranking) VALUES(?, ?, ?, ?)";

            $best_score = 4;
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->bindParam(3, $best_score);
            $req->bindParam(4, $best_score);
            $req->execute();
            
            if($req->rowCount()) {
                return true;
            }
            return false;
        }

        public function is_exist($login, $password) {
            $sql = "SELECT * FROM ".$this->get_table_name()." WHERE login = ? && password = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->execute();

            if($req->rowCount()) {
                return true;
            }
            return false;
        }

        /* ---------------- Getters Methods ------------------- */
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

        protected function get_table_name() {
            return $this->tbname;
        }

        /* ---------------- Setters Methods ------------------- */
        public function set_score($score) {
            $this->score = $score;
        }

        public function set_best_score($score) {
            $this->best_score = $score;
        }
    }

    $player = new Players();
    if($player->is_exist("wahil", "bvbu")) {
        echo "identifiant déja utiliser";
    } else {
        var_dump($player->register("wahil", "bvbu"));
    }