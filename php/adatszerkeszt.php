<?php
session_start();
require_once('../config/connect.php');
echo file_get_contents('../html/header.html');
if (isset($_SESSION['userid'])) {

    $userid = $_SESSION['userid'];
} else {
    header("Location:bejelent.php");
}
$adatid = $_GET['adatszerk'];
if (isset($_GET['adatszerk']) && $adatid > 0) {
    $result = $conn->query("SELECT adatok.homerseklet, adatok.paratartalom, adatok.datum FROM adatok WHERE adatok.id=$adatid");

    $row = $result->fetch_array();
}
?>
<div class="row justify-content-center align-items-center">
    <div class="form-group col-sm-2 shadow p-3 mb-5 bg-body rounded h-100">
        <form method="post">
            <label for="homerseklet">Hőmérséklet: </label>
            <input type="number" class="form-control" name="homerseklet" id="" placeholder="Hőmérséklet" value="<?php echo $row[0]; ?>" required><br>
            <label for="paratartalom">Páratartalom: </label>
            <input type="number" class="form-control" name="paratartalom" id="" placeholder="Páratartalom" value="<?php echo $row[1]; ?>" required><br>
            <label for="datum">Dátum: </label>
            <input type="datetime-local" class="form-control" name="datum" id="" value="<?php echo $row[2]; ?>" required><br>
            <input type="submit" class="btn btn-dark mt-4" value="Küldés" name="updateadat">
        </form>
    </div>
</div>
<?php
if (isset($_POST['updateadat'])) {
    $homersek = $_POST['homerseklet'];
    $paratartalom = $_POST['paratartalom'];
    $datum = $_POST['datum'];
    $darab = explode('T', $datum);
    $datum = $darab[0] . " " . $darab[1];

    if ($homersek !== "" && $paratartalom !== "" && $datum !== "") {
        $sql = "UPDATE adatok SET homerseklet=$homersek,paratartalom=$paratartalom,datum='$datum' WHERE id=$adatid";
        mysqli_query($conn, $sql);
    }
    header("Location:reszletesebb.php");
}
$adattorol = $_GET['adattorol'];
if (isset($_GET['adattorol']) && $adattorol > 0) {
    $sql = "DELETE FROM adatok WHERE id=$adattorol";
    if ($conn->query($sql) === TRUE) {
        echo "sikerült";
        header("Location:reszletesebb.php");
    } else {
        echo "nem sikerült";
    }
}

//HIBÁS nem tudja törölni az adatokat
/*$lakastorol = $_GET['lakastorol'];
if(isset($_GET['lakastorol']) && $lakastorol > 0){
    $sql = "DELETE FROM lakas WHERE id=$lakastorol";
    if ($conn->query($sql) === TRUE) {
        echo "sikerült";
        $sql = "DELETE FROM tartozik WHERE lakas_id=$lakastorol";
        if ($conn->query($sql) === TRUE) {
            echo "sikerült";
            header("Location:reszletesebb.php");
        } else {
            echo "nem sikerült";
        }
    } else {
        echo "nem sikerült";
    }
}*/
