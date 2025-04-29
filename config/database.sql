CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('admin', 'normal') NOT NULL DEFAULT 'normal',
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES
('admin', SHA2('admin123', 256), 'admin'),
('joao', SHA2('joao123', 256), 'normal'),
('maria', SHA2('maria123', 256), 'normal'),
('pedro', SHA2('pedro123', 256), 'normal'),
('ana',   SHA2('ana123', 256), 'normal');

CREATE TABLE pacientes (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           nome VARCHAR(100) NOT NULL,
                           email VARCHAR(100) NOT NULL,
                           telefone VARCHAR(20) NOT NULL,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE consultas (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           paciente_id INT NOT NULL,
                           data DATE NOT NULL,
                           hora TIME NOT NULL,
                           observacoes TEXT,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
);