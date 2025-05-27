# Agenda de Pacientes

Sistema de agendamento de pacientes desenvolvido em PHP, seguindo o padrão MVC, com autenticação e gerenciamento de consultas.

### Desenvolvedores
- Abner Yohan Sato - RA 2459299
- Ricardo Koji Takenaka - RA (Adicionar RA)
- Lucas Eduardo Vidal - RA (Adicionar RA)

---

## ✅ Requisitos Essenciais

- **PHP 8.0+**
- **Servidor Web** com suporte a PHP (Apache, Nginx, etc.)
    - Módulo `mod_rewrite` (ou equivalente) habilitado para URLs amigáveis.
- **MySQL (ou MariaDB)** como sistema de gerenciamento de banco de dados.
- **Composer** para gerenciamento de dependências PHP.
- **Navegador Web** moderno (Chrome, Firefox, Edge, etc.).
- (Opcional) Editor de código como VSCode ou PhpStorm.

---

## 🛠️ Guia de Instalação

1.  **Clonar o Repositório:**
    Clone ou baixe este repositório para o diretório raiz do seu servidor web (ex: `htdocs`, `www`, `public_html`).
    ```bash
    git clone https://SUA_URL_DE_REPOSITORIO/Agenda-de-Pacientes.git
    cd Agenda-de-Pacientes
    ```
    Ou coloque os arquivos baixados em uma pasta, por exemplo: `C:/xampp/htdocs/Agenda-de-Pacientes` (para XAMPP) ou `/var/www/html/Agenda-de-Pacientes` (para Linux).

2.  **Instalar Dependências com Composer:**
    Certifique-se de ter o [Composer](https://getcomposer.org/) instalado globalmente. Navegue até a raiz do projeto e execute:
    ```bash
    composer install
    ```
    Isso instalará as dependências PHP necessárias e configurará o autoloader.

3.  **Configurar o Banco de Dados:**
    * Acesse seu gerenciador de banco de dados (ex: [phpMyAdmin](http://localhost/phpmyadmin), DBeaver, MySQL Workbench).
    * Crie um novo banco de dados. O nome padrão usado no projeto é:
        ```
        agenda_db
        ```

    * Importe a estrutura e os dados iniciais executando o script SQL localizado em:
        ```
        /config/database.sql
        ```


4.  **Configurar a Aplicação:**
    * Copie o arquivo `/config/config.example.php` para `/config/config.php` (se `config.php` não existir ou se você quiser começar do zero).
    * Edite o arquivo `/config/config.php` com as suas configurações:
        ```php
        <?php

        // Configurações do Banco de Dados
        define('DB_HOST', 'localhost'); // Host do banco de dados
        define('DB_NAME', 'agenda_db');   // Nome do banco de dados
        define('DB_USER', 'root');      // Usuário do banco de dados
        define('DB_PASS', '');          // Senha do banco de dados

        // URL Base da Aplicação
        // Se o projeto está na raiz do seu domínio (ex: [http://agenda.local/](http://agenda.local/)), deixe como '/'.
        // Se estiver em uma subpasta (ex: http://localhost/Agenda-de-Pacientes/), use '/Agenda-de-Pacientes'.
        define('BASE_URL', '/Agenda-de-Pacientes'); // Ajuste conforme necessário

        // Caminho raiz da aplicação no servidor (normalmente detectado automaticamente)
        define('APP_ROOT', dirname(__DIR__)); // Geralmente não precisa mudar

        // Outras configurações...
        ```

5.  **Configurar o Servidor Web (URLs Amigáveis):**
    Para que as URLs amigáveis (ex: `/login/index` em vez de `/index.php?url=login/index`) funcionem, seu servidor web precisa estar configurado para redirecionar todas as requisições para o `index.php` principal, a menos que o arquivo ou diretório solicitado exista fisicamente.

    * **Apache:**
      Certifique-se de que o módulo `mod_rewrite` está habilitado. O arquivo `.htaccess` na raiz do projeto já deve conter as regras necessárias. Um exemplo de `.htaccess` básico:
        ```apache
        RewriteEngine On

        # Se o arquivo ou diretório solicitado não existir fisicamente
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d

        # Redireciona para index.php, passando a URL original
        RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
        ```
      **Para configuração de VirtualHost (Recomendado para Desenvolvimento Local):**
      Se você deseja usar um nome de host local como `http://agenda.local`, você precisará:
        1.  Editar seu arquivo `hosts`:
            * Windows: `C:\Windows\System32\drivers\etc\hosts`
            * Linux/macOS: `/etc/hosts`
              Adicione a linha:
            ```
            127.0.0.1 agenda.local
            ```

        2.  Configurar um VirtualHost no seu Apache. Exemplo para `httpd-vhosts.conf` (XAMPP) ou configuração de sites do Apache:
            ```apache
            <VirtualHost *:80>
                ServerAdmin admin@agenda.local
                DocumentRoot "CAMINHO_PARA_SUA_PASTA/Agenda-de-Pacientes" # Ex: "C:/xampp/htdocs/Agenda-de-Pacientes"
                ServerName agenda.local
                <Directory "CAMINHO_PARA_SUA_PASTA/Agenda-de-Pacientes">
                    Options Indexes FollowSymLinks
                    AllowOverride All
                    Require all granted
                </Directory>
                ErrorLog "logs/agenda.local-error.log"
                CustomLog "logs/agenda.local-access.log" common
            </VirtualHost>
            ```

            Lembre-se de reiniciar o Apache após as alterações.

    * **Nginx:**
      Uma configuração de exemplo para o bloco `server` no Nginx seria:
        ```nginx
        server {
            listen 80;
            server_name agenda.local; # Ou seu domínio/IP
            root /CAMINHO_PARA_SUA_PASTA/Agenda-de-Pacientes; # Ex: /var/www/html/Agenda-de-Pacientes
            index index.php index.html index.htm;

            location / {
                try_files $uri $uri/ /index.php?$query_string;
            }

            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php8.x-fpm.sock; # Ajuste para sua versão e configuração do PHP-FPM
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
            }

            location ~ /\.ht {
                deny all;
            }
        }
        ```
      Lembre-se de reiniciar o Nginx e o PHP-FPM após as alterações.

6.  **Permissões de Escrita (Opcional, se usar logs ou uploads):**
    Certifique-se de que o servidor web tem permissão de escrita no diretório de logs (se configurado) ou em pastas de upload, se a aplicação tiver essa funcionalidade. Para o log de erros do PHP (`php-error.log` na raiz do projeto), a pasta raiz precisa ser gravável pelo servidor.

---

## 🌐 Acesso à Aplicação

Após a configuração, acesse o sistema pelo navegador:

* Se configurou um VirtualHost como `agenda.local` e `BASE_URL` como `/` (ou vazio):
    ```text
    [http://agenda.local/login/index](http://agenda.local/login/index)
    ```
* Se estiver acessando via `localhost` em uma subpasta (ex: `Agenda-de-Pacientes`) e `BASE_URL` for `/Agenda-de-Pacientes`:
    ```text
    http://localhost/Agenda-de-Pacientes/login/index
    ```


**Usuário Padrão (criado via SQL):**
- **Login:** `admin`
- **Senha:** `admin123`

---

## 🧰 Estrutura de Diretórios Principal