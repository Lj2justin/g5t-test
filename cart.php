<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';

$cartManager = new CartManager($pdo);
$cart = $cartManager->getCart();
$total = $cartManager->getTotal();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Digishop</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="index.html" class="logo">Digishop</a>
                <div class="nav-links">
                    <a href="index.html">Accueil</a>
                    <a href="telephones.html">Téléphones</a>
                    <a href="ordinateurs.html">Ordinateurs</a>
                    <a href="accessoires.html">Accessoires</a>
                    <a href="about.html">À propos</a>
                    <a href="contact.html">Contact</a>
                </div>
                <div class="nav-actions">
                    <a href="cart.php" class="cart-icon">🛒 <span class="cart-count"><?php echo count($cart); ?></span></a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>Votre panier</h1>

            <?php if (!empty($_SESSION['cart_message'])): ?>
                <div class="notice"><?php echo htmlspecialchars($_SESSION['cart_message']); unset($_SESSION['cart_message']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['cart_error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_SESSION['cart_error']); unset($_SESSION['cart_error']); ?></div>
            <?php endif; ?>

            <?php if (empty($cart)): ?>
                <p>Votre panier est vide.</p>
                <a href="index.html" class="cta-button">Continuer vos achats</a>
            <?php else: ?>
                <form action="update_cart.php" method="post">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                                </td>
                                <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</td>
                                <td>
                                    <a href="remove_cart.php?id=<?php echo $item['id']; ?>" class="remove-link">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-actions">
                        <div class="cart-total">Total : <strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></div>
                        <div class="cart-buttons">
                            <button type="submit" class="apply-filters">Mettre à jour</button>
                            <a href="checkout.php" class="cta-button">Passer à la caisse</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>À propos de Digishop</h4>
                    <p>Votre destination de confiance pour l'électronique.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>