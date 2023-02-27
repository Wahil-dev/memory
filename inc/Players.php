<?php
    require("Bdd.php");
    $bdd = new Bdd();
    
    class Players {
        private $id;
        protected $login;
        protected $password;
        protected $coup;
        protected $score;
        protected $best_score;
        protected $ranking;
        public $conn;

        public function __construct() {
            global $bdd;
            $this->conn = $bdd;
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
    }

    $player = new Players();
    var_dump ($player->conn);