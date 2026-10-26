<?php
require_once 'php/config.php';

// Insérer les catégories
$categories = [
    ['Téléphones', 'telephones', 'Smartphones et téléphones portables'],
    ['Ordinateurs', 'ordinateurs', 'Ordinateurs portables et de bureau'],
    ['Accessoires', 'accessoires', 'Accessoires informatiques et électroniques']
];

$stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
foreach ($categories as $category) {
    try {
        $stmt->execute($category);
        echo "Catégorie {$category[0]} ajoutée avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de la catégorie {$category[0]}: " . $e->getMessage() . "<br>";
    }
}

// Insérer les produits
$products = [
    [1, 'SP001', 'Samsung Galaxy S21', 'samsung-galaxy-s21', 'Le dernier smartphone Samsung avec des fonctionnalités avancées', 899.99, 10],
    [1, 'IP001', 'iPhone 13', 'iphone-13', 'Le dernier iPhone avec la puce A15 Bionic', 999.99, 8],
    [2, 'LP001', 'MacBook Pro 14"', 'macbook-pro-14', 'MacBook Pro avec puce M1 Pro', 1999.99, 5],
    [2, 'LP002', 'Dell XPS 15', 'dell-xps-15', 'Ordinateur portable performant pour les professionnels', 1499.99, 7],
    [3, 'ACC001', 'AirPods Pro', 'airpods-pro', 'Écouteurs sans fil avec réduction active du bruit', 249.99, 15],
    [3, 'ACC002', 'Souris Gaming Logitech G502', 'logitech-g502', 'Souris gaming haute précision', 79.99, 20]
];

$stmt = $pdo->prepare("INSERT INTO products (category_id, sku, name, slug, description, price, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($products as $product) {
    try {
        $stmt->execute($product);
        echo "Produit {$product[2]} ajouté avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout du produit {$product[2]}: " . $e->getMessage() . "<br>";
    }
}

// Créer l'utilisateur admin
try {
    $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)");
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt->execute(['admin@digishop.com', $adminPassword, 'Admin', 'User', 'admin']);
    echo "Utilisateur admin créé avec succès.<br>";
} catch (PDOException $e) {
    echo "Erreur lors de la création de l'utilisateur admin: " . $e->getMessage() . "<br>";
}

echo "Configuration de la base de données terminée.";
?>