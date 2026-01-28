# 🏭 Mini Controle de Fábrica de Softwares — Backend (API)

API REST desenvolvida em **Laravel** para controle de **clientes**, **projetos**, **lançamentos (timesheet)** e cálculo de **lucratividade** (dashboard).

---

## ✅ O que esta API faz

- CRUD de **Clientes**
- CRUD de **Projetos**
- CRUD de **Lançamentos (Timesheet)**
- **Dashboard de Lucratividade** por projeto e período:
  - horas totais
  - custo total
  - receita do contrato
  - margem bruta (R$ e %)
  - break-even (em horas)
  - resumo por tipo (corretiva/evolutiva/implantacao/legislativa)

---

## 🧰 Tecnologias

- PHP 8+
- Laravel 12
- MySQL
- API REST

---

## ▶️ Como rodar localmente

### Pré-requisitos
- PHP 8+
- Composer
- MySQL (XAMPP/WAMP/Laragon ou similar)

### Passo a passo

```bash
# entrar na pasta do backend
cd mini-fabrica-softwares/backend

# instalar dependências
composer install

# configurar ambiente
cp .env.example .env

# gerar chave da aplicação
php artisan key:generate

# (no .env) configure o banco:
# DB_DATABASE=...
# DB_USERNAME=...
# DB_PASSWORD=...

# rodar migrations
php artisan migrate

# iniciar servidor
php artisan serve

API disponível em:
http://127.0.0.1:8000


Autor
Antônio Lima Barbosa Bisneto
Projeto desenvolvido para fins de estudo e avaliação técnica.