-- Populate Category table
INSERT INTO Category (id, type) VALUES
    (1, 'consoles'),
    (2, 'video games'),
    (3, 'controllers');
-- Populate Admin table with additional necessary columns if needed
INSERT INTO Admin (id, email, password) VALUES
    (1, 'admin@example.com', 'adminpass');  -- Ensure Admin table structure includes necessary fields
-- Populate User table
INSERT INTO "User" (id, username, password, name, email) VALUES
    (1, 'john_doe', 'password123', 'John Doe', 'john@example.com'),
    (2, 'jane_smith', 'password456', 'Jane Smith', 'jane@example.com'),
    (3, 'alice_jones', 'password789', 'Alice Jones', 'alice@example.com');

-- Populate Product table
INSERT INTO Product (id, category_id, name, image, description, quantity, price, discount_percent) VALUES
    (1, 1, 'PlayStation 5', 'ps5.jpg', 'Next-gen gaming console', 50, 499.99, 10),
    (2, 2, 'Cyberpunk 2077', 'cyberpunk.jpg', 'Open-world RPG game', 100, 59.99, 20),
    (3, 3, 'DualSense Controller', 'dualsense.jpg', 'PS5 Wireless Controller', 200, 69.99, 15);

INSERT INTO "Order" (id, user_id, tracking_number, status, buy_date, estimated_delivery_date) VALUES
    (1, 1, 'TRK123456', 'processing', '2024-01-01', '2024-01-05'),
    (2, 1, 'TRK654321', 'shipped', '2024-01-02', '2024-01-06'),
    (3, 1, 'TRK654323', 'shipped', '2024-01-03', '2024-01-05');

INSERT INTO Transaction (id, user_id, order_id, code, price, NIF, credit_card_number, credit_card_exp_date, credit_card_cvv) VALUES
    (1, 1, 1, 'TXN1001', 499.99, '123456789', '4111111111111111', '2025-12-31', '123'),
    (2, 1, 2, 'TXN1002', 59.99, '987654321', '4111111111111112', '2026-12-31', '456'),
    (3, 1, 3, 'TXN1003', 59.99, '987654321', '4111111111111112', '2026-12-31', '456');

INSERT INTO Order_Product (id, order_id, product_id,quantity) VALUES
    (1, 1, 1 ,1),
    (2, 1, 2 ,1),
    (3, 2, 3, 1),
    (4, 3, 3, 1);

-- Populate Wishlist table
INSERT INTO Wishlist (id, user_id, product_id) VALUES
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3);

-- Populate Review table ensuring valid user and product IDs
INSERT INTO Review (id, user_id, product_id, description, rating) VALUES
    (1, 1, 1, 'Great console, very fast!', 5.0),
    (2, 2, 3, 'Controller feels nice and responsive.', 4.5),
    (3, 1, 2, 'Game is fun but buggy.', 3.0);  -- Changed user_id to 1 to reflect a valid purchase

-- Populate Report table with existing reviews
INSERT INTO Report (id, review_id, user_id, admin_id, reason, description) VALUES
    (1, 1, 2, 1, 'Inappropriate language', 'The review contains some offensive terms.'),
    (2, 3, 1, 1, 'Spam', 'The review seems to be promotional.');

-- Populate Notification table
INSERT INTO Notification (id, description, viewed, date) VALUES
    (1, 'Your order has been shipped!', FALSE, '2024-01-02'),
    (2, 'New product added to your wishlist!', TRUE, '2024-01-03');

-- Populate Notification_User table
INSERT INTO Notification_User (id, user_id, notification_id) VALUES
    (1, 1, 1),
    (2, 2, 2);

-- Populate Shipping_Address table
INSERT INTO Shipping_Address (id, postal_code, address, location, country, is_primary) VALUES
    (1, '10001', '123 Main St', 'New York', 'USA', TRUE),
    (2, '90210', '456 Elm St', 'Los Angeles', 'USA', TRUE),
    (3, '94101', '789 Maple Ave', 'San Francisco', 'USA', FALSE);

-- Populate User_Shipping table
INSERT INTO User_Shipping (id, user_id, shipping_id) VALUES
    (1, 1, 1),
    (2, 2, 2),
    (3, 3, 3);


