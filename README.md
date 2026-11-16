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
4. No phpMyAdmin, importe o arquivo SQL `Encanto_Make/bancodados`.
5. Confira as credenciais e o nome do banco em `Encanto_Make/conexao.php`.
6. Acesse http://localhost/Encanto_Make/.

A aplicação se chama **Bela Agenda**. A pasta e o banco existentes foram preservados para manter compatibilidade com instalações anteriores.

## Tecnologias

PHP com MySQLi, MySQL, HTML5 e CSS3. Não é necessário instalar dependências com npm.

## Verificação

A sintaxe dos arquivos PHP pode ser verificada com `php -l arquivo.php`.
Para testar o fluxo completo, use uma base de desenvolvimento: cadastre uma conta, entre, crie um horário, edite-o, exclua-o e encerre a sessão.
