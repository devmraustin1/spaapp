CREATE TABLE users
(
    id              INT(10) PRIMARY KEY AUTO_INCREMENT,
    login           VARCHAR(30),
    password_hashed VARCHAR(255)
);

CREATE TABLE operations
(
    id        INT(10) PRIMARY KEY AUTO_INCREMENT,
    is_income BOOL,
    amount    INT,
    comment   TEXT
);