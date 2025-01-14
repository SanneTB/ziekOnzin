<?php
    session_start();
    session_unset();
    session_destroy();
    echo "Session destroyed"; // Add this for debugging
    header("Location: login.php");
    exit();