<?php
require("dbconfig.php");

function addUser($userName, $pwd) {  // 新增使用者資訊
    global $db;
    $money = 1000;
    $sql = "insert into user (userName, pwd, money) values (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $userName, $pwd, $money);
    mysqli_stmt_execute($stmt);
    return true;
}
function getUser($userName) {  // 得到使用者資訊
    global $db;
    $sql = "select * from user where userName=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function getRank() {  // 得到排行榜
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
function addRoom($userName, $answer) {  // 新稱房間使用者
    global $db;
    $sql = "insert into room (userName, answer) values(?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "si", $userName, $answer);
    mysqli_stmt_execute($stmt);
    return true;
}
function getAnswer($userName) {  // 得到莊家設定的數字
    global $db;
    $sql = "select * from room where userName = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function getRoom() {  // 得到房間及莊家資訊
    global $db;
    $sql = "select * from room where status = 0";
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
function getPlayer($userName) {
    global $db;
    $sql = "select * from player where userName = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function getBanker($userName) {  // 得到贏或輸多少錢
    global $db;
    $sql = "select * from room where userName = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);
    return $rs;
}
function addPlayer($userName, $betNum, $betMoney, $rid) {  // 新增玩家資訊
    global $db;
    $sql = "insert into player (userName, rid, betNum, betMoney) values (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $userName, $rid, $betNum, $betMoney);
    mysqli_stmt_execute($stmt);
    return true;
}
function getRoomPlayer($userName) {  // 得到在那間房間有下注的玩家資訊
    global $db;
    $sql = "select * from player where rid = (select rid from room where userName = ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $retArr=array();
    while ($rs = mysqli_fetch_assoc($result)) {
        $tArr=array();
        $tArr['userName']=$rs['userName'];
        $tArr['betMoney']=$rs['betMoney'];
        $tArr['betNum'] = $rs['betNum'];
        $retArr[] = $tArr;
    }
    return $retArr;
}
function bankerWin($betMoney, $bankerName, $playerName) {
    global $db;
    $sql = "select * from user where userName = ?";  // 得到莊家錢包餘額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $bankerName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);

    $sql = "update user set money = ?+? where userName = ?";  // 莊家加錢
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $rs['money'], $betMoney, $bankerName);
    mysqli_stmt_execute($stmt);

    $sql = "update room set differ = differ+? where userName = ?";  // 更新莊家差額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "is", $betMoney, $bankerName);
    mysqli_stmt_execute($stmt);

    $sql = "select * from user where userName = ?";  // 得到玩家錢包餘額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $playerName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);

    $sql = "update user set money = ?-? where userName = ?";  // 玩家扣錢
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $rs['money'], $betMoney, $playerName);
    mysqli_stmt_execute($stmt);

    $sql = "update player set differ = differ-? where userName = ?";  // 更新玩家差額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "is", $betMoney, $playerName);
    mysqli_stmt_execute($stmt);

    $sql = "update room set status = 1";  // 房間不見
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);

    return true;
}
function bankerLose($betMoney, $bankerName, $playerName) {
    global $db;
    $sql = "select * from user where userName = ?";  // 得到莊家錢包餘額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $bankerName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);

    $sql = "update user set money = ?-(5*?) where userName = ?";  // 莊家扣錢
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $rs['money'], $betMoney, $bankerName);
    mysqli_stmt_execute($stmt);

    $sql = "update room set differ = differ-(5*?) where userName = ?";  // 更新莊家差額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "is", $betMoney, $bankerName);
    mysqli_stmt_execute($stmt);

    $sql = "select * from user where userName = ?";  // 得到玩家錢包餘額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $playerName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rs = mysqli_fetch_assoc($result);

    $sql = "update user set money = ?+(5*?) where userName = ?";  // 玩家加錢
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $rs['money'], $betMoney, $playerName);
    mysqli_stmt_execute($stmt);

    $sql = "update player set differ = differ+(5*?) where userName = ?";  // 更新玩家差額
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "is", $betMoney, $playerName);
    mysqli_stmt_execute($stmt);

    $sql = "update room set status = 1";  // 房間不見
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);

    return true;
}
?>