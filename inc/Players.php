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
            $this->best_score = 99;
            $this->ranking = 99;
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
                $_SESSION["user"] = $player_obj;
                $this->update_local_data($player_obj);
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

        /* ---------------- Getters Methods ------------------- */
        public function get_properties() {
            return $_SESSION["user"];
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
        }

        public function set_best_score($score) {
            $this->best_score = $score;
        }

        public function set_click() {
            $this->click++;
        }
    }

    $player = new Players();
    $login = "wahil";
    $password = "bvb";
    // if($player->is_exist(login: $login)) {
    //     echo "identifiant déja utiliser";
    // } else {
    //     if($player->register(login: $login, password: $password)) {
    //         echo "user inscrit";
    //     } else {
    //         echo "user n'est pas inscrit";
    //     };
    // }

    // if($player->connect(login: $login, password: $password)) {
    //     echo "player connecter";
    // } else {
    //     echo "identifiant n'est pas valide";
    // }

    echo "<br>";

    if(isset($_SESSION["user"])) {
        //var_dump($_SESSION["user"]);
    }

    echo "<br>";
    
    //$data = $player->update_local_data($_SESSION["user"]);
    
    var_dump($player->get_id());

    echo "<br>";
    
    var_dump($player->is_connected());

