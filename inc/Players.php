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
        protected $ranking;

        protected $ranking_list;
        private $current_player;
        protected $tbname;
        public $conn;

        private static $instance = NULL;

        //Get object created
        public static function get_instance() {
            if(self::$instance === NULL) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {
            $this->conn = Parent::__construct();
            $this->tbname = "players";
            $this->best_score = 999;
            $this->ranking_list = new Ranking();
        }

        /* ---------------- Others Methods ------------------ */
        public function register($login, $password) {
            $sql = "INSERT INTO ".$this->get_table_name()."(login, password, best_score, ranking) VALUES(?, ?, ?, ?)";

            $this->ranking = $this->initialize_rank();

            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $login);
            $req->bindParam(2, $password);
            $req->bindParam(3, $this->best_score);
            $req->bindParam(4, $this->ranking);
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

                //remplire les attribut de player
                $this->update_local_data($player_obj);

                //session player pour l'utiliser pour la connexion sur les autres pages de site et pour autres utlisations
                $_SESSION["player"] = $player_obj;

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
        
        protected function initialize_rank() {
            $sql = "SELECT MAX(ranking) as ranking FROM ".$this->get_table_name();
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchObject()->ranking + 1; // 1 plus le dernier rank
        }

        public function update_score($even_number) {
            $new_score = $this->get_number_of_click() / $even_number; //rule
            $this->set_score($new_score);

            if($this->get_score() < $this->get_best_score()) {
                $this->set_best_score();
            }
        }

        /* ---------------- Getters Methods ------------------- */
        public function get_properties() {
            $list = [
                "id" => $this->get_id(),
                "login" => $this->get_login(),
                "password" => $this->get_password(),
                "best_score" => $this->get_best_score(),
                "ranking" => $this->get_ranking(),
                "score" => $this->get_score(),
                "click" => $this->get_number_of_click(),
            ];
            
            return $list;
        }

        public function get_id() {
            return $this->id;
        }

        public function get_login() {
            return $this->login;
        }

        public function get_password() {
            return $this->password;
        }

        public function get_score() {
            $this->score = $_SESSION["score"];
            return $this->score;
        }

        public function get_best_score() {
            return $this->best_score;
        }

        public function get_ranking() {
            return $this->ranking;
        }

        public function get_number_of_click() {
            $this->click = $_SESSION["click"];
            return $this->click;
        }

        public function get_current_player() {
            return $this->current_player;
        }

        protected function get_table_name() {
            return $this->tbname;
        }

        public function get_rank_by_id() {
            $sql = "SELECT * FROM ".$this->get_table_name()." 
            WHERE id = $this->id";  
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchObject();
        }


        /* ---------------- Setters Methods ------------------- */
        public function set_score($score_game) {
            $_SESSION["score"] = $score_game;

            //supprimer ou vider la valuer de session score apres la fin du partie (quand on efface la fenêtre sortie a la finn de la partie) =crie un function new_game est mit a l'intérieur session['score] = null, session['click] = 0 ... 
        }

        protected function set_best_score() {
            $new_best_score = $_SESSION["player"]->best_score = $this->best_score = $this->get_score();
            $sql = "UPDATE ".$this->get_table_name()." SET best_score = ? WHERE id = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $new_best_score);
            $req->bindParam(2, $this->id);
            $req->execute();

            //mettre a jour les classement de joueur
            $this->update_rank();
        }

        public function set_click() {
            $_SESSION["click"] = ++$this->click;
        }

        protected function set_rank($new_rank) {
            $this->ranking = $new_rank;
            $_SESSION["player"]->ranking = $new_rank;
        }

        protected function update_rank() {
            //mise a jour le classement des joueur quand qullqu'un atteint un nouveau best_score
            $this->ranking_list->update_ranking_list();

            //set new_rank
            $this->set_rank($this->get_rank_by_id()->ranking);
            
        }
    }

    $player = Players::get_instance(); //Singleton pattern
