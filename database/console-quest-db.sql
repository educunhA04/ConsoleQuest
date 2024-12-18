DROP SCHEMA IF EXISTS lbaw24151 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw24151;
SET search_path TO lbaw24151;


-----------------------------------------
-- Drop Tables
-----------------------------------------

DROP TABLE IF EXISTS User_Shipping CASCADE;
DROP TABLE IF EXISTS Shipping_Address CASCADE;
DROP TABLE IF EXISTS Notification_User CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS Report CASCADE;
DROP TABLE IF EXISTS Review CASCADE;
DROP TABLE IF EXISTS Order_Product CASCADE;
DROP TABLE IF EXISTS Transaction CASCADE;
DROP TABLE IF EXISTS "Order" CASCADE;
DROP TABLE IF EXISTS Shopping_Cart CASCADE;
DROP TABLE IF EXISTS Wishlist CASCADE;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS "Type" CASCADE;
DROP TABLE IF EXISTS Product CASCADE;
DROP TABLE IF EXISTS Category CASCADE;
DROP TABLE IF EXISTS "User" CASCADE;

-----------------------------------------
-- Drop Types
-----------------------------------------
DROP TYPE IF EXISTS categories CASCADE;
DROP TYPE IF EXISTS order_status CASCADE;

-----------------------------------------
-- Drop Functions
-----------------------------------------
DROP FUNCTION IF EXISTS product_search_update CASCADE;
DROP FUNCTION IF EXISTS enforce_order_cancellation CASCADE;
DROP FUNCTION IF EXISTS verify_purchase_for_review CASCADE;
DROP FUNCTION IF EXISTS validate_promotion_release_date CASCADE;
DROP FUNCTION IF EXISTS restrict_out_of_stock_cart_addition CASCADE;
DROP FUNCTION IF EXISTS enforce_single_review_per_product CASCADE;
DROP FUNCTION IF EXISTS enforce_own_review_modification CASCADE;
DROP FUNCTION IF EXISTS enforce_user_related_notifications CASCADE;
DROP FUNCTION IF EXISTS restrict_out_of_stock_wishlist_addition CASCADE;
DROP FUNCTION IF EXISTS restrict_address_change_for_shipped_orders CASCADE;

-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE categories AS ENUM(
  'Consoles', 
  'Video Games', 
  'Controllers'
);

