hibrido-contact
===============

esse plugin wodpress foi desenvolvido para facilitar o envio e manipulação de formulários de contato que são enviados por ajax

# utilizar

para utilizarmos o plugin, precisamos criar uma tag `<form>` com o atributo `data-hc-form`, `method=post` e `action` igual a `admin_url('admin-ajax.php')`, onde esse form será o form que será enviado por email (na verdade os dados contidos nele serão enviados por email)

após enviarmos nossa requisição receberemos um feedback, para mostrarmos esse feedback precisamos criar qualquer elemento com o atributo `data-hc-feedback`, onde esse elemento além de receber a mensagem de resposta, receberá uma classe indicando o status da resposta `success` para sucesso e `error` para erro

quando clicamos para enviar o formulário o texto do botão é trocado, então, para que nenhum erro ocorra e consigamos enviar o formulário, precisamos que o botão de envio seja um tag button no estilo `<button type="submit">Enviar</button>`

outra coisa que precisamos lembrar é que onde o form for utilizado precisamos chamar o código de inicio `HC::init()`

# campos

## campos da mensagem de email

a mensagem de email possui vários campos os quais são populados normalmente pelos padrões, porem, podem ser também populados segundo alguns campos do formulário

lembrando que todos os padrões são buscados com base na linguagem do wordpress, atualmente o plugin só tem a linguagem pt_BR

campo | padrão | overwrite
----- | ------ | ---------
$to | email admin do wp | pelo filtro
$subject | contato enviado pelo site | campo assunto e depois pelo filtro
$message | todos os campos com seus valores | pelo filtro
$from | blogname <admin email> | pelo filtro
$replyTo | nenhum | campo nome <campo email> e depois pelo filtro
$headers | array($from, $replyTo) | pelo filtro

## filtros

filtro | descrição
-----  | ---------
hc-mail-post | aplica filtros no $_POST, passando o $_POST como argumento
hc-mail-to | aplica filtros no remetente, passando o remetente padrão como argumento
hc-mail-subject | aplica filtros no assunto do email, passando o assunto padrão como argumento
hc-mail-message | aplica filtros na mensagem, passando a mensagem e o $_POST como argumento
hc-mail-from | aplica filtros no cabeçalho de envio passando o cabeçalho padrão como argumento e.g. `From: Me <me@home.com>`
hc-mail-reply-to | aplica filtros no cabeçalho de envio passando o cabeçalho padrão como argumento e.g. `Reply-to: Me <me@home.com>`
hc-mail-headers | aplica filtros no array de cabeçalhos do email (que possuem somente o from)

