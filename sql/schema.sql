DROP SCHEMA IF EXISTS rabeira CASCADE;
CREATE SCHEMA IF NOT EXISTS rabeira;
SET search_path TO rabeira;


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
--DROP FUNCTION IF EXISTS prevent_admin_purchase CASCADE;
DROP FUNCTION IF EXISTS verify_purchase_for_review CASCADE;
DROP FUNCTION IF EXISTS validate_promotion_release_date CASCADE;
DROP FUNCTION IF EXISTS restrict_out_of_stock_cart_addition CASCADE;
DROP FUNCTION IF EXISTS enforce_single_review_per_product CASCADE;
DROP FUNCTION IF EXISTS enforce_own_wishlist CASCADE;
DROP FUNCTION IF EXISTS enforce_own_shopping_cart CASCADE;
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
    email TEXT UNIQUE NOT NULL
);
CREATE TABLE Admin (
    id SERIAL  PRIMARY KEY,
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

-- trigger02
--CREATE OR REPLACE FUNCTION prevent_admin_purchase() RETURNS TRIGGER AS $$
--BEGIN
--    IF (SELECT is_admin FROM Admin WHERE id = NEW.admin_id) THEN
--        RAISE EXCEPTION 'Administrators cannot purchase products';
--    END IF;
--    RETURN NEW;
--END
--$$ LANGUAGE plpgsql;
--DROP TRIGGER IF EXISTS trg_admin_purchase_prevention ON Shopping_Cart;
--CREATE TRIGGER trg_admin_purchase_prevention
--BEFORE INSERT ON Shopping_Cart
--FOR EACH ROW EXECUTE PROCEDURE prevent_admin_purchase();                           


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

-- trigger04
--CREATE OR REPLACE FUNCTION validate_promotion_release_date() RETURNS TRIGGER AS $$
--BEGIN
--    IF NEW.discount_percent > 0 AND NEW.promotion_date > CURRENT_DATE THEN
--        RAISE EXCEPTION 'Promotion release date cannot be in the future';
 --   END IF;
 --   RETURN NEW;
--END;
--$$ LANGUAGE plpgsql;

--DROP TRIGGER IF EXISTS trg_validate_promotion_release_date ON Product;
--CREATE TRIGGER trg_validate_promotion_release_date
--BEFORE UPDATE ON Product
--FOR EACH ROW EXECUTE FUNCTION validate_promotion_release_date();

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

-- trigger07
--CREATE OR REPLACE FUNCTION enforce_own_wishlist() RETURNS TRIGGER AS $$
--BEGIN
--    IF NEW.user_id != current_setting('app.current_user_id')::int THEN
--        RAISE EXCEPTION 'A user can only add products to their own wishlist';
--    END IF;
--    RETURN NEW;
--END
--$$ LANGUAGE plpgsql;

--DROP TRIGGER IF EXISTS trg_own_wishlist ON Wishlist;
--CREATE TRIGGER trg_own_wishlist
--BEFORE INSERT ON Wishlist
--FOR EACH ROW EXECUTE PROCEDURE enforce_own_wishlist();


-- trigger08
--CREATE OR REPLACE FUNCTION enforce_own_shopping_cart() RETURNS TRIGGER AS $$
--BEGIN
--    IF NEW.user_id != current_user_id() THEN
--        RAISE EXCEPTION 'A user can only add products to their own shopping cart';
--    END IF;
--    RETURN NEW;
--END
--$$ LANGUAGE plpgsql;

--DROP TRIGGER IF EXISTS trg_own_shopping_cart ON Shopping_Cart;
--CREATE TRIGGER trg_own_shopping_cart
--BEFORE INSERT ON Shopping_Cart
--FOR EACH ROW EXECUTE PROCEDURE enforce_own_shopping_cart();

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

-- trigger10
CREATE OR REPLACE FUNCTION enforce_user_related_notifications() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.user_id != current_setting('app.current_user_id')::int THEN
        RAISE EXCEPTION 'A user can only receive notifications related to their own actions or orders';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_user_related_notifications ON Notification;
CREATE TRIGGER trg_user_related_notifications
BEFORE INSERT ON Notification
FOR EACH ROW EXECUTE PROCEDURE enforce_user_related_notifications();

-- trigger11
CREATE OR REPLACE FUNCTION restrict_out_of_stock_wishlist_addition() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT quantity FROM Product WHERE id = NEW.product_id) <= 0 THEN
        RAISE EXCEPTION 'This product is out of stock and cannot be added to the wishlist';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_out_of_stock_wishlist_addition ON Wishlist;
CREATE TRIGGER trg_out_of_stock_wishlist_addition
BEFORE INSERT ON Wishlist
FOR EACH ROW EXECUTE PROCEDURE restrict_out_of_stock_wishlist_addition();


-- trigger 12
CREATE OR REPLACE FUNCTION restrict_address_change_for_shipped_orders() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT status FROM "Order" WHERE shipping_address_id = OLD.id) = 'shipped' THEN
        RAISE EXCEPTION 'Cannot change the shipping address for shipped orders';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_address_change_restriction ON Shipping_Address;
CREATE TRIGGER trg_address_change_restriction
BEFORE UPDATE ON Shipping_Address
FOR EACH ROW EXECUTE PROCEDURE restrict_address_change_for_shipped_orders();

-- Populate Category table
INSERT INTO Category (id, type) VALUES
    (1, 'consoles'),
    (2, 'video games'),
    (3, 'controllers');

-- Populate Product table
INSERT INTO Product (id, category_id, name, image, description, quantity, price, discount_percent) VALUES
    (1, 1, 'PlayStation 5', '/dbimages/pg5.jpg', 'Next-gen gaming console', 50, 499.99, 10),
    (2, 2, 'EA FC 25', '/dbimages/eafc25.jpg', 'Cancer game', 100, 59.99, 20),
    (3, 2, 'Fifa Street', '/dbimages/fifastreet.jpg', 'Goat game', 100, 19.99, 0),
    (4, 2, 'League of Legnds', '/dbimages/LOL.jpeg', 'Virgin game', 10, 9.99, 0),
    (5, 3, 'DualSense Controller', '/dbimages/dualsense.jpg', 'PS5 Wireless Controller', 200, 69.99, 15);

INSERT INTO Admin (id, email, password) VALUES
    (1, 'admin@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W');  -- Ensure Admin table structure includes necessary fields