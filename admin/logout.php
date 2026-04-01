<?php
require_once '../includes/auth.php';
logout();
header('Location: /admin/login.php');
exit;
