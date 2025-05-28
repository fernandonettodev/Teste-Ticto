
# 📌 Teste para Vaga de Desenvolvedor Back-end Senior (PHP / Laravel) – Ticto

## 🧾 Sobre o Projeto

Este é um projeto de teste técnico desenvolvido para a vaga de Desenvolvedor Back-end Senior da **Ticto**. O sistema simula um registro de ponto eletrônico para funcionários via navegador, com diferentes níveis de acesso e funcionalidades.

---

## 🚀 Tecnologias Utilizadas

- **PHP** com **Laravel 12** (última versão estável)
- **MySQL** com Engine InnoDB
- **Laravel Sail** para ambientação via Docker
- **Blade** para a interface front-end

---

## ⚙️ Instalação e Uso

> **Pré-requisitos:** Docker e Docker Compose instalados.

### 🔧 Setup rápido:

```bash
# Clone o repositório
git clone https://github.com/fernandonettodev/Teste-Ticto.git
cd Teste-Ticto

# Copie o arquivo de ambiente
cp .env.example .env

# Instale as dependências PHP
./vendor/bin/sail composer install

# Gere a chave da aplicação
./vendor/bin/sail artisan key:generate

# Rode as migrations
./vendor/bin/sail artisan migrate

# Suba os containers com Sail
./vendor/bin/sail up -d

# Acesse no navegador
http://localhost
```

> **Observação:** Caso prefira, pode executar todos os comandos diretamente com `php artisan` e utilizar o servidor embutido do PHP, sem o Docker/Sail será necessario cadastrar o .env com os dados do Mysql e gerar a artisan:key. 

O Sail foi utilizado para acelerar o setup do banco de dados MySQL e garantir um ambiente consistente.

---

## 🗂️ Funcionalidades

### 👨‍💼 Funcionário

- Autenticação (login)
- Registro de ponto com um único botão
- Alteração de senha

### 🧑‍💼 Administrador

> **Acesso:** [http://localhost/admin/login](http://localhost/admin/login)  
> **Email:** `admin@admin.com`  
> **Senha:** `123456`

O administrador possui as seguintes funcionalidades:

- Gerenciamento completo (CRUD) dos funcionários cadastrados;
- Visualização detalhada dos registros de ponto de todos os funcionários;
- Filtro avançado de registros por intervalo de datas para análise;
- Relatórios de registros de ponto, com dados completos para controle;
- Cada funcionário está vinculado ao administrador responsável pelo seu cadastro, garantindo segurança e organização.

---

## 🧍 Cadastro de Usuários

Os seguintes dados são obrigatórios:

- Nome completo
- CPF (com validação de duplicidade e formato)
- E-mail
- Senha
- Cargo
- Data de nascimento
- CEP (consulta automática via [ViaCEP](https://viacep.com.br))
- Endereço completo (preenchido automaticamente)

---

## 🧩 Requisitos Técnicos

- Uso obrigatório de **Migrations** e **Eloquent** para manipulação de dados (exceto no relatório especial)
- Relacionamentos e índices corretamente definidos no banco
- Uso de **SQL puro** em relatório específico (ver abaixo)

---

## 📊 Relatório Especial (SQL Puro)

Uma listagem especial deve ser gerada usando **somente SQL** (sem Eloquent), contendo:

- ID do Registro
- Nome do Funcionário
- Cargo
- Idade
- Nome do Gestor
- Data e Hora do Registro (com segundos)

> A avaliação deste relatório focará na qualidade do `SELECT`, nos `JOINs`, filtros por data e uso apropriado dos relacionamentos.

---

## ✅ Observações Finais

- Este projeto visa demonstrar boas práticas com Laravel, estrutura de código, domínio de banco de dados e uso de tecnologias modernas.
- O Laravel Sail foi escolhido para garantir um ambiente consistente e facilitar a integração com o MySQL.
- Foi feito uma refatoração do front-end do Livewire para o Blade, por ser mais simples e mais direto.