<?php
require("dbconfig.php");

function addUser($userName, $pwd) {
    global $db;
    $sql = "insert into user (userName, pwd) values (?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $userName, $pwd);
    mysqli_stmt_execute($stmt);

    $sql = "select id from user order by id desc limit 1";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);

    $money = 1000;
    $sql = "insert into money (id, money) values (?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $rs['id'], $money);
    mysqli_stmt_execute($stmt);
    return true;
}
function getUser($userName) {
    global $db;
    $sql = "select pwd from user where userName=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs['pwd'];
}
?>