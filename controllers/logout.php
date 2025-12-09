<?php
session_start();
session_destroy(); // Destrói todas as sessões (Admin ou Cliente)
header("Location: ../index.html"); // Manda de volta para a home
exit;
?>