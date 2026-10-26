<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';

$cart = new CartManager($pdo);
$quantities = $_POST['quantities'] ?? [];

foreach ($quantities as $productId => $qty) {
    $productId = (int)$productId;
    $qty = (int)$qty;
    if ($qty <= 0) {
        $cart->removeItem($productId);
    } else {
        $cart->updateQuantity($productId, $qty);
    }
}

$_SESSION['cart_message'] = 'Panier mis à jour.';
header('Location: cart.php');
exit;
?>