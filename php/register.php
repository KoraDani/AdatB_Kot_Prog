<?php
session_start();
require_once("../config/connect.php");
echo file_get_contents('../html/header.html');
echo file_get_contents('../html/login.html');
?>
<div class="row h-100 justify-content-center align-items-center m-0" id="reg">
    <div class="form-group col-sm-2 shadow p-3 mb-5 bg-body rounded h-100">
        <form method="post" id="regisztracio">
            <label>Felhasználó név</label>
            <input type="text" class="form-control" placeholder="Felhasználónév" name="user" required>
            <label for="exampleInputEmail1">Email cím</label>
            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Email cím"
                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" required>
            <label for="exampleInputPassword1">Jelszó</label>
            <input type="password" class="form-control" placeholder="Jelszó" name="pwd1"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" required>
            <label for="exampleInputPassword1">Jelszó megerősítése</label>
            <input type="password" class="form-control" placeholder="Jelszó megerősítése" name="pwd2"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" required>
            <label for="exampleInputPassword1">Lakásnév megadása</label>
            <input type="text" class="form-control" placeholder="Lakás neve" name="lakasnev" required>
            <button type="submit" class="btn btn-dark mt-4" name="regiszt">Regisztráció</button>
        </form>
    </div>
</div>
<?php
function encrypt($text)
{
    $hashedPwd = hash('sha512', $text);
    return strtoupper($hashedPwd);
}
if (isset($_POST['regiszt'])) {
    $name = $_POST['user'];
    $email = $_POST['email'];
    $lakasnev = $_POST['lakasnev'];
    $result = $conn->query("SELECT DISTINCT felh FROM users WHERE felh ='$name'");
    //echo $result;
    $szam = $result->num_rows;
    if ($_POST['user'] == "" || $_POST['pwd1'] == "" || $_POST['pwd2'] == "") {
        echo '<p>Nem töltött ki valamelyik mezőt</p>';
    } else if (!$szam == 1) {
        if ($_POST['pwd1'] == $_POST['pwd2']) {
            $pwd = $_POST['pwd1'];
            $pattern = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
            if (preg_match($pattern, $pwd)) {
                $pwdHash = encrypt($pwd);
                $_SESSION['felhnev'] = $name;
                mysqli_query($conn, "INSERT INTO users (felh, jelszo, emial) VALUES ('$name','$pwdHash','$email')");
                mysqli_query($conn, "INSERT INTO lakas (lakasnev) VALUES('$lakasnev')");
                mysqli_query($conn, "INSERT INTO tartozik (felh_id,lakas_id) VALUES((SELECT id FROM users WHERE felh LIKE '$name'),(SELECT id FROM lakas WHERE lakasnev LIKE '$lakasnev'))");
                echo '<p class="text-center">Sikeresen Regisztráció</p></br><p class="text-center">Kérem jelentkezzen be/</p>';
            }
        } else {
            echo '<p class="text-center">Nem egyezik a jelszó</p>';
        }
    } else {
        echo '<p class="text-center">A felhasználó név már létezik</p>';
    }
}