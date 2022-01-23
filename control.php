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
        /*$rankList = getRank();
        print_r($rankList);
        echo count($rankList);
        print_r($rankList[0]);
        for ($i = 0; $i < count($rankList); $i++) {
            print($rankList[$i]['money']);
        }
        break;*/
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
        addRoom($_SESSION['userName'], $answer);
        header("Location: draw.html");
        break;
    case "getBanker":  // 得到莊家資訊
        $differ = getBanker($_SESSION['userName']);
        echo json_encode($differ);
        break;
    case "getPlayer":  // 
        $player = getPlayer($_SESSION['userName']);
        echo json_encode($player);
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
        $betMoney = $_POST['betMoney'];
        $rid = (int)$_POST['rid'];
        $list = getUser($_SESSION['userName']);
        if ($betMoney != "") {
            if ($list['money'] >= (int)$betMoney) {
                    addPlayer($_SESSION['userName'], (int)$betNum, $betMoney, $rid);
                    header("Location: wait.html?rid=" . (string)$rid);
                    break;
            } else {
                echo "<script type='text/javascript'>alert('餘額不足');</script>";
                echo "<script>window.location.href='player.html?rid=" . (string)$rid . "';</script>";
                break;
            }
        } else {
            echo "<script type='text/javascript'>alert('請填寫下注金額');</script>";
            echo "<script>window.location.href='player.html?rid=" . (string)$rid . "';</script>";
        }
    case "getRoomPlayer":  // 得到在那間房間有下注的玩家資訊
        $list = getRoomPlayer($_SESSION['userName']);
        echo json_encode($list);
        break;
    case "draw":  // 開獎
        $answer = getAnswer($_SESSION['userName']);
        $numList = getRoomPlayer($_SESSION['userName']);
        for ($i = 0; $i < count($numList); $i++) {
            if ($answer['answer'] == $numList[$i]['betNum']) {
                bankerLose($numList[$i]['betMoney'], $_SESSION['userName'], $numList[$i]['userName']);
            } else {
                bankerWin($numList[$i]['betMoney'], $_SESSION['userName'], $numList[$i]['userName']);
            }
        }
        echo "OK";
        break;
    default;
}
?>