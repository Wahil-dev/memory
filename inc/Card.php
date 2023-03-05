<?php
    if(!session_id()) {
        session_start();
    }
    class Card {
        private $id;
        protected $name;
        protected $current_img;
        protected $default_img;
        protected $displayed; //image afficher ou non
        protected $completed; 
        protected static $list_of_cards = [];

        public function __construct($name) {
            $this->name = $name;
            $this->default_img = "default";
            $this->completed = false;
            $this->current_img = $this->default_img;
        }

        public function get_id() {
            return $this->id;
        }

        public function get_name() {
            return $this->name;
        }

        public function get_image() {
            return "assets/img/".$this->current_img.".jpg";
        }

        public function get_default_img() {
            return $this->default_img;
        }

        public function is_displayed() {
            return $this->displayed;
        }

        public function is_completed() {
            return $this->completed;
        }

        //pour la carte clicker
        public function update_card_status() {
            if($this->is_displayed()) {
                $this->displayed = false;
                return;
            }
            $this->displayed = true;
        }

        //pour toutes les cartes que ne sont pas clicker
        public function set_displayed_to_false() {
            //si on a pas trouver les deux carte qui se resemble
            if(!$this->completed) {
                $this->displayed = false;
            }
        }


        /* ----------------- Setters Methods ------------------ */
        public function set_image($image) {
            $this->current_img = $image;
        }

        public function set_completed() {
            $this->completed = true;
        }

        public function set_id($id) {
            $this->id = $id;
        }


        /* ----------------- Static Methods ------------------ */
        public static function get_list_of_cards() {
            if(!isset($_SESSION["game_list_cards_created"])) {
                $_SESSION["game_list_cards_created"] = serialize(self::$list_of_cards);
                return self::$list_of_cards;
            }

            self::$list_of_cards = $_SESSION["game_list_cards_created"];
            return unserialize(self::$list_of_cards);
        }

        public static function create_cards_game() {
            for($i = 1; $i <= $_SESSION["even_number_game"]; $i++) {
                $name_of_card = $i;
                $card_paire_1 = new self($name_of_card);
                $card_paire_2 = new self($name_of_card);

                array_push(self::$list_of_cards, $card_paire_1, $card_paire_2);
            }  

            self::$list_of_cards = self::set_id_to_card(self::$list_of_cards);
            return self::$list_of_cards;
        }

        public static function set_id_to_card($cards_without_id) {
            $new_list_of_cards = [];
            $id = 1;
            foreach($cards_without_id as $card) {
                $card->set_id($id);
                $id++;
                array_push($new_list_of_cards, $card);
            }
            return $new_list_of_cards;
        }

        public static function get_card_clicked($id) {
            $new_list_of_cards = [];
            foreach(self::get_list_of_cards() as $card) {
                if($card->get_id() == $id) {
                    //change l'image de la carte clicker
                    if(!$card->is_displayed()) {
                        $_SESSION["last_card_opened"][] = $card->get_name();

                        if(count($_SESSION["last_card_opened"]) == 2) {
                            if($_SESSION["last_card_opened"][0] == $_SESSION["last_card_opened"][1]) {
                                $card->set_completed();
                                self::set_completed_by_name($card->get_name());
                                unset($_SESSION["last_card_opened"]);
                                header("refresh:0");
                                exit();
                            } else {
                                //delete the first element of array
                                array_shift($_SESSION["last_card_opened"]);
                            }
                        }
                        $card->set_image($card->get_name());
                    } else {
                        if(!$card->is_completed()) {
                            $card->set_image($card->get_default_img());
                        }
                    }
                    //update card status
                    if(!$card->is_completed()) {
                        $card->update_card_status();
                    }
                } else {
                    if(!$card->is_completed()) {
                        $card->set_image($card->get_default_img());
                        $card->set_displayed_to_false();
                    }
                }
                array_push($new_list_of_cards, $card);
            }
            self::update_list_of_cards($new_list_of_cards);
            header("Location: game_home.php");
            exit();
        }

        
        public static function draw_card() {
            foreach(self::get_list_of_cards() as $card) {
                echo '<article class="card">
                    <a href="?id='.$card->get_id().'" id=""><img src="'.$card->get_image()
                    .'"></a>
                </article>';
            }
        }

        
        public static function update_list_of_cards($new_list_of_cards) {
            $_SESSION["game_list_cards_created"] = serialize($new_list_of_cards);
        }

        public static function quit_game() {
            foreach(array_keys($_SESSION) as $key) {
                if(str_contains($key, "game")) {
                    unset($_SESSION[$key]);
                }
            }
            
            unset($_SESSION["click"]);
            unset($_SESSION["score"]);
            unset($_SESSION["last_card_opened"]);
            unset($_SESSION["win"]);
            //redirect to game_home
            header("location: ../game_home.php");
            exit();
        }

        public static function get_card_displayed() {
            foreach(self::get_list_of_cards() as $card) {
                if($card->get_card_status()) {
                    return $card;
                }
            }
        }

        public static function set_completed_by_name($name_of_card) {
            $new_list_of_cards = [];
            foreach(self::get_list_of_cards() as $card) {
                if($card->get_name() == $name_of_card) {
                    $card->set_completed();
                }
                array_push($new_list_of_cards, $card);
            }
            self::update_list_of_cards($new_list_of_cards);
        }

        public static function player_win() {
            global $win;
            $win = true;
            foreach(self::get_list_of_cards() as $card) {
                if($card->is_completed() == false) {
                    $win = false;
                }
            }
            if($win) {
                //self::quit_game();
            }
            return $win;
        }
    }