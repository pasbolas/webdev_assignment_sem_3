<?php

    // we are starting a session here for the php to store session vars
    session_start();

    if (!empty($_SESSION['username'])) 
        
    {
        header('Location: ./books/search.php');
        exit;
    } 
    
    else 
    
    {
        header('Location: ./auth/login.php');
        exit;
    }
?>
