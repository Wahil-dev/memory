<?php
    require_once("Bdd.php");

    class Ranking extends Bdd {
        protected $conn;
        protected $tbname;

        public function __construct() {
            $this->tbname = "players";
            $this->conn = Parent::__construct();
        }

        /* --------------------- Getters Methods -------------------- */
        protected function get_table_name() {
            return $this->tbname;
        }

        protected function get_all_players() {
            $sql = "SELECT * FROM ".$this->get_table_name()." ORDER BY best_score ASC";
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_OBJ);
        }

        public function get_best_ten_player() {
            $sql = "SELECT id, login, ranking, best_score FROM ".$this->get_table_name()." 
            ORDER BY best_score ASC LIMIT 10 OFFSET 0";  
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_OBJ);
        }


        /* -------------------- Setters Methods -------------------- */



        /* -------------------- Others Methods --------------------- */
        protected function process_rank($id, $new_rank) {
            $sql = "UPDATE ".$this->get_table_name()." SET ranking = ? WHERE id = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $new_rank);
            $req->bindParam(2, $id);
            $req->execute();
        }

        public function display_best_ten_player() { ?>
            <section class="classement">
                <h1 class="title">classement</h1>
                <article class="list-players">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Login</th>
                                <th>Best Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i = 0 ; isset($this->get_best_ten_player()[$i]); $i++) :?>
                                <?php // *** Partager les classements
                                    $this->process_rank($this->get_best_ten_player()[$i]->id, $i)
                                    ?>
                                <tr>
                                    <td><?=
                                            $this->get_best_ten_player()[$i]->ranking
                                        ?></td>
                                        <td><?=
                                            $this->get_best_ten_player()[$i]->login
                                        ?></td>
                                        <td><?=
                                            $this->get_best_ten_player()[$i]->best_score
                                        ?>s</td>
                                </tr>
                                <?php endfor ;?>
                        </tbody>
                    </table>
                </article>
            </section>
        <?php }

        public function update_ranking_list() {
            $player = $this->get_all_players();
            for($i = 0 ; isset($player[$i]); $i++) {
                $this->process_rank($player[$i]->id, $i);
            }
        }
    }

    $ranking = new Ranking();