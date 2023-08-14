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


<div class="d-flex flex-row justify-content-around mt-5 ml-auto">
    <div class="p-3 shadow mb-5 rounded">
        <form method="post">
            <p>Felhasználói Adatok Módosítása</p>
            <input type="password" class="form-control" name="oldpwd" placeholder="Régi jelszó" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$"><br>
            <input type="password" class="form-control" name="newpwd1" placeholder="Új jelszó" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$"><br>
            <input type="password" class="form-control" name="newpwd2" placeholder="Új jelszó megerősítése" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$"><br>
            <input type="email" class="form-control" name="emailcim" placeholder="Email cím megváltoztatása"><br>
            <input type="text" class="form-control" name="lakasnev" placeholder="Lakásnév megváltoztatása">
            <input type="submit" class="btn btn-dark mt-4" value="Megváltoztatás" name="valtoztat">
        </form>
    </div>
    <div class="p-3 shadow mb-5 rounded">
        <form method="post">
            <p>Új szoba felvétele</p>
            <input type="text" class="form-control" name="szobanev" value="" placeholder="Szoba neve" id="" required><br>
            <input type="text" class="form-control" name="terulet" value="" placeholder="Területe" id="" required><br>
            <input type="text" class="form-control" name="eszkoz" value="" placeholder="Eszköz neve" id="" required><br>
            <input type="text" class="form-control" name="futes" value="" placeholder="Fűtés Típus" id="" required>
            <input type="submit" class="btn btn-dark mt-4" value="Szoba hozzáadása" name="hozzaad">
        </form>
    </div >
    <div class="p-3 shadow mb-5 rounded">
        <form method="post">
            <p>Felhasználó hozzáadása a alkáshoz</p>
            <input type="text" class="form-control " name="felhneve" placeholder="Felhasználónév" required><br>
            <input class="form-control" type="text" name="emailcime" placeholder="Email cím" required><br>
            <input class="btn btn-dark" type="submit" name="lakashozadd" value="Lakáshoz add">
        </form><br>
        <form method="post">
            <p>Új lakás felvétele</p>
            <input type="text" class="form-control " name="lakasnev" placeholder="Lakás neve" required><br>
            <input class="btn btn-dark" type="submit" name="ujlakas" value="Új lakás hozzáadása">
        </form>
    </div>
</div>
<?php
$lakasid = $_SESSION['lakasid'];
$actuser = $_SESSION['felhnev'];
$actuserid = $_SESSION['userid'];
if(isset($_POST['ujlakas'])){
    $ujlakas = $_POST['lakasnev'];
    $sql = "INSERT INTO lakas (lakasnev) VALUES('$ujlakas'); ";
    if ($conn->query($sql) === TRUE) {
        echo '<p class="text-center">Új lakás felvéve</p>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $sql = "INSERT INTO tartozik (felh_id,lakas_id) VALUES ($actuserid,(SELECT id FROM lakas WHERE lakasnev LIKE '$ujlakas'));";
    if ($conn->query($sql) === TRUE) {
        echo '<p class="text-center">Kapcsolat sikeresek kialakítva</p>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['lakashozadd'])) {
    $felh = $_POST['felhneve'];
    $email = $_POST['emailcime'];
    $sql = "INSERT INTO tartozik (felh_id,lakas_id) VALUES ((SELECT id FROM users WHERE felh LIKE '$felh'),$lakasid)";
    if ($conn->query($sql) === TRUE) {
        echo '<p class="text-center">Felhasználó hozzáadva a alkáshoz</p>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<?php
if (isset($_POST['valtoztat'])) {
    if (isset($_POST['oldpwd']) && isset($_POST['newpwd1']) && isset($_POST['newpwd2'])) {
        $regi = $_POST['oldpwd'];
        $uj1 = $_POST['newpwd1'];
        $uj2 = $_POST['newpwd2'];
        if ($regi !== "" && $uj1 !== "" && $uj2 !== "") {
            $result = $conn->query("SELECT users.jelszo FROM users WHERE users.id=" . $userid);
            $row = $result->fetch_array();
            $hash = strtoupper(hash('sha512', $regi));
            if ($row[0] == $hash) {
                if ($uj1 == $uj2) {
                    $hash = strtoupper(hash('sha512', $uj1));
                    mysqli_query($conn, "UPDATE users SET jelszo='" . $hash . "' WHERE id=" . $userid);
                    echo '<p class="text-center">Jelszó sikeresen megváltoztatta</p>';
                } else {
                    echo '<p class="text-center">uj jelszók nem egyeznek</p>';
                }
            } else {
                echo '<p>A régi jelszó nem egyezik</p>';
            }
        }
    }
    if (isset($_POST['lakasnev'])) {
        $lakasnev = $_POST['lakasnev'];
        if ($lakasnev !== "") {
            mysqli_query($conn, "UPDATE lakas SET lakasnev='" . $lakasnev . "' WHERE id=(SELECT tartozik.lakas_id FROM lakas INNER JOIN tartozik ON tartozik.felh_id=lakas.id WHERE tartozik.felh_id=" . $userid . ")");
            echo '<p class="text-center">Sikeresen megváltoztatta a lakás nevét</p>';
        }
    }
    if (isset($_POST['emailcim'])) {
        $emailcim = $_POST['emailcim'];
        if ($emailcim !== "") {
            mysqli_query($conn, "UPDATE users SET emial='$emailcim' WHERE id=$userid");
            echo '<p class="text-center">Sikeresen megváltoztatta az email címét</p>';
        }
    }
}

if (isset($_POST['hozzaad'])) {
    $szobanev = $_POST['szobanev'];
    $terulet = $_POST['terulet'];
    $eszkoz = $_POST['eszkoz'];
    $futes = $_POST['futes'];
    if ($szobanev !== "" && $terulet !== "" && $eszkoz !== "" && $futes !== "") {
        $sql = "INSERT INTO szoba (lakas_id,szobaNev, terulet) VALUES($lakasid,'$szobanev',$terulet); ";
        $conn->query($sql);
        $sql = "INSERT INTO eszkozok (szobaID, eszkozNev) VALUES((SELECT id FROM szoba WHERE szobaNev LIKE '$szobanev' AND lakas_id=$lakasid),'$eszkoz'); ";
        $conn->query($sql);
        $sql = "INSERT INTO futes (szobaID, futesTipus, fokozat, bekapcsolva) VALUES((SELECT id FROM szoba WHERE szobaNev LIKE '$szobanev' AND lakas_id=$lakasid),'$futes',1,0); ";
        $conn->query($sql);
        $sql = "INSERT INTO adatok (szobaId,homerseklet,paratartalom) VALUES((SELECT id FROM szoba WHERE szobaNev LIKE '$szobanev' AND lakas_id=$lakasid),25,40)";
        $conn->query($sql);
    }
}
?>