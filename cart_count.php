<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';
$cartManager = new CartManager($pdo);
echo count($cartManager->getCart());
?>