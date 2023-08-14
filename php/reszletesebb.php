<?php
session_start();
require_once('../config/connect.php');
echo file_get_contents('../html/header.html');
echo file_get_contents('../html/logout.html');

if (isset($_SESSION['userid'])) {

    $userid = $_SESSION['userid'];
} else {
    header("Location:bejelent.php");
}
?>
<div class="container mt-5 d-flex justify-content-around flex-wrap">
    <div class="p-1 w-50">
        <table class="fixed_header table-hover mt-3 w-100">
            <thead>
                <tr>
                    <th>Szoba Neve</th>
                    <th>Terület</th>
                    <th>Eszköz</th>
                    <th>Fűtés Típus</th>
                    <th>Módosítás</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $szobak = "";
                $result1 = $conn->query("SELECT szoba.szobaNev, szoba.terulet, eszkozok.eszkozNev, futes.futesTipus, szoba.id FROM szoba INNER JOIN lakas ON szoba.lakas_id=lakas.id INNER JOIN tartozik ON lakas.id=tartozik.lakas_id INNER JOIN eszkozok ON eszkozok.szobaID=szoba.id INNER JOIN futes ON futes.szobaID=szoba.id WHERE tartozik.felh_id=$userid");
                while ($row = $result1->fetch_array()) {
                    $szobak .= '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td><a href="szerkeszt.php?asd=' . $row[4] . '&torol=-1&felhdelete=-1">Szerkesztés</a><br><a href="szerkeszt.php?asd=-1&torol=' . $row[4] . '&felhdelete=-1">Törlés</a></td></tr>';
                }
                echo $szobak;
                ?>
            </tbody>
        </table>
    </div>
    <div class="p-1 w-50">
        <table class="fixed_header table-hover mt-3 w-100">
            <thead>
                <tr>
                    <th>Szoba</th>
                    <th>Hőmérséklet</th>
                    <th>Páratartalom</th>
                    <th>Időpont</th>
                    <th>Módosítás</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Adatok listázása
                $result2 = $conn->query("SELECT szoba.szobaNev, adatok.homerseklet, adatok.paratartalom, adatok.datum, adatok.id FROM adatok 
                                    INNER JOIN szoba ON adatok.szobaId=szoba.id 
                                    INNER JOIN lakas ON szoba.lakas_id=lakas.id 
                                    INNER JOIN tartozik ON lakas.id=tartozik.lakas_id 
                                    WHERE tartozik.felh_id=" . $userid);
                //TODO táblázat elkészítése
                $adatok = "";
                while ($row = $result2->fetch_array()) {
                    $adatok .= '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td>
                <td><a href="adatszerkeszt.php?adatszerk=' . $row[4] . '&adattorol=-1&lakastorol=-1">Szerkesztés</a><br><a href="adatszerkeszt.php?adattorol=' . $row[4] . '&adatszerk=-1&lakastorol=-1">Törlés</a></td></tr>';
                }
                echo $adatok;
                ?>
            </tbody>
        </table>
    </div>
    <div class="p-1 w-50">
        <?php
        //Lakások listázása
        $result = $conn->query("SELECT lakas.lakasnev, lakas.id FROM lakas INNER JOIN tartozik ON lakas.id=tartozik.lakas_id WHERE tartozik.felh_id=" . $userid . " GROUP BY lakas.lakasnev;");
        //TODO táblázat elkészítése
        $lakas = "";
        $lakas .= '<p class="mt-3">Saját lakások</p>';
        $lakas .= "<ul>";
        while ($row = $result->fetch_array()) {
            $lakas .= '<li>' . $row[0] . '</li>';
        }
        $lakas .= "</ul>";
        echo $lakas;
        ?>
    </div>
    <div class="p-1 w-50">
        <table class="fixed_header table-hover mt-3  w-100">
            <thead>
                <tr>
                    <th>Felhasználónév</th>
                    <th>Email cím</th>
                    <th>Lakás neve</th>
                    <th>Eltávoláts a lakásból</th>
                </tr>
                <thead>
                <tbody>
                    <?php
                    $html = "";
                    if (isset($_SESSION['userid'])) {
                        $lakasid = $_SESSION['lakasid'];
                        $userid = $_SESSION['userid'];
                        $result = $conn->query("SELECT users.felh, users.emial, lakas.lakasnev, users.id FROM users 
                                                INNER JOIN tartozik ON users.id=tartozik.felh_id 
                                                INNER JOIN lakas ON tartozik.lakas_id=lakas.id 
                                                WHERE tartozik.lakas_id=(SELECT tartozik.lakas_id FROM tartozik WHERE tartozik.felh_id=" . $userid . " AND tartozik.lakas_id=" . $lakasid . ") 
                                                AND tartozik.felh_id<>" . $userid);

                        while ($row = $result->fetch_array()) {
                            $html .= '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td><a href="szerkeszt.php?asd=-1&torol=-1&felhdelete=' . $row[3] . '">Eltávolítás</a></td></tr>';
                        }
                        $html .= '';
                    }
                    echo $html;
                    ?>

                </tbody>
        </table>
    </div>
</div>