//<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

/*$(document).ready(function () {

    if (location.href.indexOf("monitor2") > -1) {
        todolist();
        $(document).on('change', '.done', function () {
            let id1 = $(this).val();
            be(id1);
        });
    }
    if (location.href.indexOf("monitor2") > -1) {
        todolist();
        $(document).off('change', '.done', function () {
            let id2 = $(this).val();
            ki(id2);
        });
    }
});*/
function todolist() {
    $.get("monitor2.php", function (adat) { $(".monitor").html(adat) });//monitor2.php
}
/*function be(id1) {
    console.log(id1);
    console.log("1");
    $.post("be.php", { id1 }, function (data, status) {
        todolist();
    });
}
function ki(id2) {
    console.log(id2);
    console.log("0");
    $.post("ki.php", { id2 }, function (data, status) {
        todolist();
    });
}*/

function deleteSzoba(szobaid) {
    console.log(szobaid);
    xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.getElementById("sikeres").innerHTML = this.responseText;
    }
    xhttp.open("POST", "C:/xampp/htdocs/weblap/okosotthon/php/szerkesztes.php?szid="+szobaid);
}

