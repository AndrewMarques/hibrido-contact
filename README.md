hibrido-contact
===============

plugin wodpress para facilitar formulários de contato

# campos

todos os campos possuem um padrão sensível e podem ser modificados com filtros

## padrões

campo | padrão
----- | ------


## filtros

filtro           | descrição
---------------  | ---------
hc-mail-post     | aplica filtros no $_POST, passando o $_POST como argumento
hc-mail-to       | aplica filtros no remetente, passando o remetente padrão como argumento
hc-mail-subject  | aplica filtros no assunto do email, passando o assunto padrão como argumento
hc-mail-message  | aplica filtros na mensagem, passando a mensagem e o $_POST como argumento
hc-mail-from     | aplica filtros no cabeçalho de envio passando o cabeçalho padrão como argumento e.g. `From: Me <me@home.com>`
hc-mail-reply-to | aplica filtros no cabeçalho de envio passando o cabeçalho padrão como argumento e.g. `Reply-to: Me <me@home.com>`
hc-mail-headers  | aplica filtros no array de cabeçalhos do email (que possuem somente o from)

# temos que

## form

* [x] bindar no form através de data-attributes
* [x] enviar o form via ajax
* [x] desabilitar e botar mensagem de loading no botão enquanto envia

## sucesso

* [x] elemento de mensagem enviada ok (com data-attributes)

## erros

* prover uma api para adicionar validações (tem que ser por atributos e.g. required)
* colocar erros por campo (como vai ser por atributos isso é automatico)

## mail

* [ ] enviar o email
* [ ] prover um filter para modificar os dados antes de coloca-los na mensagem
* [ ] prover um filter para modificar a mensagem de envio (sprintf ???)
