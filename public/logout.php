<?php
require_once __DIR__ . '/bootstrap.php';

$authService->logout();
redirect('index.php');
