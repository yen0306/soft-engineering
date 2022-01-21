<?php
require("dbconfig.php");

function addUser($userName, $pwd) {
    global $db;
    $money = 1000;
    $sql = "insert into user (userName, pwd, money) values (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $userName, $pwd, $money);
    mysqli_stmt_execute($stmt);
    return true;
}
function getUser($userName) {
    global $db;
    $sql = "select * from user where userName=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function getRank() {
    global $db;
    $sql = "select * from user order by money desc";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $retArr=array();
    while ($rs = mysqli_fetch_assoc($result)) {
        $tArr=array();
        $tArr['userName']=$rs['userName'];
        $tArr['money']=$rs['money'];
        $retArr[] = $tArr;
    }
    return $retArr;
}
function addRoom($userName, $answer, $type=0) {
    global $db;
    $sql = "insert into room (userName, answer) values(?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "si", $userName, $answer);
    mysqli_stmt_execute($stmt);
    return true;
}
function getAnswer($userName) {
    global $db;
    $sql = "select * from room where userName = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function getRoom() {
    global $db;
    $sql = "select * from room";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $retArr=array();
    while ($rs = mysqli_fetch_assoc($result)) {
        $tArr=array();
        $tArr['rid']=$rs['rid'];
        $tArr['userName']=$rs['userName'];
        $retArr[] = $tArr;
    }
    return $retArr;
}
?>