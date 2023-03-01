<?php
    require_once("Bdd.php");
    require_once("Model.php");
    require_once("Ranking.php");
    
    class Players extends Bdd {
        private $id;
        protected $login;
        protected $password;
        protected $click;
        protected $score;
        protected $best_score;
        protected $my_rank;

        protected $ranking_list;
        private $current_player;
        protected $tbname;
        public $conn;

        public function __construct() {
            $this->conn = Parent::__construct();
            $this->tbname = "players";
            $this->best_score = 5;
            $this->click = 0; //number of click
            $this->ranking_list = new Ranking();
        }

        /* ---------------- Others Methods ------------------ */
        public function register($login, $password) {
            $sql = "INSERT INTO ".$this->get_table_name()."(login, password, best_score, ranking) VALUES(?, ?, ?, ?)";

            $this->my_rank = $this->initialize_rank();

            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->bindParam(3, $this->best_score);
            $req->bindParam(4, $this->my_rank);
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

                $_SESSION["player"] = $player_obj;
                $_SESSION["score"] = $this->get_score();
                $_SESSION["click"] = $this->get_number_of_click();

                $this->current_player = $this;
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

            header("location: ../index.php");
            exit();
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

        public function redirect_if_is_connected() {
            if($this->is_connected()) {
                header("location: index.php");
                exit();
            }
        }

        /* ---------------- Getters Methods ------------------- */
        public function get_properties() {
            if(isset($_SESSION["player"])) {
                return $_SESSION["player"];
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

        public function get_my_rank() {
            return $this->my_rank;
        }

        public function get_number_of_click() {
            return $this->click;
        }

        public function get_current_player() {
            return $this->current_player;
        }

        protected function get_table_name() {
            return $this->tbname;
        }

        /* ---------------- Setters Methods ------------------- */
        public function set_score($score) {
            $this->score = $score;
            $_SESSION["score"] = $this->get_score();

            if($this->get_score() < $this->get_best_score()) {
                $this->set_best_score();
            }
        }

        protected function set_best_score() {
            $new_best_score = $_SESSION["player"]->best_score = $this->best_score = $this->get_score();
            $sql = "UPDATE ".$this->get_table_name()." SET best_score = ? WHERE id = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $new_best_score);
            $req->bindParam(2, $this->id);
            $req->execute();
        }

        public function set_click() {
            $this->click = $_SESSION["click"] + 1;
            $_SESSION["click"] = $this->get_number_of_click();
        }

        protected function initialize_rank() {
            $sql = "SELECT MAX(ranking) as my_rank FROM ".$this->get_table_name();
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchObject()->my_rank + 1; // 1 plus le dernier rank
        }

        // protected function set_rank($rank) {
        //     if($new_rank < $this->get_my_rank()) {
        //         $this->my_rank = $new_rank;
        //         $_SESSION["player"]->ranking = $this->get_my_rank();

        //         $sql = "UPDATE ".$this->get_table_name()." SET ranking = ? WHERE id = ?";
        //         $req = $this->conn->prepare($sql);
        //         $req->bindParam(1, $new_rank);
        //         $req->bindParam(2, $id);
        //         $req->execute();
        //     }
        // }
    }

    $player = new Players();


