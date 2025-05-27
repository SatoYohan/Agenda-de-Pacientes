# Agenda de Pacientes

Sistema de agendamento de pacientes desenvolvido em PHP, seguindo o padrão MVC, com autenticação e gerenciamento de consultas.

### Desenvolvedores
- Abner Yohan Sato - RA 2459299
- Ricardo Koji Takenaka - RA 2509857
- Lucas Eduardo Vidal - RA 2000881

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

Agenda-de-Pacientes/
├── app/                     # Contém a lógica principal da aplicação
│   ├── Controllers/         # Controladores (lógica de requisição/resposta)
│   ├── Models/              # Modelos (lógica de negócios e acesso a dados)
│   └── Views/               # Arquivos de apresentação (HTML, templates)
│       └── layouts/         # Layouts base das páginas
├── config/                  # Arquivos de configuração (banco de dados, aplicação)
├── core/                    # Classes base do framework MVC (Controller, Model)
├── public/                  # Deveria ser o DocumentRoot idealmente (contém assets públicos como CSS, JS, imagens)
│   └── css/
│       └── style.css        # CSS customizado
├── vendor/                  # Dependências gerenciadas pelo Composer (não versionar)
├── .htaccess                # Configurações do Apache para reescrita de URL (se aplicável)
├── composer.json            # Define as dependências do projeto para o Composer
├── composer.lock            # Registra as versões exatas das dependências instaladas
├── index.php                # Ponto de entrada da aplicação (Front Controller)
└── php-error.log            # Log de erros do PHP (se configurado)

*Nota sobre `public/`*: Idealmente, apenas o conteúdo da pasta `public/` (contendo `index.php` e assets) deveria ser acessível diretamente pelo navegador. Os outros diretórios (`app`, `core`, `config`, `vendor`) ficariam fora do `DocumentRoot` por segurança. A configuração atual com `DocumentRoot` na raiz do projeto é mais simples para iniciar, mas considere refatorar para um `DocumentRoot` em `public/` em um ambiente de produção.

---

## 🔐 Autenticação e Autorização

- Sistema de login/logout com validação de credenciais no backend.
- Uso de Sessões PHP para persistir o estado de autenticação do usuário.
- Controle de acesso baseado em perfis de usuário (roles):
  - `admin`: Acesso total ao sistema, incluindo gerenciamento de pacientes.
  - `normal`: Acesso limitado ao gerenciamento de suas próprias consultas (ou visualização, dependendo da implementação).

---

## 🌟 Funcionalidades Implementadas (Trabalho 2)

Este projeto implementa os seguintes requisitos:

- **PHP 8+ e Orientação a Objetos:** Código estruturado em classes, utilizando conceitos de OOP.
- **Composer e Autoload:** Gerenciamento de dependências e autoloading PSR-4 para organização do código.
- **Banco de Dados via PDO:** Interação com o banco de dados MySQL/MariaDB utilizando PDO para maior segurança e portabilidade.
- **Padrão MVC e Sistema de Rotas:**
    - Lógica de aplicação separada em Modelos, Visões e Controladores.
    - URLs amigáveis e transparentes gerenciadas por um sistema de roteamento simples no `index.php`.
- **Interface do Usuário:**
    - Interface responsiva utilizando Bootstrap 5.
    - Mensagens de feedback (sucesso, erro, informação) para o usuário.
    - Formulários com validação de dados no lado do servidor.
- **CRUDs:**
    - Gerenciamento completo de Pacientes (Criar, Ler, Atualizar, Excluir) para administradores.
    - Gerenciamento completo de Consultas (Criar, Ler, Atualizar, Excluir).

---

## 📂 Estrutura do Banco de Dados

O script para criação das tabelas e inserção de dados iniciais está em `/config/database.sql`.

**Tabelas Principais:**
- `users`: Armazena os dados dos usuários do sistema (login, senha HASHED, perfil).
- `pacientes`: Armazena os dados dos pacientes.
- `consultas`: Armazena os detalhes das consultas agendadas, relacionando-se com pacientes.

---

## 🚀 Execução e Desenvolvimento

1.  Siga o "Guia de Instalação".
2.  Para desenvolvimento, qualquer editor de código que suporte PHP é adequado (VSCode, PhpStorm, Sublime Text, etc.).
3.  Certifique-se de que seu servidor web está configurado para servir a partir da raiz correta do projeto e que o `mod_rewrite` (ou equivalente) está ativo.
4.  Acesse a URL configurada (ex: `http://agenda.local/` ou `http://localhost/Agenda-de-Pacientes/`).
5.  O arquivo `test.php` é um arquivo isolado para testes pontuais e não faz parte do fluxo principal da aplicação MVC.

---

## 🏛️ Baseado nos Repositórios (Inspiração)

- [web-servidor-aula-2](https://github.com/drantunes/web-servidor-aula-2)
- [web-servidor-aula-5](https://github.com/drantunes/web-servidor-aula-5)
- [web-servidor-aula-7](https://github.com/drantunes/web-servidor-aula-7)
- [web-servidor-aula-9](https://github.com/drantunes/web-servidor-aula-9)
- [web-servidor-aula-11](https://github.com/drantunes/web-servidor-aula-11)