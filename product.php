<?php
require_once 'php/config.php';
require_once 'php/ProductManager.php';

$productManager = new ProductManager($pdo);

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$productId) {
    header('Location: index.php');
    exit;
}

try {
    $product = $productManager->getProductById($productId);
    if (!$product) {
        header('HTTP/1.0 404 Not Found');
        include '404.php';
        exit;
    }
} catch (Exception $e) {
    die("Une erreur est survenue : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Digishop</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="header">
        <!-- Même header que index.html -->
    </header>

    <main class="product-page">
        <div class="container">
            <nav class="breadcrumb">
                <a href="index.php">Accueil</a> &gt;
                <a href="category.php?cat=<?= htmlspecialchars($product['category_name']) ?>">
                    <?= htmlspecialchars($product['category_name']) ?>
                </a> &gt;
                <span><?= htmlspecialchars($product['name']) ?></span>
            </nav>

            <div class="product-details">
                <div class="product-gallery">
                    <?php if (!empty($product['images'])): ?>
                        <div class="main-image">
                            <img src="<?= htmlspecialchars($product['images'][0]['file_path']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 id="main-product-image">
                        </div>
                        <div class="thumbnail-list">
                            <?php foreach ($product['images'] as $image): ?>
                                <img src="<?= htmlspecialchars($image['file_path']) ?>"
                                     alt="<?= htmlspecialchars($image['alt_text'] ?? $product['name']) ?>"
                                     class="thumbnail"
                                     onclick="document.getElementById('main-product-image').src='<?= htmlspecialchars($image['file_path']) ?>'">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>
                    <div class="product-price">
                        <?= number_format($product['price'], 2, ',', ' ') ?> €
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <div class="stock-info in-stock">En stock</div>
                    <?php else: ?>
                        <div class="stock-info out-of-stock">Rupture de stock</div>
                    <?php endif; ?>

                    <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        
                        <div class="quantity-selector">
                            <button type="button" onclick="updateQuantity(-1)">-</button>
                            <input type="number" name="quantity" value="1" min="1" 
                                   max="<?= $product['stock'] ?>" id="quantity-input">
                            <button type="button" onclick="updateQuantity(1)">+</button>
                        </div>

                        <button type="submit" class="add-to-cart-button"
                                <?= $product['stock'] === 0 ? 'disabled' : '' ?>>
                            Ajouter au panier
                        </button>
                    </form>

                    <div class="product-description">
                        <h2>Description</h2>
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </div>

                    <?php if (!empty($product['reviews'])): ?>
                        <div class="product-reviews">
                            <h2>Avis clients</h2>
                            <?php foreach ($product['reviews'] as $review): ?>
                                <div class="review">
                                    <div class="review-header">
                                        <span class="reviewer-name">
                                            <?= htmlspecialchars($review['first_name']) ?>
                                        </span>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?= $i <= $review['rating'] ? 'filled' : '' ?>">
                                                    ★
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <!-- Même footer que index.html -->
    </footer>

    <script>
        function updateQuantity(delta) {
            const input = document.getElementById('quantity-input');
            const newValue = Math.max(1, Math.min(<?= $product['stock'] ?>, 
                parseInt(input.value) + delta));
            input.value = newValue;
        }
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>