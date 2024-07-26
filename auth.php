<?php
session_start();

function check_login()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

function get_logged_in_user()
{
    return isset($_SESSION['user_id']) ? $_SESSION['username'] : null;
}
