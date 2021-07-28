CREATE TABLE Product
(
    id INT GENERATED,
    PRIMARY KEY (id)
);

CREATE TABLE Feature
(
    id         INT AUTO_INCREMENT NOT NULL,
    product_id INT DEFAULT NULL,
    PRIMARY KEY (id)
);

ALTER TABLE Feature
    ADD FOREIGN KEY (product_id) REFERENCES Product (id);

