<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();
$loggedIn = isset($_SESSION['user']);

if (!$loggedIn) {
    header("Location:login.php");
    exit();
}

?>
<div class="top">
    <div class="navbar">
        <a href="#" id="logo"><img src="Logo/logo.png"></a>
        <div class="content">
            <div class="items">
                <a href="index.php">Video Yükleyin</a>
            </div>
            <div   class="items" >
                <a href="logout.php">Çıkış Yap</a>
            </div>
        </div>
        <button id="icon" onclick="toggleMenu()"><img src="Logo/icon.png"></button>
    </div>
</div>
<div id="myDropDown" class="menu">
    <a href="index.php" id="screen">Video Yükleyin</a>
    <a href="logout.php" id="screen">Çıkış</a>
</div>

