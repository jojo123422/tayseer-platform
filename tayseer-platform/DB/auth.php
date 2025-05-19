<?php
session_start();

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['type'] === 'admin';
}

function checkAdminAccess() {
    if (!isAdmin()) {
        header("Location: ../index.php");
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}
?>