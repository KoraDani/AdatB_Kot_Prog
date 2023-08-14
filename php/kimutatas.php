<?php
session_start();
require_once('../config/connect.php');
echo file_get_contents('../html/header.html');
include('../config/session.php');
if (isset($_SESSION['userid'])) {

    $userid = $_SESSION['userid'];
} else {
    header("Location:bejelent.php");
}
$homerseklet = null;
$paratartalom = null;
$levegomin = null;
$szobaid = null;
$lakasid = $_SESSION['lakasid'];
$result1 = $conn->query("SELECT DISTINCT szoba.id, szoba.szobaNev FROM szoba WHERE szoba.lakas_id=$lakasid");
$row = $result1->fetch_array();
if (isset($row)) {
	$result2 = $conn->query("SELECT DISTINCT szoba.id, szoba.szobaNev FROM szoba WHERE szoba.lakas_id=$lakasid");

	echo '<form method="post" class=" d-flex  flex-wrap flex-coloum justify-content-center">';
	while ($row1 = $result2->fetch_array()) {
		echo '<button type="submit" class="btn btn-info mt-3 m-2" name="szobak" value=' . $row1[0] . '>' . $row1[1] . '</button>';
	}
	echo '</form>';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$szobaid = $_POST['szobak'];
	} else {

		$szobaid = $row[0];
	}

	$result = $conn->query("SELECT id,homerseklet,paratartalom FROM adatok WHERE szobaId='$szobaid' ORDER BY id DESC LIMIT 10");
	if ($result) {
		$row1 = $result->fetch_array();

		$homerseklet = trim($row1['homerseklet'] . ",");
		$paratartalom = trim($row1['paratartalom'] . ",");
		//$levegomin = trim($row1['levegoMin'] . ",");

		$idk = array();
		$homerseklet1 = array();
		while ($row = $result->fetch_array()) {
			array_push($idk, $row['id']);
			array_push($homerseklet1, $row['homerseklet']);
			$homerseklet = $homerseklet . '"' . $row['homerseklet'] . '",';
			$paratartalom = $paratartalom . '"' . $row['paratartalom'] . '",';
			//$levegomin = $levegomin . '"' . $row['levegoMin'] . '",';
		}
	}
} else {
	echo '<p class="text-center m-2">Még nem tartozik adat a szobához</p>';
}
/*echo $homerseklet;
print_r($homerseklet1);
$homerseklet = trim($homerseklet, ",");// '1','2',"3","4","5","6","7","8","9","10"
$paratartalom = trim($paratartalom, ",");
$levegomin = trim($levegomin, ",");<?php echo $homerseklet; ?> <?php echo $levegomin; ?>
echo $homerseklet;
<script src="http://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
*/
?>


<div id="container">
	<canvas id="chart" style="width: 100%; height: 60vh; background: #222; border: 1px solid #555652; margin-top: 10px;"></canvas>
</div>
<script>
	var ctx = document.getElementById("chart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
			datasets: [{
					label: "Hőmérséklet",
					data: [<?php echo $homerseklet; ?>],
					backgroundColor: 'transparent',
					borderColor: 'rgba(255,99,132)',
					borderWidth: 3
				},
				{
					label: 'Paratartalom',
					data: [<?php echo $paratartalom; ?>],
					backgroundColor: 'transparent',
					borderColor: 'rgba(255,99,100)',
					borderWidth: 3
				},
				/*{
					label: 'Levegő Minőség',
					data: [<?php echo $levegomin; ?>],
					backgroundColor: 'transparent',
					borderColor: 'rgba(255,99,50)',
					borderWidth: 3
				}*/
			]
		},
		options: {
			scales: {
				scales: {
					yAxes: [{
						beginAtZero: false
					}],
					xAxes: [{
						autoskip: true,
						maxTicketsLimit: 20
					}]
				}
			},
			tooltips: {
				mode: 'index'
			},
			legend: {
				display: true,
				position: 'top',
				labels: {
					fontColor: 'rgb(255,255,255)',
					fontSize: 16
				}
			}
		}
	});
</script>