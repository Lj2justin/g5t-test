<?php
require_once 'php/config.php';
require_once 'php/ProductManager.php';

$category = isset($_GET['cat']) ? $_GET['cat'] : '';
$productManager = new ProductManager($pdo);
$products = $productManager->getAllProducts(1, 100, $category);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléphones - Digishop</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="index.html" class="logo">Digishop</a>
                <div class="nav-links">
                    <a href="index.html">Accueil</a>
                    <a href="category.php?cat=telephones" class="active">Téléphones</a>
                    <a href="category.php?cat=ordinateurs">Ordinateurs</a>
                    <a href="category.php?cat=accessoires">Accessoires</a>
                    <a href="about.html">À propos</a>
                    <a href="contact.html">Contact</a>
                </div>
                <div class="nav-actions">
                    <form action="search.php" method="get" class="search-form">
                        <input type="text" name="q" placeholder="Rechercher un téléphone...">
                        <button type="submit">🔍</button>
                    </form>
                    <a href="cart.php" class="cart-icon">🛒 <span class="cart-count">0</span></a>
                    <a href="login.php" class="account-icon">👤</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="category-header">
            <div class="container">
                <h1>Téléphones</h1>
                <p class="lead">Découvrez notre sélection de smartphones et téléphones portables</p>
            </div>
        </section>

        <section class="category-content">
            <div class="container">
                <div class="category-layout">
                    <!-- Filtres -->
                    <aside class="filters">
                        <h2>Filtres</h2>
                        <form class="filter-form" action="" method="GET">
                            <input type="hidden" name="cat" value="telephones">
                            
                            <div class="filter-group">
                                <h3>Marque</h3>
                                <label><input type="checkbox" name="brand[]" value="samsung"> Samsung</label>
                                <label><input type="checkbox" name="brand[]" value="apple"> Apple</label>
                                <label><input type="checkbox" name="brand[]" value="xiaomi"> Xiaomi</label>
                                <label><input type="checkbox" name="brand[]" value="huawei"> Huawei</label>
                            </div>

                            <div class="filter-group">
                                <h3>Prix</h3>
                                <div class="price-range">
                                    <input type="number" name="min_price" placeholder="Min">
                                    <span>à</span>
                                    <input type="number" name="max_price" placeholder="Max">
                                </div>
                            </div>

                            <div class="filter-group">
                                <h3>Stockage</h3>
                                <label><input type="checkbox" name="storage[]" value="64"> 64 Go</label>
                                <label><input type="checkbox" name="storage[]" value="128"> 128 Go</label>
                                <label><input type="checkbox" name="storage[]" value="256"> 256 Go</label>
                                <label><input type="checkbox" name="storage[]" value="512"> 512 Go</label>
                            </div>

                            <button type="submit" class="apply-filters">Appliquer les filtres</button>
                        </form>
                    </aside>

                    <!-- Liste des produits -->
                    <div class="products-section">
                        <div class="products-header">
                            <div class="products-count">
                                <span><?php echo count($products); ?> produits trouvés</span>
                            </div>
                            <div class="products-sort">
                                <select name="sort" id="sort">
                                    <option value="popular">Les plus populaires</option>
                                    <option value="price_asc">Prix croissant</option>
                                    <option value="price_desc">Prix décroissant</option>
                                    <option value="new">Nouveautés</option>
                                </select>
                            </div>
                        </div>

                        <div class="product-grid">
                            <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="assets/img/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <div class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> $</div>
                                    <button class="add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination">
                            <a href="#" class="page-link">&laquo; Précédent</a>
                            <a href="#" class="page-link active">1</a>
                            <a href="#" class="page-link">2</a>
                            <a href="#" class="page-link">3</a>
                            <a href="#" class="page-link">Suivant &raquo;</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>À propos de Digishop</h4>
                    <p>Votre destination de confiance pour l'électronique.</p>
                </div>
                <div class="footer-section">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="about.html">À propos</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="privacy.html">Confidentialité</a></li>
                        <li><a href="terms.html">CGV</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Votre email">
                        <button type="submit">S'abonner</button>
                    </form>
                </div>
                <div class="footer-section">
                    <h4>Nous contacter</h4>
                    <p>Email: contact@digishop.com</p>
                    <p>Tél: +243 82 59 54 028</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>