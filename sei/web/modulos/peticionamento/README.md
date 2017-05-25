# M�dulo Peticionamento e Intima��o Eletr�nicos

## Requisitos:
- SEI 3.0.2 instalado/atualizado ou vers�o superior (verificar valor da constante de vers�o do SEI no arquivo /sei/web/SEI.php).
	- **IMPORTANTE**, no caso de atualiza��o do presente m�dulo: A atualiza��o do SEI 2.6 para 3.0 alterou diversas tabelas que as tabelas do m�dulo relacionava. Dessa forma, alertamos que, imediatamente ANTES de executar o script de atualiza��o do SEI 3.0 � necess�rio executar o script abaixo no banco do SEI para que a atualiza��o do SEI 3.0 possa ocorrer sem erro:
		
		ALTER TABLE `md_pet_rel_tp_ctx_contato` DROP FOREIGN KEY `fk_md_pet_rel_tp_ctx_cont_1`;
		
- Antes de executar os scripts de instala��o/atualiza��o (itens 4 e 5 abaixo), o usu�rio de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, dever� ter permiss�o de acesso total ao banco de dados, permitindo, por exemplo, cria��o e exclus�o de tabelas.

## Procedimentos para Instala��o:

1. Antes, fazer backup dos bancos de dados do SEI e do SIP.

2. Carregar no servidor os arquivos do m�dulo localizados na pasta "/sei/web/modulos/peticionamento" e os scripts de instala��o/atualiza��o "/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php" e "/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php".

3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que n�o altere o charset do arquivo, para adicionar a refer�ncia � classe de integra��o do m�dulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			'URL' => 'http://[Servidor_PHP]/sei',
			'Producao' => false,
			'RepositorioArquivos' => '/var/sei/arquivos',
			'Modulos' => array('PeticionamentoIntegracao' => 'peticionamento',)
			),

4. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php" em linha de comando no servidor do SEI, verificando se n�o houve erro em sua execu��o, em que ao final do log dever� ser informado "FIM". Exemplo de comando de execu��o:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php > atualizacao_modulo_peticionamento_sei.log

5. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php" em linha de comando no servidor do SIP, verificando se n�o houve erro em sua execu��o, em que ao final do log dever� ser informado "FIM". Exemplo de comando de execu��o:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php > atualizacao_modulo_peticionamento_sip.log

6. Ap�s a execu��o com sucesso, com um usu�rio com permiss�o de Administrador no SEI, seguir os passos dispostos no t�pico Orienta��es Negociais, abaixo.

7. **IMPORTANTE**: Na execu��o dos dois scripts acima, ao final deve constar o termo "FIM". Do contr�rio, o script n�o foi executado at� o final e algum dado n�o foi inserido/atualizado no banco de dados correspondente, devendo recuperar o backup do banco pertinente e repetir o procedimento.
		- Constando o termo "FIM" ao final da execu��o significa que foi executado com sucesso. Verificar no SEI e no SIP no menu Infra > Par�metros se consta o par�metro "VERSAO_MODULO_PETICIONAMENTO" com o valor da �ltima vers�o do m�dulo.

8. Em caso de erro durante a execu��o do script verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa � algum problema na infra-estrutura local. Neste caso, ap�s a corre��o, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execu��o dos scripts indicados nos itens 4 e 5 acima.
	- Caso n�o seja poss�vel identificar a causa, entrar em contato com: Nei Jobson - neijobson@anatel.gov.br

## Orienta��es Negociais:

1. Imediatamente ap�s a instala��o com sucesso, com usu�rio com permiss�o de "Administrador" do SEI, � necess�rio realizar as parametriza��es do m�dulo no menu Administra��o > Peticionamento Eletr�nico, para que o m�dulo seja utilizado adequadamente pelos Usu�rios Externos na tela de Acesso Externo do SEI:

		http://[Servidor_PHP]/sei/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo=0

2. Ainda com usu�rio com permiss�o de "Administrador" do SEI, � necess�rio cadastrar os "Cargos", "Tratamentos", "Vocativos" e "Tipos" no menu Administra��o > Contatos.
	- Os "Cargos" ser�o utilizados pelos Usu�rios Externos na sele��o do "Cargo/Fun��o" na assinatura de cada Peticionamento e tamb�m no cadastro de novos Interessados.
	- Os demais registros acima ser�o utilizados no cadastro de novos Interessados pelos Usu�rios Externos.
	- Caso a instala��o do SEI do �rg�o n�o possua parametriza��es acima, sugerimos como exemplo a lista disponibilizada no link a seguir: https://goo.gl/NqikRu

