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
  'consoles', 
  'video games', 
  'controllers'
);

CREATE TYPE order_status AS ENUM(
  'processing', 
  'shipped', 
  'delivered'
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
    remember_token VARCHAR(255)
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

CREATE TABLE Product (
    id SERIAL PRIMARY KEY,
    category_id INT NOT NULL ,
    name TEXT NOT NULL,
    image TEXT,
    description TEXT,
    quantity INT CHECK (quantity >= 0),
    price NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    discount_percent NUMERIC(5, 2) CHECK (discount_percent >= 0 AND discount_percent <= 100),
    FOREIGN KEY (category_id) REFERENCES Category(id) ON UPDATE CASCADE
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
    admin_id INT NOT NULL,
    reason TEXT NOT NULL,
    description TEXT,
    FOREIGN KEY (review_id) REFERENCES Review(id) ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES "User"(id) ON UPDATE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES Admin(id) ON UPDATE CASCADE
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

-- trigger09
CREATE OR REPLACE FUNCTION enforce_own_review_modification() RETURNS TRIGGER AS $$
BEGIN
    IF OLD.user_id != current_setting('app.current_user_id')::int THEN
        RAISE EXCEPTION 'A user can only edit or delete their own reviews';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_own_review_modification ON Review;
CREATE TRIGGER trg_own_review_modification
BEFORE UPDATE OR DELETE ON Review
FOR EACH ROW EXECUTE PROCEDURE enforce_own_review_modification();





INSERT INTO Category (id, type) VALUES
    (1, 'consoles'),
    (2, 'video games'),
    (3, 'controllers');

INSERT INTO Product (id, category_id, name, image, description, quantity, price, discount_percent) VALUES
    (1, 1, 'PlayStation 5', '/dbimages/pg5.jpg', 'Next-gen gaming console', 50, 499.99, 10),
    (2, 2, 'EA FC 25', '/dbimages/eafc25.jpg', 'Cancer game', 100, 59.99, 20),
    (3, 2, 'Fifa Street', '/dbimages/fifastreet.jpg', 'Goat game', 100, 19.99, 0),
    (4, 2, 'League of Legnds', '/dbimages/LOL.jpeg', 'Virgin game', 10, 9.99, 0),
    (5, 3, 'DualSense Controller', '/dbimages/dualsense.jpg', 'PS5 Wireless Controller', 200, 69.99, 15);

INSERT INTO "User" (id, username, password, name, email) VALUES
    (1, 'Lopez', '$2y$10$AyqmTcuuDudCvz5A.MrEcuQ0eFsS0vY4vuW3tYFeRnlOB6ZtbF.ay', 'Rafael Augusto', 'rafa@gmail.com');
   
INSERT INTO Admin (id, name, email, password) VALUES
    (1,'rabeira' ,'admin@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'); 

INSERT INTO "Order" (id, user_id, tracking_number, status, buy_date, estimated_delivery_date) VALUES
    (1, 1, 'TRK123456', 'processing', '2024-01-01', '2024-01-05'),
    (2, 1, 'TRK654321', 'shipped', '2024-01-02', '2024-01-06');

INSERT INTO Transaction (id, user_id, order_id, code, price, NIF, credit_card_number, credit_card_exp_date, credit_card_cvv) VALUES
    (1, 1, 1, 'TXN1001', 499.99, '123456789', '4111111111111111', '2025-12-31', '123'),
    (2, 1, 2, 'TXN1002', 59.99, '987654321', '4111111111111112', '2026-12-31', '456');

INSERT INTO Order_Product (id, order_id, product_id,quantity) VALUES
    (1, 1, 1 ,1),
    (2, 1, 2 ,1),
    (3, 2, 3, 1);
    
INSERT INTO "password_reset_tokens" (email, token, created_at) 
VALUES ('rafa@gmail.com', '5f4e7fdc19c37882511ab68f1ff3cfd3', NOW());

SELECT SETVAL('"User_id_seq"', (SELECT MAX(id) FROM "User"));
SELECT SETVAL('Admin_id_seq', (SELECT MAX(id) FROM Admin));
SELECT SETVAL('Category_id_seq', (SELECT MAX(id) FROM Category));
SELECT SETVAL('Product_id_seq', (SELECT MAX(id) FROM Product));
SELECT SETVAL('"Order_id_seq"', (SELECT MAX(id) FROM "Order"));
SELECT SETVAL('Transaction_id_seq', (SELECT MAX(id) FROM Transaction));
SELECT SETVAL('Order_Product_id_seq', (SELECT MAX(id) FROM Order_Product));
