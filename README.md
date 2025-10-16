# 🎫 Sistema Helpdesk

Sistema de gerenciamento de chamados e suporte técnico para o setor de TI, desenvolvido para facilitar a organização, acompanhamento e resolução de solicitações de usuários.

## 📋 Sobre o Projeto

O Sistema Helpdesk é uma solução completa para gestão de tickets de suporte técnico, permitindo que empresas organizem eficientemente o atendimento às demandas internas e externas do setor de TI. O sistema oferece controle total sobre abertura, acompanhamento e fechamento de chamados, com diferentes níveis de acesso e prioridades.

## ✨ Funcionalidades

### Gestão de Chamados
- ✅ Abertura de tickets pelos usuários
- ✅ Categorização por tipo de problema
- ✅ Sistema de prioridades (Baixa, Média, Alta, Urgente)
- ✅ Acompanhamento de status (Aberto, Em Andamento, Aguardando, Resolvido, Fechado)
- ✅ Atribuição de tickets para técnicos responsáveis
- ✅ Histórico completo de interações

### Gestão de Usuários
- ✅ Sistema de autenticação e autorização
- ✅ Perfis de acesso diferenciados:
  - **Usuário Comum**: Abertura e acompanhamento de seus próprios chamados
  - **Técnico**: Visualização e resolução de tickets atribuídos
  - **Administrador**: Acesso total ao sistema

### Controle e Organização
- ✅ Dashboard com visão geral dos chamados
- ✅ Filtros e busca avançada
- ✅ Notificações de atualizações
- ✅ Relatórios e estatísticas
- ✅ Base de conhecimento para soluções recorrentes

### Agenda Interna do Setor de TI

✅ Agendamento de manutenções preventivas
✅ Calendário de atividades e eventos da equipe
✅ Gestão de horários e disponibilidade dos técnicos
✅ Programação de visitas técnicas
✅ Visualização em calendário mensal/semanal/diário
✅ Reserva de recursos e equipamentos

## 🚀 Tecnologias Utilizadas

- **Backend**: PHP
- **Banco de Dados**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap
- **Servidor Web**: Apache

## 📦 Pré-requisitos

Antes de começar, certifique-se de ter instalado em sua máquina:

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache ou Nginx
- Composer (gerenciador de dependências PHP)
- Git

## 🔧 Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/MarcusViniciusJose/helpdesk.git
cd helpdesk
```

### 2. Configure o banco de dados

Crie um banco de dados MySQL:

```sql
CREATE DATABASE helpdesk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importe o arquivo SQL de estrutura do banco (caso exista):

```bash
mysql -u seu_usuario -p helpdesk < database/helpdesk.sql
```

### 3. Configure as credenciais

Copie o arquivo de configuração de exemplo e edite com suas credenciais:

```bash
cp config/config.example.php config/config.php
```

Edite o arquivo `config/config.php` com suas informações:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'helpdesk');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```


### 6. Acesse o sistema

Abra seu navegador e acesse:

```
http://localhost/helpdesk/public
```


## 📁 Estrutura do Projeto

```
helpdesk/
├── config/              # Arquivos de configuração
├── database/            # Scripts SQL
├── models/              # Modelos de dados
├── controllers/         # Controladores da aplicação
├── views/               # Templates e páginas
├── index.php            # Arquivo principal
└── README.md
```

## 🔐 Segurança

O sistema implementa diversas camadas de segurança:

- Proteção contra SQL Injection através de prepared statements
- Validação e sanitização de inputs
- Sistema de sessões seguro
- Proteção contra CSRF
- Senhas criptografadas com hash seguro
- Controle de acesso baseado em funções (RBAC)

## 📊 Fluxo de Trabalho

1. **Usuário** abre um chamado descrevendo o problema
2. **Sistema** gera um número único de ticket
3. **Administrador/Técnico** recebe notificação do novo chamado
4. **Técnico** analisa e assume o chamado
5. **Técnico** atualiza o status e registra ações tomadas
6. **Usuário** acompanha o progresso em tempo real
7. **Técnico** resolve o problema e fecha o chamado



⭐ Se este projeto foi útil para você, considere dar uma estrela no repositório!
