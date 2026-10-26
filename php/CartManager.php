<?php
require_once 'config.php';

class CartManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    public function addItem($productId, $quantity = 1) {
        // Vérifier si le produit existe et est en stock
        $stmt = $this->pdo->prepare("SELECT id, name, price, stock FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Produit non trouvé");
        }
        
        if ($product['stock'] < $quantity) {
            throw new Exception("Stock insuffisant");
        }
        
        // Ajouter ou mettre à jour le panier
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
        
        return true;
    }
    
    public function removeItem($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }
    
    public function updateQuantity($productId, $quantity) {
        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }
        
        // Vérifier le stock
        $stmt = $this->pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if ($product && $quantity <= $product['stock']) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            return true;
        }
        
        return false;
    }
    
    public function getCart() {
        return $_SESSION['cart'];
    }
    
    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    public function clear() {
        $_SESSION['cart'] = [];
    }
    
    public function checkout($userId, $shippingAddress, $billingAddress) {
        if (empty($_SESSION['cart'])) {
            throw new Exception("Le panier est vide");
        }
        
        $this->pdo->beginTransaction();
        
        try {
            // Créer la commande
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, total, shipping_address_id, billing_address_id)
                VALUES (?, ?, ?, ?)
            ");
            
            $total = $this->getTotal();
            $stmt->execute([$userId, $total, $shippingAddress['id'], $billingAddress['id']]);
            $orderId = $this->pdo->lastInsertId();
            
            // Ajouter les articles de la commande
            $stmt = $this->pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, total_price)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            // Mise à jour du stock
            $updateStock = $this->pdo->prepare("
                UPDATE products 
                SET stock = stock - ?
                WHERE id = ? AND stock >= ?
            ");
            
            foreach ($_SESSION['cart'] as $item) {
                // Ajouter l'article à la commande
                $stmt->execute([
                    $orderId,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['price'] * $item['quantity']
                ]);
                
                // Mettre à jour le stock
                $updateStock->execute([
                    $item['quantity'],
                    $item['id'],
                    $item['quantity']
                ]);
                
                if ($updateStock->rowCount() === 0) {
                    throw new Exception("Stock insuffisant pour " . $item['name']);
                }
            }
            
            $this->pdo->commit();
            $this->clear(); // Vider le panier après la commande
            
            return $orderId;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}