3. Outro ponto importante � a parametriza��o do menu Administra��o > Peticionamento Eletr�nico > Hip�teses Legais Permitidas. Contudo, antes, exige que no menu Administra��o > Hip�teses Legais > Lista (core do SEI) j� contenha uma lista bem definida de uso pelo �rg�o, com todas as op��es legais existentes aplic�veis, inclusive em raz�o de Lei pr�prio do �rg�o, pois, na medida que se disponibiliza as Hip�teses aplic�veis, melhor ser�o as indica��es de Restri��o segundo op��o legal pr�pria.
	- Caso a instala��o do SEI do �rg�o n�o possua lista de Hip�teses Legais em uso ou a lista tenha poucas op��es, sugerimos como exemplo a lista disponibilizada no link a seguir: https://goo.gl/JzycpM

4. Destacamos que a janela de Cadastro de Interessado na tela de Peticionamento de Processo Novo � aberta ao Validar CPF ou CPNJ em duas situa��es: (i) quando o CPF ou CNPJ n�o existir na tabela "contato" no banco do SEI ou (ii) quando existir mais de um registro na referida tabela com o mesmo CPF ou CNPJ. A segunda regra visa a priorizar o cadastro novo feito por meio do m�dulo pelo pr�prio Usu�rio Externo, que geralmente possui mais dados sobre o Interessado.
	- **IMPORTANTE**: sugere-se que o �rg�o fa�a uma extra��o da tabela "contato" e fa�a an�lises para levantar os cadastros com CPF ou CNPJ duplicados, para resolver as duplica��es, mantendo um s� cadastro por CPF ou CNPJ.

5. Peticionamento Intercorrente:
	- Os Usu�rios Externos somente visualizar�o o menu Peticionamento > Intercorrente depois que na Administra��o for configurado pelo menos o "Intercorrente Padr�o".
	- A abertura de processo novo relacionado ao processo de fato indicado pelo Usu�rio Externo ocorrer� quando este corresponder a processo: 1) de Tipo de Processo sem Crit�rio Intercorrente parametrizado; 2) com N�vel de Acesso "Sigiloso"; 3) Sobrestado, Anexado ou Bloqueado; ou 4) de Tipo de Processo desativado.
		- Em todos os casos acima a forma de indica��o de N�vel de Acesso pelo Usu�rio Externo ser� a indicada em Administra��o > Peticionamento Eletr�nico > Crit�rios para Intercorrente > bot�o "Intercorrente Padr�o". Somente no caso 4 � que o Tipo de Processo tamb�m ser� o indicado para "Intercorrente Padr�o".
	- Se TODAS as Unidades por onde o processo indicado tenha tramitado estiverem Desativadas no SEI, o Usu�rio Externo ser� avisado que o Peticionamento Intercorrente n�o � poss�vel e que dever� utilizar a funcionalidade de Peticionamento de Processo Novo.

6. N�o foi poss�vel fazer um Manual do Usu�rio Externo gen�rico para qualquer �rg�o, em raz�o das especificidades de cada �rg�o quanto aos procedimentos de credenciamento dos Usu�rios Externos e at� mesmo de parametriza��o do M�dulo. De qualquer forma, segue link para o Manual do Usu�rio Externo do SEI elaborado pela Anatel que pode ser quase que completamente aproveitado para elabora��o de outros Manuais: https://goo.gl/eyJr12

7. Acesse o Manual de Administra��o do M�dulo (ainda em constru��o): https://goo.gl/pqIoZY

8. Ainda, conforme pode ser observado no Manual do Usu�rio Externo disponibilizado no item 7 acima, � extremamente recomendado que o �rg�o tenham bem definido procedimento para cadastro e libera��o de Usu�rios Externos no SEI, preferencialmente com assinatura e entrega de "Termo de Declara��o de Concord�ncia e Veracidade" e mais um documento que contenha n�mero de CPF.
	- Segue link para exemplo de Termo que pode ser utilizado, desde que ajuste a indica��o do endere�o para envio da correspond�ncia. Sugerimos que seja disponibilizado em formato PDF: https://docs.google.com/document/d/1ZvoDA5Jpx2VwNKvva6V9v3d3j7hQdbJUXBJ81wEbITg/edit?usp=sharing

9. Por fim, n�o � aconselh�vel dar publicidade a registros de indisponibilidades do SEI at� que o m�dulo possua funcionalidades afetas a Intima��o Eletr�nica, prevista para a vers�o 2.0. De qualquer forma, segue URL da p�gina p�blica que lista os cadastrados realizados no menu Administra��o > Peticionamento Eletr�nico > Indisponibilidades do SEI:

		http://[Servidor_PHP]/sei/modulos/peticionamento/md_pet_usu_ext_indisponibilidade_lista.php?acao_externa=md_pet_usu_ext_indisponibilidade_listar&id_orgao_acesso_externo=0