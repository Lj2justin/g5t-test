<?php
require_once 'config.php';

class ProductManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProducts($page = 1, $limit = 12, $category = null) {
        $offset = ($page - 1) * $limit;
        $params = [];
        
        $sql = "SELECT p.*, c.name as category_name, 
                (SELECT file_path FROM product_images WHERE product_id = p.id LIMIT 1) as image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1";
        
        if ($category) {
            $sql .= " AND c.slug = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ? AND p.is_active = 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $product = $stmt->fetch();
        
        if ($product) {
            // Récupérer les images du produit
            $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY position";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $product['images'] = $stmt->fetchAll();
            
            // Récupérer les avis
            $sql = "SELECT r.*, u.first_name 
                    FROM reviews r
                    LEFT JOIN users u ON r.user_id = u.id
                    WHERE r.product_id = ? AND r.status = 'approved'
                    ORDER BY r.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $product['reviews'] = $stmt->fetchAll();
        }
        
        return $product;
    }

    public function searchProducts($query) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT file_path FROM product_images WHERE product_id = p.id LIMIT 1) as image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 
                AND (p.name LIKE ? OR p.description LIKE ?)
                ORDER BY p.created_at DESC
                LIMIT 24";
        
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        
        return $stmt->fetchAll();
    }

    public function addProduct($data) {
        if (!isAdmin()) {
            throw new Exception("Accès non autorisé");
        }

        $sql = "INSERT INTO products (category_id, sku, name, slug, description, price, stock)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['category_id'],
                $data['sku'],
                $data['name'],
                $this->createSlug($data['name']),
                $data['description'],
                $data['price'],
                $data['stock']
            ]);
            
            $productId = $this->pdo->lastInsertId();
            
            // Gestion des images
            if (isset($data['images']) && is_array($data['images'])) {
                $sql = "INSERT INTO product_images (product_id, file_path, alt_text, position) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                
                foreach ($data['images'] as $position => $image) {
                    $stmt->execute([
                        $productId,
                        $image['path'],
                        $image['alt'] ?? null,
                        $position
                    ]);
                }
            }
            
            $this->pdo->commit();
            return $productId;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function createSlug($string) {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}