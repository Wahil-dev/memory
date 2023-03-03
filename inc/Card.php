<?php
    if(!session_id()) {
        session_start();
    }
    class Card {
        private $id;
        protected $displayed;
        protected $current_img;
        protected static $list_of_cards = [];

        public function __construct($id) {
            $this->id = $id;
            $this->displayed = false;
            $this->current_img = "default";
        }

        public function get_id() {
            return $this->id;
        }

        public function get_image() {
            return "assets/img/".$this->current_img.".jpg";
        }


        /* ----------------- Setters Methods ------------------ */
        public function set_image($id) {
            $this->current_img = $id;
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
                $card = new self($i);
                array_push(self::$list_of_cards, $card);
            }   

            return self::$list_of_cards;
        }

        public static function get_card_clicked($id) {
            $new_list_of_cards = [];
            foreach(self::get_list_of_cards() as $card) {
                if($card->get_id() == $id) {
                    $card->set_image($id);
                    var_dump($card);
                }
                array_push($new_list_of_cards, $card);
            }

            self::update_list_of_cards($new_list_of_cards);
        }

        
        public static function draw_card() {
            foreach(self::get_list_of_cards() as $card) {
                echo '<article class="card">
                    <a href="?id='.$card->get_id().'" id=""><img src="'.$card->get_image().'"></a>
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
    }