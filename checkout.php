<?php
require_once 'php/config.php';
require_once 'php/CartManager.php';

$cartManager = new CartManager($pdo);
$cart = $cartManager->getCart();
$total = $cartManager->getTotal();

// Pour l'instant, checkout est une simulation — on n'enregistre pas la commande en base
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vous pouvez étendre ceci pour enregistrer la commande via CartManager::checkout
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    // Simuler une validation
    if (empty($name) || empty($email) || empty($address)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Ici on aurait appelé $cartManager->checkout(...) si les tables étaient disponibles
        $success = "Merci, $name — votre commande a été simulée (non enregistrée). Montant : " . number_format($total, 2, ',', ' ') . " €";
        $cartManager->clear();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Digishop</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Passer à la caisse</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="notice"><?php echo htmlspecialchars($success); ?></div>
            <a href="index.html" class="cta-button">Retour à l'accueil</a>
        <?php else: ?>
            <h2>Récapitulatif</h2>
            <p>Total : <strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></p>

            <form method="post">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Adresse de livraison</label>
                    <textarea name="address" rows="4" required></textarea>
                </div>
                <button type="submit" class="cta-button">Simuler la commande</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>