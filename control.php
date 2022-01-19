<?php
require("model.php");

if (isset($_REQUEST['act'])) {
	$act=$_REQUEST['act'];
} else $act='';

switch ($act) {
    case "addUser":
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
    case "login":
        $userName = $_POST['userName'];
        $pwd = $_POST['pwd'];
        if (getUser($userName) == $pwd) {
            header("Location: selectUI.html");
            break;
        } else {
            echo "<script type='text/javascript'>alert('帳號或密碼錯誤，請再嘗試一次');</script>";
            echo "<script>window.location.href='loginUI.html';</script>";
            break;
        }
    default;
}
?>