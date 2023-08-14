<?php
session_start();
require_once('../config/connect.php');
echo file_get_contents('../html/header.html');
if (isset($_SESSION['userid'])) {

    $userid = $_SESSION['userid'];
} else {
    header("Location:bejelent.php");
}
$szobaid = intval($_GET['asd']);
$result = $conn->query("SELECT szoba.szobaNev, szoba.terulet, eszkozok.eszkoznev, futes.futesTipus FROM szoba INNER JOIN eszkozok ON eszkozok.szobaID=szoba.id INNER JOIN futes ON futes.szobaID=szoba.id WHERE szoba.id='$szobaid'");

$row = $result->fetch_array();

?>
<div class="row justify-content-center align-items-center">
    <div class="form-group col-sm-2 shadow p-3 mb-5 bg-body rounded h-100">
        <form method="post">
            <label for="szobanev">Szoba Neve: </label>
            <input type="text" class="form-control" name="szobanev" id="" placeholder="Szoba Neve" value="<?php echo $row[0]; ?>"><br>
            <label for="terulet">Terület: </label>
            <input type="number" class="form-control" name="terulet" id="" placeholder="Terület" value="<?php echo $row[1]; ?>"><br>
            <label for="eszkoznev">Eszköznév: </label>
            <input type="text" class="form-control" name="eszkoznev" id="" placeholder="Eszköz név" value="<?php echo $row[2]; ?>"><br>
            <label for="futestipus">Fűtés Típus: </label>
            <input type="text" class="form-control" name="futestipus" id="" placeholder="Fűtés Típus" value="<?php echo $row[3]; ?>"><br>
            <input type="submit" class="btn btn-dark mt-4" value="Küldés" name="update">
        </form>
    </div>
</div>

<?php
if(isset($_POST['update'])){
    $szobanev = $_POST['szobanev'];
    $terulet = intval($_POST['terulet']);
    $eszkoznev = $_POST['eszkoznev'];
    $futestipus = $_POST['futestipus'];

    if($szobanev !== ""){
        $sql = "UPDATE szoba SET szobaNev='".$szobanev."', terulet=$terulet WHERE id=$szobaid; ";
        mysqli_query($conn, $sql);
    }if($eszkoznev !== ""){
        $sql = "UPDATE eszkozok SET eszkozNev='".$eszkoznev."' WHERE eszkozok.szobaID=$szobaid; ";
        mysqli_query($conn, $sql);
    }if($futestipus !== ""){
        $sql = "UPDATE futes SET futesTipus='".$futestipus."' WHERE futes.szobaID=$szobaid; ";
        mysqli_query($conn, $sql);
    }
    header("Location:reszletesebb.php");
}

$szobatolor = $_GET['torol'];
if (isset($_GET['torol']) && $szobatolor > 0) {
    $szobasql = "DELETE FROM `szoba` WHERE id=".$szobatolor;
    if($conn->query($szobasql) === TRUE){
        echo "sikerült";
        header("Location:reszletesebb.php");
    }else{
        echo "nem sikerült";
    }
    header("Location:reszletesebb.php");

}

$felhdelete = $_GET['felhdelete'];
if(isset($_GET['felhdelete']) && $felhdelete > 0){
    $sql = "DELETE FROM tartozik WHERE felh_id=".$felhdelete;
    if($conn->query($sql) === TRUE){
        echo "sikerült";
        header("Location:reszletesebb.php");
    }else{
        echo "nem sikerült";
    }
}