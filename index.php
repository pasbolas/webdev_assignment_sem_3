<?php

    // we are starting a session here for the php to store session vars
    session_start();

    // we are checking if the session have a username variable set, if it exists, redirect to the search page
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
