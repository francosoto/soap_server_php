CREATE TABLE IF NOT EXISTS users_db.users (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username varchar(100) NOT NULL,
	password varchar(100) NOT NULL,
	email varchar(100) NULL,
	enabled TINYINT NOT NULL DEFAULT 1,
	UNIQUE KEY unique_username (username)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
