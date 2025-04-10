<?php
session_start(); // Start sessie
session_destroy(); // Verwijder sessie
header("Location: login.php"); // Terug naar loginpagina
