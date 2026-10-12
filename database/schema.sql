-- ============================================================
-- Business Subscription Revenue Tracker (SubTrack)
-- MySQL schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS subtrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE subtrack;

-- ------------------------------------------------------------
-- users: one row per business owner / admin account
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120)        NOT NULL,
    email         VARCHAR(190)        NOT NULL UNIQUE,
    password_hash VARCHAR(255)        NOT NULL,
    business_name VARCHAR(150)        NOT NULL,
    created_at    DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- customers: each business's end-customers (subscribers)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS customers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED        NOT NULL,
    name        VARCHAR(150)        NOT NULL,
    email       VARCHAR(190)        NOT NULL,
    phone       VARCHAR(30)         NULL,
    created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_customers_user (user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- plans: subscription plans a business offers
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS plans (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED        NOT NULL,
    plan_name      VARCHAR(100)        NOT NULL,
    price          DECIMAL(10,2)       NOT NULL,
    billing_cycle  ENUM('monthly','annual') NOT NULL DEFAULT 'monthly',
    description    VARCHAR(255)        NULL,
    created_at     DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_plans_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_plans_user (user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- subscriptions: links a customer to a plan
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS subscriptions (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED        NOT NULL,
    customer_id   INT UNSIGNED        NOT NULL,
    plan_id       INT UNSIGNED        NOT NULL,
    start_date    DATE                NOT NULL,
    renewal_date  DATE                NOT NULL,
    status        ENUM('active','cancelled') NOT NULL DEFAULT 'active',
    cancelled_at  DATE                NULL,
    created_at    DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_subscriptions_user     FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
    CONSTRAINT fk_subscriptions_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    CONSTRAINT fk_subscriptions_plan     FOREIGN KEY (plan_id)     REFERENCES plans(id)     ON DELETE RESTRICT,
    INDEX idx_subscriptions_user (user_id),
    INDEX idx_subscriptions_status (status),
    INDEX idx_subscriptions_renewal (renewal_date)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- payments: every recurring charge collected for a subscription
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS payments (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subscription_id  INT UNSIGNED        NOT NULL,
    amount           DECIMAL(10,2)       NOT NULL,
    payment_date     DATE                NOT NULL,
    payment_status   ENUM('paid','pending','failed') NOT NULL DEFAULT 'paid',
    CONSTRAINT fk_payments_subscription FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    INDEX idx_payments_subscription (subscription_id),
    INDEX idx_payments_date (payment_date)
) ENGINE=InnoDB;
