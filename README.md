# Bela Agenda

Aplicação web para cadastro de usuários e gerenciamento de horários em um salão de beleza. Com PHP e MySQL, o sistema permite criar uma conta, entrar e acompanhar, editar ou excluir os próprios agendamentos.

## Executar localmente

1. Instale PHP 8.2 ou superior com a extensão `mysqli` e inicie o MySQL ou MariaDB.
2. Crie o banco `encanto_make` e importe `database/schema.sql`. O arquivo cria as tabelas sem contas ou agendamentos preexistentes.
3. Na raiz do repositório, execute `php -S localhost:8000 -t Encanto_Make`.
4. Abra http://localhost:8000, crie uma conta com dados fictícios e agende um horário.

A conexão aceita as variáveis de ambiente `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD` e `DB_NAME`.

## Demonstração na Vercel

A publicação do sistema completo requer um runtime comunitário de PHP com `mysqli`, um banco MySQL hospedado e sessões compartilhadas entre instâncias. Use um banco separado, criado a partir de `database/schema.sql`, com dados fictícios. A estrutura atual está configurada para execução local com sessões nativas do PHP.
