# Notas do autor
Fiz duas versões (uma usando PHP puro, e outra usando o framework Laravel).

As duas funcionam perfeitamente, apenas sendo necessário criar o banco de dados.

Para configurar o banco de dados:

  ● Usuario deve ser root e sem senha, host do banco deve ser local. Caso necessário, altere as credenciais nos arquivos "./PHP Puro/api.php" linha 7, e no "./PHP+Laravel+jQuery/.env" linhas 11-16 (No caso do laravel, não esqueca de executa o comando "php artisan config:cache" após alterar o .env)

  ● Versão PHP Puro: importe o arquivo banco.sql em um servidor MySQL local (usei o incluso no XAMPP)

  ● Versão Laravel: na raiz do projeto, execute o comando "php artisan config:cache", em seguida já com o servidor MySQL iniciado, execute o comando "php artisan migrate:fresh" ou importe o arquivo banco.sql manualmente.

# PHPtest

Teste de seleção para vaga PHP

## Faça um fork desse projeto e siga as intruções a seguir utilizando esse projeto.

Construir uma aplicação web para buscar endereço. Aplicação deve fazer uma chamada na API via cep : https://viacep.com.br/.
Premissas:

  ● Usar PHP 5.6 ou superior.
  
  ● Usar Bootstrap.
  
  ● JavaScript (Não usar framework).
  
  ● Retorno deve ser em xml.
  
  ● Salvar os dados em uma base e antes de uma nova consulta verificar se o cep já foi consultado, caso tenha sido, trazer    informação da base e não deve efetuar uma nova consulta.
  
  ● Tratar o erro. Dar um retorno amigável para usuário leigo.
  
  
## PS: Valorizamos a criatividade no layout.

# Entrega: 
 * Disponibilizar um link do repositório no GitHub e encaminhar para developer@cd2.com.br
