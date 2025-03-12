-- Tworzenie bazy danych
CREATE DATABASE blog_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE blog_cms;

-- Tabela kategorii
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela użytkowników
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    role ENUM('admin', 'author') DEFAULT 'author',
    active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela postów
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

ALTER TABLE posts ADD COLUMN image_path VARCHAR(255) NULL;

-- Tabela komentarzy
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Tabela plików
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela wiadomosci
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dodawanie użytkowników
INSERT INTO users (username, password_hash, email, role, active) VALUES 
('admin', '$2y$10$eImiTXuWVxfM37uY4JANjQe5Qx9Yl6h5Z9e5Z9e5Z9e5Z9e5Z9e5Z', 'admin@example.com', 'admin', 1),
('author1', '$2y$10$eImiTXuWVxfM37uY4JANjQe5Qx9Yl6h5Z9e5Z9e5Z9e5Z9e5Z9e5Z', 'author1@example.com', 'author', 1),
('author2', '$2y$10$eImiTXuWVxfM37uY4JANjQe5Qx9Yl6h5Z9e5Z9e5Z9e5Z9e5Z9e5Z', 'author2@example.com', 'author', 1);

-- Dodawanie kategorii
INSERT INTO categories (name) VALUES 
('Drift'),
('Car Show'),
('Tuning');

-- Dodawanie postów
INSERT INTO posts (title, content, author_id, category_id, img_path) VALUES 
('Drift Event', 'This weekend, we witnessed an adrenaline-pumping drift event with top drivers showcasing their incredible skills on the track. The crowd was thrilled by the breathtaking turns, high-speed maneuvers, and tire smoke that filled the air.', 1, 1, 'uploads/240sx.jpg'),
('Car Show Highlights', 'The annual car show brought together some of the finest vehicles from around the globe. From classic muscle cars to cutting-edge electric vehicles, the event was a haven for car enthusiasts.', 2, 2, 'uploads/cresside.jpg'),
('Tuning Tips', 'Are you looking to enhance your car’s performance? In this post, we cover essential tuning tips, including upgrading your exhaust system, fine-tuning the suspension, and optimizing engine performance for better horsepower.', 3, 3, 'uploads/interior.jpg'),
('Drift Championship', 'The Drift Championship finals brought together elite drivers from around the world. The event featured intense head-to-head battles, with jaw-dropping drifts and razor-sharp precision on every corner.', 4, 1, 'uploads/skynight.jpg'),
('Exotic Car Showcase', 'Luxury and style were on full display at the Exotic Car Showcase. Attendees marveled at the sleek designs and unmatched performance of the latest models from top brands like Ferrari, Lamborghini, and McLaren.', 1, 2, 'uploads/nissan.jpg'),
('DIY Car Tuning', 'Thinking of tuning your car at home? Learn the basics of do-it-yourself tuning, from installing a cold air intake to upgrading your car’s ECU. Save money and get hands-on experience with these tips.', 2, 3, 'uploads/parking.jpg'),
('Night Drift Experience', 'Drifting under the stars provided a surreal experience for both drivers and spectators. The illuminated track and roaring engines made it a night to remember.', 3, 1, 'uploads/showcase.jpg'),
('Rare Car Collection', 'A private car collector opened their garage to the public, revealing a stunning collection of rare and vintage cars. Each vehicle had a unique story, making the event a walk through automotive history.', 4, 2, 'uploads/200sx.jpg'),
('Beginner’s Guide to Tuning', 'If you’re new to car tuning, this post is for you. Learn the basics, such as upgrading spark plugs, choosing the right tires, and adjusting your car’s air-fuel ratio for optimal performance.', 1, 3, 'uploads/parking.jpg');


-- Dodawanie komentarzy
INSERT INTO comments (post_id, author_name, content) VALUES 
(1, 'User1', 'Great event!'),
(2, 'User2', 'Loved the car show!'),
(3, 'User3', 'Thanks for the tips!');

-- Dodawanie wiadomości
INSERT INTO messages (name, email, message) VALUES 
('John Doe', 'john@example.com', 'Hello, I love your blog!'),
('Jane Smith', 'jane@example.com', 'Great content, keep it up!');

