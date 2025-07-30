<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    if ($action === 'create') {
        $news = $_POST['news'];
        
        $stmt = $pdo_conn->prepare("INSERT INTO latest_news (news) VALUES (?)");
        $stmt->execute([$news]);
        
        header("Location: latest_news.php?success=created");
        exit;
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $news = $_POST['news'];
        
        $stmt = $pdo_conn->prepare("UPDATE latest_news SET news = ? WHERE id = ?");
        $stmt->execute([$news, $id]);
        
        header("Location: latest_news.php?success=updated");
        exit;
    }
}

header("Location: latest_news.php");
exit;
?>