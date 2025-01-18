<?php

// Logout the user
session_start();
session_destroy();

header('Location: /views/');

?>