# Agenda-de-Pacientes

### Abner Yohan Sato - RA 2459299

---

## ✅ Requisitos

- PHP 8+ (recomendado via [XAMPP](https://www.apachefriends.org/))
- MySQL/MariaDB
- Navegador web atualizado (Chrome, Firefox, etc.)
- Editor de código (recomendado: VSCode ou PhpStorm)

---

## 🛠️ Instalação

1. Clone ou baixe este repositório e coloque na pasta do seu XAMPP:
   ```
   C:/xampp/htdocs/Agenda-de-Pacientes
   ```

2. Inicie os serviços Apache e MySQL no painel do XAMPP.

3. Crie o banco de dados:
  - Acesse: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
  - Crie um banco com o nome:
    ```
    agenda_db
    ```
  - Execute o script SQL localizado em:
    ```
    /config/database.sql
    ```

4. Configure o acesso ao banco no arquivo:
   ```
   /config/config.php
   ```
   Altere se necessário:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'agenda_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('BASE_URL', '/Agenda-de-Pacientes');
   ```

5. Configure o Apache para reconhecer o domínio local:

  - Edite o arquivo `C:\Windows\System32\drivers\etc\hosts`:
    ```
    127.0.0.1 agenda.local
    ```

  - Configure o VirtualHost no `httpd-vhosts.conf` do Apache:
    ```apache
    <VirtualHost *:80>
        ServerAdmin admin@agenda.local
        DocumentRoot "C:/xampp/htdocs/Agenda-de-Pacientes"
        ServerName agenda.local
        <Directory "C:/xampp/htdocs/Agenda-de-Pacientes">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```

  - Reinicie o Apache.

---

## 🌐 Acesso

Acesse o sistema pelo navegador:
```text
http://agenda.local/Agenda-de-Pacientes/login/index
```

Usuário padrão criado via SQL:
- **Login:** admin
- **Senha:** admin123

---

## 🧰 Estrutura de Diretórios

```
Agenda-de-Pacientes/
├── app/
│   ├── controllers/         # Lógica de controle
│   ├── models/              # Acesso a dados
│   └── views/               # HTML e apresentação
├── config/                 # Configurações do sistema e banco
├── core/                   # Classes base (Model, Controller)
├── public/                 # Pasta pública (caso futuro)
├── .htaccess               # Regras de roteamento
├── index.php               # Front controller
└── test.php                # Arquivo de teste isolado
```

---

## 🔐 Autenticação

- Login com validação via PHP
- Sessões para manter o estado do usuário
- Perfis de usuário com controle de acesso:
  - `admin`
  - `normal`

---

## 📊 Funcionalidades - Etapa 1

- Autenticação de usuários (login/logout)
- Controle de acesso por tipo de usuário
- Interface com feedback (erros/sucesso)
- Formulários com validação no PHP
- Separacção MVC (Model-View-Controller)
- Sistema pronto para expansão com CRUDs

---

## 📂 Estrutura do Banco

Execute em `/config/database.sql`:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'normal') NOT NULL DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role)
VALUES ('admin', SHA2('admin123', 256), 'admin');

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
```

---

## 🏢 Baseado nos Repositórios

- [web-servidor-aula-2](https://github.com/drantunes/web-servidor-aula-2)
- [web-servidor-aula-5](https://github.com/drantunes/web-servidor-aula-5)
- [web-servidor-aula-7](https://github.com/drantunes/web-servidor-aula-7)
- [web-servidor-aula-9](https://github.com/drantunes/web-servidor-aula-9)
- [web-servidor-aula-11](https://github.com/drantunes/web-servidor-aula-11)

---

## 🚀 Execução em Outra IDE

1. Abra o projeto em qualquer IDE (VSCode, PhpStorm, etc.)
2. Certifique-se que o Apache está servindo a raiz correta
3. Use a URL completa ou o host configurado (agenda.local)
4. Verifique que o `.htaccess` está ativo e com `mod_rewrite` habilitado
5. Pronto para desenvolver e testar
