<?php
    require_once("Bdd.php");

    class Ranking extends Bdd {
        protected $conn;
        protected $tbname;
        protected $players_tbname;

        public function __construct() {
            $this->tbname = "ranking";
            $this-> players_tbname = "players";
            $this->conn = Parent::__construct();
        }

        /* --------------------- Getters Methods -------------------- */
        protected function get_table_name() {
            return $this->tbname;
        }

        public function get_best_ten_player() {
            $sql = "SELECT id, login, ranking, best_score FROM ".$this->players_tbname." 
            ORDER BY best_score ASC LIMIT 10 OFFSET 0";  
            
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_OBJ);
        }

        public function get_rank_by_id($id) {
            $sql = "SELECT id, ranking, best_score FROM ".$this->players_tbname." 
            WHERE id = $id ORDER BY best_score ASC";  
            
            $req = $this->conn->prepare($sql);
            $req->execute();

            return $req->fetchObject()->ranking;
        }


        /* -------------------- Setters Methods -------------------- */
        public function set_rank($id, $new_rank) {
            $sql = "UPDATE ".$this->players_tbname." SET ranking = ? WHERE id = ?";
            $req = $this->conn->prepare($sql);
            $req->bindParam(1, $new_rank);
            $req->bindParam(2, $id);
            $req->execute();
        }



        /* -------------------- Others Methods --------------------- */
        public function process_rank($id, $new_rank) {
            $this->set_rank(id :$id, new_rank: $new_rank);
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
    }
