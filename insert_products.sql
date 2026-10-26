-- Insertion de quelques produits d'exemple
INSERT INTO products (category_id, sku, name, slug, description, price, stock) VALUES
(1, 'IP14-128', 'iPhone 14 Pro 128GB', 'iphone-14-pro-128gb', 'Le dernier iPhone avec une puce A16 Bionic et un appareil photo de 48MP', 1299.99, 50),
(1, 'SG-S23U', 'Samsung Galaxy S23 Ultra', 'samsung-s23-ultra', 'Le flagship Samsung avec stylet S-Pen et zoom x100', 1199.99, 30),
(2, 'MB-M2-256', 'MacBook Pro M2 256GB', 'macbook-pro-m2', 'Portable Apple avec la nouvelle puce M2, 13 pouces', 1499.99, 25),
(2, 'LP-X1', 'Lenovo ThinkPad X1', 'lenovo-thinkpad-x1', 'Ordinateur portable professionnel avec Intel i7', 1299.99, 20),
(3, 'AP-AIRPODS', 'AirPods Pro 2', 'airpods-pro-2', 'Écouteurs sans fil avec réduction de bruit active', 279.99, 100),
(3, 'LG-27GP', 'LG 27" Gaming Monitor', 'lg-27-gaming', 'Moniteur gaming 27" 165Hz QHD', 399.99, 15);

-- Insertion des images pour les produits
INSERT INTO product_images (product_id, file_path, alt_text, position) VALUES
(1, 'assets/img/products/iphone14-pro-1.jpg', 'iPhone 14 Pro face avant', 1),
(1, 'assets/img/products/iphone14-pro-2.jpg', 'iPhone 14 Pro face arrière', 2),
(2, 'assets/img/products/s23-ultra-1.jpg', 'Samsung S23 Ultra face avant', 1),
(2, 'assets/img/products/s23-ultra-2.jpg', 'Samsung S23 Ultra face arrière', 2),
(3, 'assets/img/products/macbook-m2-1.jpg', 'MacBook Pro M2 ouvert', 1),
(4, 'assets/img/products/thinkpad-x1-1.jpg', 'ThinkPad X1 vue de face', 1),
(5, 'assets/img/products/airpods-pro-1.jpg', 'AirPods Pro dans leur boîtier', 1),
(6, 'assets/img/products/lg-monitor-1.jpg', 'Moniteur LG Gaming', 1);