CREATE TYPE order_status AS ENUM(
  'processing', 
  'shipped', 
  'delivered',
  'cancelled'
);

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE "User" (
    id SERIAL  PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    shipping_address TEXT,
    remember_token VARCHAR(255),
    image TEXT,
    blocked BOOLEAN DEFAULT FALSE
);
CREATE TABLE Admin (
    id SERIAL  PRIMARY KEY,
    name TEXT  NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
);
CREATE TABLE Category (
    id SERIAL PRIMARY KEY,
    type categories NOT NULL 
);
CREATE TABLE "Type"(
    id SERIAL PRIMARY KEY,
    name TEXT UNIQUE NOT NULL 
);
CREATE TABLE Product (
    id SERIAL PRIMARY KEY,
    category_id INT NOT NULL ,
    name TEXT NOT NULL,
    type_id INT NOT NULL,
    description TEXT,
    quantity INT CHECK (quantity >= 0),
    price NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    discount_percent NUMERIC(5, 2) CHECK (discount_percent >= 0 AND discount_percent <= 100),
    FOREIGN KEY (category_id) REFERENCES Category(id) ON UPDATE CASCADE,
    FOREIGN KEY (type_id) REFERENCES "Type"(id) ON UPDATE CASCADE

);
CREATE TABLE Product_Images(
    id SERIAL PRIMARY KEY,
    url TEXT,
    product_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES Product(id) ON UPDATE CASCADE
);
CREATE TABLE Wishlist (
    id SERIAL  PRIMARY KEY,
    user_id INT NOT NULL ,
    product_id INT NOT NULL ,
    UNIQUE (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(id) ON UPDATE CASCADE
);

CREATE TABLE Shopping_Cart (
    id SERIAL  PRIMARY KEY,
    user_id INT NOT NULL ,
    product_id INT NOT NULL ,
    quantity INT NOT NULL,
    UNIQUE (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(id) ON UPDATE CASCADE
);

CREATE TABLE "Order" (
    id  SERIAL  PRIMARY KEY,
    user_id INT NOT NULL,
    tracking_number TEXT UNIQUE NOT NULL,
    status order_status NOT NULL,
    buy_date DATE NOT NULL,
    estimated_delivery_date DATE NOT NULL,
    shipping_address TEXT,
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE
);

CREATE TABLE Transaction (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    order_id INT NOT NULL UNIQUE ,
    code TEXT UNIQUE NOT NULL,
    price NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    NIF TEXT,
    credit_card_number TEXT NOT NULL,
    credit_card_exp_date DATE NOT NULL,
    credit_card_cvv TEXT NOT NULL,
    shipping_address TEXT,
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (order_id) REFERENCES "Order"(id) ON UPDATE CASCADE
);

CREATE TABLE Order_Product (
    id  SERIAL PRIMARY KEY,
    order_id INT NOT NULL ,
    product_id INT NOT NULL ,
    quantity INT NOT NULL,
    UNIQUE (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES "Order"(id) ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(id) ON UPDATE CASCADE
);

CREATE TABLE Review (
    id SERIAL  PRIMARY KEY,
    user_id INT NOT NULL ,
    product_id INT NOT NULL ,
    description TEXT NOT NULL,
    rating NUMERIC(3, 1) CHECK (rating >= 0 AND rating <= 5),
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(id) ON UPDATE CASCADE
);

CREATE TABLE Report (
    id SERIAL  PRIMARY KEY,
    review_id INT NOT NULL ,
    user_id INT NOT NULL ,
    reason TEXT NOT NULL,
    description TEXT,
    FOREIGN KEY (review_id) REFERENCES Review(id) ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE
);

CREATE TABLE Notification (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    viewed BOOLEAN DEFAULT FALSE,
    date DATE NOT NULL CHECK (date <= CURRENT_DATE)
);

CREATE TABLE Notification_User (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL ,
    notification_id INT NOT NULL,
    UNIQUE (user_id, notification_id),
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (notification_id) REFERENCES Notification(id) ON UPDATE CASCADE
);

CREATE TABLE Shipping_Address (
    id SERIAL  PRIMARY KEY,
    postal_code TEXT NOT NULL,
    address TEXT NOT NULL,
    location TEXT NOT NULL,
    country TEXT NOT NULL,
    is_primary BOOLEAN NOT NULL
);

CREATE TABLE User_Shipping (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL ,
    shipping_id INT NOT NULL ,
    UNIQUE (user_id, shipping_id),
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (shipping_id) REFERENCES Shipping_Address(id) ON UPDATE CASCADE
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) UNIQUE NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    PRIMARY KEY (email, token)
);

-----------------------------------------
-- PERFORMANCE INDICES
-----------------------------------------
CREATE INDEX idx_order_dates ON "Order" USING btree (buy_date, estimated_delivery_date); 
CLUSTER "Order" USING idx_order_dates;

CREATE INDEX idx_notification_date ON notification USING btree (date); 
CLUSTER notification USING idx_notification_date;

CREATE INDEX idx_product_price ON Product (price);
CLUSTER Product USING idx_product_price;

CREATE INDEX idx_transaction_price ON transaction (price);
CLUSTER transaction USING idx_transaction_price;

CREATE INDEX idx_review_rating ON Review (rating);

-----------------------------------------
-- FTS INDICES
-----------------------------------------
ALTER TABLE product ADD COLUMN tsvectors TSVECTOR;
CREATE FUNCTION product_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$ 
LANGUAGE plpgsql;
-- Create a trigger before insert or update on product
CREATE TRIGGER product_search_update
BEFORE INSERT OR UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE product_search_update();
-- Create a GIN index for ts_vectors
CREATE INDEX search_product ON product USING GIN (tsvectors);



-----------------------------------------
-- TRIGGERS
-----------------------------------------
-- trigger01
CREATE OR REPLACE FUNCTION enforce_order_cancellation() RETURNS TRIGGER AS $$
BEGIN
    IF OLD.status = 'shipped' AND NEW.status = 'cancelled' THEN
        RAISE EXCEPTION 'Order can only be cancelled before it is shipped';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_order_cancellation ON "Order";
CREATE TRIGGER trg_order_cancellation
BEFORE UPDATE ON "Order"
FOR EACH ROW EXECUTE FUNCTION enforce_order_cancellation();
                        


-- trigger03
CREATE OR REPLACE FUNCTION verify_purchase_for_review() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM Order_Product op
        JOIN "Order" o ON op.order_id = o.id
        WHERE op.product_id = NEW.product_id AND o.user_id = NEW.user_id) = 0 THEN
        RAISE EXCEPTION 'Only users who have purchased this product can leave a review';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_review_purchase_verification ON Review;
CREATE TRIGGER trg_review_purchase_verification
BEFORE INSERT ON Review
FOR EACH ROW EXECUTE FUNCTION verify_purchase_for_review();


-- trigger05
CREATE OR REPLACE FUNCTION restrict_out_of_stock_cart_addition() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT quantity FROM Product WHERE id = NEW.product_id) <= 0 THEN
        RAISE EXCEPTION 'This product is out of stock';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_restrict_out_of_stock_cart_addition ON Shopping_Cart;
