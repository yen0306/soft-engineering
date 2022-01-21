<?php
session_start();
require("model.php");

if (isset($_REQUEST['act'])) {
	$act=$_REQUEST['act'];
} else $act='';

switch ($act) {
    case "addUser":  // 新增使用者資訊
        $userName = $_POST['userName'];
        $pwd = $_POST['pwd'];
        if ($userName!="" && $pwd!="") {
            addUser($userName, $pwd);
            echo "<script>alert('註冊成功');</script>";
            echo "<script>window.location.href='loginUI.html';</script>";
            break;
        } else {
            echo "<script type='text/javascript'>alert('註冊失敗，請再註冊一次');</script>";
            echo "<script>window.location.href='registerUI.html';</script>";
            break;
        }
    case "login":  // 登入
        $userName = $_POST['userName'];
        $pwd = $_POST['pwd'];
        $list = getUser($userName);
        $_SESSION['userName'] = $list['userName'];
        if ($list['pwd'] == $pwd) {
            header("Location: selectUI.html");
            break;
        } else {
            echo "<script type='text/javascript'>alert('帳號或密碼錯誤，請再嘗試一次');</script>";
            echo "<script>window.location.href='loginUI.html';</script>";
            break;
        }
    case "getUser":  // 得到使用者資訊
        $list = getUser($_SESSION['userName']);
        echo json_encode($list);
        break;
    case "getRank":  // 得到排行榜
        $rankList = getRank();
        echo json_encode($rankList);
        break;
    case "addRoom":  // 新增房間資訊
        $answer = (int)$_POST['answer'];
        addRoom($_SESSION['userName'], $answer, $type);
        header("Location: draw.html");
        break;
    case "getAnswer":  // 得到莊家設定的數字
        $answer = getAnswer($_SESSION['userName']);
        echo json_encode($answer);
        break;
    case "getRoom":  // 得到房間資訊
        $room = getRoom();
        echo json_encode($room);
        break;
    case "addPlayer":  // 新增玩家資訊
        $betNum = (int)$_POST['betNum'];
        $betMoney = (int)$_POST['betMoney'];
        $rid = (int)$_POST['rid'];
        $list = getUser($_SESSION['userName']);
        if ($list['money'] >= $betMoney) {
            addPlayer($_SESSION['userName'], $betNum, $betMoney, $rid);
            header("Location: wait.html?rid=" . (string)$rid);
            break;
        } else {
            echo "<script type='text/javascript'>alert('餘額不足');</script>";
            echo "<script>window.location.href='player.html?rid=" . (string)$rid . "';</script>";
            break;
        }
    case "getPlayer":  // 得到在那間房間有下注的玩家資訊
        $list = getPlayer($_SESSION['userName']);
        echo json_encode($list);
        break;
    default;
}
?>