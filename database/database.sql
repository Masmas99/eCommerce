-- Database schema for Pidii Modern Minimalist E-Commerce
CREATE DATABASE IF NOT EXISTS `db_pidii_ecommerce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_pidii_ecommerce`;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Products Table
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `price` DECIMAL(12,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 10,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `badge` VARCHAR(50) DEFAULT NULL, -- e.g. 'Limited Edition', 'Sale', 'New Arrival'
  `rating` DECIMAL(2,1) DEFAULT 4.8,
  `sizes` VARCHAR(100) DEFAULT 'S, M, L, XL',
  `colors` VARCHAR(100) DEFAULT 'Black, Off-White, Charcoal',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Banners Table
CREATE TABLE IF NOT EXISTS `banners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `link` VARCHAR(255) DEFAULT 'products.php',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `courier` VARCHAR(50) NOT NULL,
  `shipping_cost` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(12,2) NOT NULL,
  `status` ENUM('Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_method` VARCHAR(50) NOT NULL,
  `tracking_number` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_title` VARCHAR(200) NOT NULL,
  `size` VARCHAR(20) DEFAULT NULL,
  `color` VARCHAR(30) DEFAULT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DATA
-- Default Users: admin@example.com (password: adminpassword), customer@example.com (password: customerpassword)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`) VALUES
(1, 'Admin Pidii', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567890', 'HQ Studio Jakarta, Indonesia'),
(2, 'Jane Doe', 'customer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '089876543210', 'Jl. Sudirman No. 45, Jakarta Selatan')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Categories
INSERT INTO `categories` (`id`, `category_name`, `slug`, `image`) VALUES
(1, 'Outerwear & Tailored', 'outerwear-tailored', 'cat_outerwear.jpg'),
(2, 'Minimalist Tops', 'minimalist-tops', 'cat_tops.jpg'),
(3, 'Trousers & Skirts', 'trousers-skirts', 'cat_trousers.jpg'),
(4, 'Footwear & Boots', 'footwear-boots', 'cat_footwear.jpg'),
(5, 'Leather Accessories', 'leather-accessories', 'cat_accessories.jpg')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Banners
INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `is_active`) VALUES
(1, 'THE MONOCHROME ESSENTIALS', 'Autumn/Winter 2026 Collection - Pure Lines & Sharp Silhouettes', 'hero_banner_1.jpg', 'products.php', 1),
(2, 'STRUCTURED MINIMALISM', 'Discover hand-tailored outerwear designed for timeless versatility.', 'hero_banner_2.jpg', 'products.php?category=outerwear-tailored', 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Products
INSERT INTO `products` (`id`, `category_id`, `title`, `slug`, `price`, `stock`, `description`, `image`, `badge`, `rating`, `sizes`, `colors`) VALUES
(1, 1, 'Oversized Wool Trench Coat', 'oversized-wool-trench-coat', 2450000.00, 15, 'Meticulously crafted from heavyweight premium wool blend. Features sharp notch lapels, structured shoulders, and a detachable waist belt for a bold minimalist silhouette.', 'prod_coat.jpg', 'Limited Edition', 4.9, 'S, M, L, XL', 'Deep Black, Charcoal'),
(2, 1, 'Structured Double-Breasted Blazer', 'structured-double-breasted-blazer', 1850000.00, 20, 'Tailored architectural blazer featuring structured pads, clean welt pockets, and horn buttons. Perfectly suited for elevated day-to-night layering.', 'prod_blazer.jpg', 'New Arrival', 4.8, 'S, M, L', 'Deep Black, Off-White'),
(3, 2, 'Raw Silk Boxy Shirt', 'raw-silk-boxy-shirt', 890000.00, 30, 'Crafted from 100% organic raw silk with a modern boxy cut, hidden placket button closure, and dropped shoulders.', 'prod_silk_shirt.jpg', 'Sale', 4.7, 'S, M, L, XL', 'Off-White, Sand, Black'),
(4, 2, 'Heavy Knit Asymmetric Top', 'heavy-knit-asymmetric-top', 650000.00, 25, 'Ribbed compact knit featuring a sculptural neckline and asymmetric hemline. Soft cotton-cashmere blend.', 'prod_knit.jpg', 'New Arrival', 4.9, 'S, M, L', 'Deep Black, Slate Gray'),
(5, 3, 'High-Waisted Wide Leg Trousers', 'high-waisted-wide-leg-trousers', 1150000.00, 18, 'Precision-pleated wide leg trousers with a high waist, side seam pockets, and clean floor-sweeping hem.', 'prod_trousers.jpg', 'Limited Edition', 4.8, 'S, M, L', 'Deep Black, Off-White'),
(6, 4, 'Square-Toe Leather Ankle Boots', 'square-toe-leather-ankle-boots', 1950000.00, 12, 'Handcrafted calfskin leather ankle boots featuring a distinct square toe box, rear metallic zip, and 60mm architectural block heel.', 'prod_boots.jpg', 'Limited Edition', 5.0, '38, 39, 40, 41, 42', 'Deep Black'),
(7, 5, 'Geometric Minimalist Leather Tote', 'geometric-minimalist-leather-tote', 1450000.00, 14, 'Structured full-grain leather tote bag with clean magnetic top closure, internal zip pocket, and reinforced minimalist top handles.', 'prod_tote.jpg', 'Sale', 4.9, 'One Size', 'Deep Black, Off-White'),
(8, 5, 'Sculptural Silver Ring Set', 'sculptural-silver-ring-set', 450000.00, 40, 'Solid sterling silver ring trio with brushed matte finish and geometric facets.', 'prod_rings.jpg', 'New Arrival', 4.8, 'S, M, L', 'Silver')
ON DUPLICATE KEY UPDATE `id`=`id`;
