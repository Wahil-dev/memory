<?php 
    if(!session_id()) {
        session_start();
    }
    class Model {

        /* ------------------ Static Methods ------------------ */

        // supprimer les espaces / transformer les tags html en text / back slash
        public static function process_input($inp) {
            $inp = trim($inp);
            $inp = stripslashes($inp);
            $inp = htmlspecialchars($inp);
            return $inp;
        }

        public static function is_valid($password) {
            $uppercase = preg_match("@[A-Z]@", $password);
            $lowercase = preg_match("@[a-z]@", $password);
            $number = preg_match("@[0-9]@", $password);
            $specialChars = preg_match("@[^\w]@", $password);
            $length = strlen($password) >= 8 && strlen($password) < 255;
            if(!$uppercase || !$lowercase || !$number || !$specialChars || !$length) {
                return false;
            }
            return true;
        }

        public static function display_message($message) {
            echo "<script>alert('$message')</script>";
        }

        public static function delete_err_session() {
            foreach($_SESSION as $key => $value) {
                if(str_contains($key, "Err")) {
                    unset($_SESSION[$key]);
                }
            }
        }

        public static function print_err($session_name) {
            if(isset($_SESSION[$session_name])) {
                echo '<p class="error-msg">'.$_SESSION[$session_name].'</p>';
            }
        }
    }