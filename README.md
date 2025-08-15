# Sistema de Gestão de Compras

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12.x">
  <img src="https://img.shields.io/badge/Filament-3.x-F59E0B?style=for-the-badge" alt="Filament 3.x">
  <img src="https://img.shields.io/badge/PostgreSQL-16-336791?style=for-the-badge&logo=postgresql" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker" alt="Docker Ready">
</p>



## 🎯 Sobre o Projeto

O **Sistema de Gestão de Compras** é uma aplicação web robusta, desenvolvida como um *case* de estudo para demonstrar a implementação de um fluxo de trabalho administrativo complexo com tecnologias modernas. O projeto simula um processo de requisição e aprovação de compras, com foco em segurança, integridade de dados e uma experiência de usuário limpa e eficiente.

A arquitetura foi projetada com base em princípios de modularidade e escalabilidade, tornando esta aplicação uma base sólida que pode ser facilmente customizada para diversas outras soluções de gerenciamento de processos de negócio (BPM).

---

## ✨ Features

- **Dashboard Gerencial:** Widgets com estatísticas em tempo real e lista de atividades recentes para tomada de decisão rápida.
- **Controle de Acesso (ACL):** Sistema de permissões baseado em papéis (Admin/Usuário) para proteger rotas e ações críticas.
- **Gestão de Ciclo de Vida de Usuários:** Sistema de status **Ativo/Inativo**, prevenindo a exclusão de usuários com histórico e bloqueando o acesso de contas desativadas.
- **Fluxo de Compras:** Criação de solicitações com múltiplos itens, rastreamento de status (`Pendente`, `Aprovada`, `Efetivada`) e associação com solicitantes e aprovadores.
- **Integridade de Dados Garantida:** Lógica de negócio implementada na aplicação e reforçada por *constraints* de chave estrangeira no banco de dados, com notificações amigáveis para o usuário ao tentar executar ações inválidas (ex: excluir um item em uso).
- **CRUDS Completos:** Gerenciamento completo de entidades de suporte como Institutos, Coordenações, Categorias e Materiais.

---

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 12 (PHP 8.3)
- **Painel Administrativo:** Filament PHP v3
- **Banco de Dados:** PostgreSQL 16
- **Ambiente de Desenvolvimento:** Docker com Laravel Sail
- **Autenticação:** Laravel Fortify (via Filament)
- **Frontend:** Tailwind CSS, Alpine.js (via Filament)

---

## 🚀 Instalação e Execução

O ambiente de desenvolvimento é totalmente containerizado com Docker, garantindo uma configuração rápida e consistente.

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/jovi0503/controle_de_compras/
    cd nome-do-projeto
    ```

2.  **Configure o Ambiente:**
    ```bash
    cp .env.example .env
    ```

3.  **Inicie os Contêineres:**
    ```bash
    ./vendor/bin/sail up -d
    ```

4.  **Instale as Dependências:**
    ```bash
    ./vendor/bin/sail composer install
    ```

5.  **Configure a Aplicação:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate --seed # Executa as migrations e os seeders
    ./vendor/bin/sail artisan storage:link
    ```

6.  **Acesso:**
    - **URL:** `http://localhost`
    - **Painel Admin:** `http://localhost/admin`
    - **Usuário Admin:** `admin@exemplo.com` | `password` *(Sugestão para seu `UserSeeder`)*

---

## 📜 Licença

Distribuído sob a licença MIT. Veja `LICENSE` para mais informações.

---

## 👤 Contato

**João Vitor Santana**

[![LinkedIn](https://img.shields.io/badge/linkedin-%230077B5.svg?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/jovii/)