CREATE TRIGGER trg_restrict_out_of_stock_cart_addition
BEFORE INSERT ON Shopping_Cart
FOR EACH ROW EXECUTE FUNCTION restrict_out_of_stock_cart_addition();

-- trigger06
CREATE OR REPLACE FUNCTION enforce_single_review_per_product() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM Review WHERE product_id = NEW.product_id AND user_id = NEW.user_id) > 0 THEN
        RAISE EXCEPTION 'A user can only submit one review per product';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_single_review_per_product ON Review;
CREATE TRIGGER trg_single_review_per_product
BEFORE INSERT ON Review
FOR EACH ROW EXECUTE PROCEDURE enforce_single_review_per_product();






INSERT INTO Category (id, type) VALUES
    (1, 'Consoles'),
    (2, 'Video Games'),
    (3, 'Controllers');

INSERT INTO "Type" (id, name) VALUES 
    (1, 'PS5 Console'),
    (2, 'Football'),
    (3, 'MOBA'),
    (4, 'PS5 Wireless Controller'),
    (5, 'Xbox Console'),
    (6, 'Adventure RPG'),
    (7, 'Racing'),
    (8, 'Nintendo Console'),
    (9, 'Nintendo Controller'),
    (10, 'Action RPG'),
    (11, 'Action Adventure'),
    (12, 'Horror RPG'),
    (13, 'Battle Royal');

INSERT INTO Product (id, category_id, name, type_id, description, quantity, price, discount_percent) VALUES
    (1, 1, 'PlayStation 5', 1, 'Next-gen gaming console', 50, 499.99, 10),
    (2, 2, 'EA FC 25', 2, 'Cancer game', 100, 59.99, 20),
    (3, 2, 'Fifa Street', 2, 'Goat game', 100, 19.99, 0),
    (4, 2, 'League of Legends', 3, 'Virgin game', 10, 9.99, 0),
    (5, 3, 'DualSense Controller', 4, 'PS5 Wireless Controller', 200, 69.99, 15),
    (6, 1, 'Xbox Series X', 5, 'Powerful next-gen console', 40, 499.99, 5),
    (7, 2, 'The Legend of Zelda: Breath of the Wild', 6, 'Award-winning adventure game', 60, 59.99, 10),
    (8, 2, 'Mario Kart 8 Deluxe', 7, 'Fun racing game', 80, 49.99, 5),
    (9, 1, 'Nintendo Switch OLED', 8, 'Hybrid console with OLED screen', 70, 349.99, 0),
    (10, 3, 'Nintendo Switch Pro Controller', 9, 'Wireless controller for Switch', 120, 69.99, 10),
    (11, 2, 'Elden Ring', 10, 'Open-world action RPG', 50, 59.99, 15),
    (12, 2, 'God of War Ragnarok', 11, 'Action-packed Norse mythology game', 40, 69.99, 10),
    (13, 1, 'Steam Deck', 1, 'Portable gaming PC', 30, 399.99, 5),
    (14, 3, 'Razer Wolverine V2', 5, 'Advanced controller for Xbox/PC', 50, 99.99, 10),
    (15, 2, 'Cyberpunk 2077', 10, 'Futuristic action RPG', 100, 39.99, 20),
    (16, 2, 'Horizon Forbidden West', 10, 'Post-apocalyptic open-world game', 60, 59.99, 15),
    (17, 3, 'Xbox Elite Series 2', 5, 'Premium wireless Xbox controller', 70, 179.99, 20),
    (18, 2, 'Spider-Man: Miles Morales', 11, 'Superhero action game', 90, 49.99, 10),
    (19, 2, 'Resident Evil 4 Remake', 12, 'Horror survival game', 80, 59.99, 10),
    (20, 1, 'PlayStation 4 Slim', 1, 'Compact version of PS4', 30, 299.99, 0),
    (21, 2, 'Assassins Creed IV Black Flag', 11, 'Assassins PS4 game', 80, 29.99, 10),
    (22, 2, 'Fortnite', 13, 'Popular battle royale game', 280, 20.00, 0);

