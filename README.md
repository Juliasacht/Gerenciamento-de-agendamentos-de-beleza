# Bela Agenda

Sistema de agendamentos para salão de beleza desenvolvido por **Julia Sacht**, como projeto acadêmico de Tecnologia em Sistemas para Internet.

## Funcionalidades

- Cadastro e login de usuários.
- Criação, consulta, edição e exclusão dos próprios agendamentos.
- Interface responsiva em tons de ameixa e rosé.
- Navegação por teclado, campos com rótulos e datas no formato brasileiro.

## Executar com XAMPP

1. Clone este repositório:
   `git clone https://github.com/Juliasacht/Gerenciamento-de-agendamentos-de-beleza.git`
2. Copie a pasta `Encanto_Make` para `C:\xampp\htdocs\`.
3. Inicie Apache e MySQL.
4. No phpMyAdmin, importe `Encanto_Make/database/schema.sql` para criar o banco `bela_agenda` e suas tabelas.
5. Confira as credenciais e o nome do banco em `Encanto_Make/conexao.php`.
6. Acesse http://localhost/Encanto_Make/.

A aplicação se chama **Bela Agenda**. A pasta existente foi preservada. A conexão padrão usa o banco `bela_agenda`. Para instalações anteriores, configure `DB_NAME` apenas se o banco estiver íntegro; o arquivo legado `bancodados` não é necessário para uma instalação nova.

## Tecnologias

PHP com MySQLi, MySQL, HTML5 e CSS3. Não é necessário instalar dependências com npm.

## Verificação

A sintaxe dos arquivos PHP pode ser verificada com `php -l arquivo.php`.
Para testar o fluxo completo, use uma base de desenvolvimento: cadastre uma conta, entre, crie um horário, edite-o, exclua-o e encerre a sessão.

## Conexão

As variáveis de ambiente `DB_HOST`, `DB_USER`, `DB_PASSWORD` e `DB_NAME` permitem configurar a conexão. Os padrões locais são `localhost`, `root`, senha vazia e `bela_agenda`. Crie as tabelas com `database/schema.sql` antes de abrir o sistema.

## Atualizar uma instalação existente

Para habilitar cliente, profissional, valor e duração nos agendamentos já existentes, importe `Encanto_Make/database/migracao_agendamentos.sql` no banco `bela_agenda` antes de usar a versão nova. A migração adiciona campos opcionais sem apagar registros. Instalações novas devem usar `database/schema.sql`.

O botão **Novo agendamento** abre um formulário sobre o dashboard. Cliente e profissional sugerem os nomes já usados pela própria conta; valor e duração são informados manualmente. A prévia `dashboard.php?demo=1&novo=1` permite visualizar o formulário sem gravar dados.
