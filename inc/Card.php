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
        protected static $list_of_cards = [];

        public function __construct($id, $name) {
            $this->id = $id;
            $this->name = $name;
            $this->default_img = "default";
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

        //pour la carte clicker
        public function update_card_status() {
            if($this->is_displayed()) {
                $this->displayed = false;
                return;
            }
            $this->displayed = true;
        }

        //pour toutes les cartes que ne sont pas clicker
        public function set_disabled_to_false() {
            $this->displayed = false;
        }


        /* ----------------- Setters Methods ------------------ */
        public function set_image($image) {
            $this->current_img = $image;
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
                $card_id = $i;
                $card_paire_1 = new self($card_id, $name_of_card);
                $card_paire_2 = new self($card_id, $name_of_card);

                array_push(self::$list_of_cards, $card_paire_1, $card_paire_2);
            }   
            return self::$list_of_cards;
        }

        public static function get_card_clicked($name) {
            $new_list_of_cards = [];
            foreach(self::get_list_of_cards() as $card) {
                if($card->get_name() == $name) {
                    //change l'image de la carte clicker
                    if(!$card->is_displayed()) {
                        $card->set_image($name);
                    } else {
                        $card->set_image($card->get_default_img());
                    }
                    //update card status
                    $card->update_card_status();
                } else {
                    $card->set_image($card->get_default_img());
                    $card->set_disabled_to_false();
                }
                array_push($new_list_of_cards, $card);
            }
            self::update_list_of_cards($new_list_of_cards);
        }

        
        public static function draw_card() {
            foreach(self::get_list_of_cards() as $card) {
                echo '<article class="card">
                    <a href="?name='.$card->get_name().'" id=""><img src="'.$card->get_image()
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
    }