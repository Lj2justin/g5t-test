Digishop - Starter kit

Contenu:
- index.html: page d'accueil
- assets/css/styles.css: styles
- assets/js/main.js: interactions client (panier local, formulaires)
- php/: fichiers PHP (config, ProductManager, CartManager)
- product.php: page produit dynamique
- database.sql: script de création de la base de données

Installation locale (Windows - XAMPP/Laragon):
1) Copier le dossier "Intitulé E-commerce" dans le dossier public de ton serveur (ex: C:\\xampp\\htdocs\\)
2) Importer `database.sql` dans MySQL (via phpMyAdmin ou mysql client)
3) Ajuster les identifiants DB dans `php/config.php`
4) Lancer Apache & MySQL
5) Ouvrir http://localhost/Intitulé%20E-commerce/ dans ton navigateur

Notes:
- Ce kit est minimal et destiné à développement/local. Pour production, sécuriser, activer HTTPS, configurer des sauvegardes et utiliser un vrai fournisseur de stockage pour les images.