INSERT INTO Product_Images (id,url, product_id) VALUES
    (1,'/dbimages/pg5.jpg', 1),
    (2,'/dbimages/eafc25.jpg', 2),
    (3,'/dbimages/fifastreet.jpg', 3),
    (4,'/dbimages/LOL.jpeg', 4),
    (5,'/dbimages/dualsense.jpg', 5),
    (6,'/dbimages/xboxseriesx.jpg', 6),
    (7,'/dbimages/zelda_botw.jpg', 7),
    (8,'/dbimages/mariokart8.jpg', 8),
    (9,'/dbimages/switch_oled.jpg', 9),
    (10,'/dbimages/switch_pro_controller.jpg', 10),
    (11,'/dbimages/eldenring.jpg', 11),
    (12,'/dbimages/gow_ragnarok.jpg', 12),
    (13,'/dbimages/steamdeck.jpg', 13),
    (14,'/dbimages/razer_wolverine.jpg', 14),
    (15,'/dbimages/cyberpunk2077.jpg', 15),
    (16,'/dbimages/horizon_fw.jpg', 16),
    (17,'/dbimages/xbox_elite2.jpg', 17),
    (18,'/dbimages/spiderman_mm.jpg', 18),
    (19,'/dbimages/re4_remake.jpg', 19),
    (20,'/dbimages/ps4_slim.jpg', 20),
    (21,'/dbimages/assassins_creed_4_black_flag.jpg', 21),
    (22,'/dbimages/fortnite.jpg', 22),
    (23,'/dbimages/fc-25-1.jpg', 2),
    (24,'/dbimages/ea-fc25-3.jpg', 2);



INSERT INTO "User" (id, username, password, name, email, image ,blocked) VALUES
    (1, 'Lopez', '$2y$10$AyqmTcuuDudCvz5A.MrEcuQ0eFsS0vY4vuW3tYFeRnlOB6ZtbF.ay', 'Rafael Augusto', 'rafa@gmail.com','userimages/lopez.jpeg', FALSE);
   
INSERT INTO Admin (id, name, email, password) VALUES
    (1,'rabeira' ,'admin@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'); 

INSERT INTO "Order" (id, user_id, tracking_number, status, buy_date, estimated_delivery_date) VALUES
    (1, 1, 'TRK123456', 'processing', '2024-01-01', '2024-01-05'),
    (2, 1, 'TRK654321', 'shipped', '2024-01-02', '2024-01-06');

INSERT INTO Transaction (id, user_id, order_id, code, price, NIF, credit_card_number, credit_card_exp_date, credit_card_cvv, shipping_address) VALUES
    (1, 1, 1, 'TXN1001', 499.99, '123456789', '4111111111111111', '2025-12-31', '123', 'topxuxa'),
    (2, 1, 2, 'TXN1002', 59.99, '987654321', '4111111111111112', '2026-12-31', '456', 'topxuxa2');

INSERT INTO Order_Product (id, order_id, product_id,quantity) VALUES
    (1, 1, 1 ,1),
    (2, 1, 2 ,1),
    (3, 2, 3, 1);
    
INSERT INTO "password_reset_tokens" (email, token, created_at) 
VALUES ('rafa@gmail.com', '5f4e7fdc19c37882511ab68f1ff3cfd3', NOW());

SELECT SETVAL('"User_id_seq"', (SELECT MAX(id) FROM "User"));
SELECT SETVAL('Admin_id_seq', (SELECT MAX(id) FROM Admin));
SELECT SETVAL('Category_id_seq', (SELECT MAX(id) FROM Category));
SELECT SETVAL('"Type_id_seq"', (SELECT MAX(id) FROM "Type"));
SELECT SETVAL('Product_id_seq', (SELECT MAX(id) FROM Product));
SELECT SETVAL('Product_Images_id_seq', (SELECT MAX(id) FROM Product_Images));
SELECT SETVAL('"Order_id_seq"', (SELECT MAX(id) FROM "Order"));
SELECT SETVAL('Transaction_id_seq', (SELECT MAX(id) FROM Transaction));
SELECT SETVAL('Order_Product_id_seq', (SELECT MAX(id) FROM Order_Product));
SELECT SETVAL('Notification_id_seq', (SELECT MAX(id) FROM Notification));
SELECT SETVAL('Notification_User_id_seq', (SELECT MAX(id) FROM Notification_User));
