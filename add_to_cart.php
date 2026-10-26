<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';

$cart = new CartManager($pdo);

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$qty = isset($_REQUEST['qty']) ? max(1, (int)$_REQUEST['qty']) : 1;

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.html';

try {
    if ($id > 0) {
        $cart->addItem($id, $qty);
        $_SESSION['cart_message'] = 'Produit ajouté au panier.';
    }
} catch (Exception $e) {
    $_SESSION['cart_error'] = $e->getMessage();
}

header('Location: ' . $redirect);
exit;
?>