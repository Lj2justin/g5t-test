<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cart = new CartManager($pdo);
if ($id > 0) {
    $cart->removeItem($id);
    $_SESSION['cart_message'] = 'Article supprimé du panier.';
}

header('Location: cart.php');
exit;
?>