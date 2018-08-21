# Módulo Peticionamento e Intimação Eletrônicos

## Requisitos
- SEI 3.0.11 instalado/atualizado ou versão superior.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização (itens 4 e 5 abaixo), o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.
- Os códigos-fonte do Módulo podem ser baixados a partir do link a seguir, devendo sempre utilizar a versão mais recente: [https://softwarepublico.gov.br/gitlab/anatel/mod-sei-peticionamento/tags](https://softwarepublico.gov.br/gitlab/anatel/mod-sei-peticionamento/tags "Clique e acesse")
- Solicitamos que os Órgãos que tenham instalado o Módulo preencham a pesquisa a seguir, para termos um feedback sobre sua utilização: [https://goo.gl/gubYLL](https://goo.gl/gubYLL "Clique e acesse")

## Procedimentos para Instalação
1. Antes, fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo localizados na pasta "/sei/web/modulos/peticionamento" e os scripts de instalação/atualização "/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php" e "/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php".
   - Caso se trate de atualização de versão anterior do Módulo, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/peticionamento", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			'URL' => 'http://[Servidor_PHP]/sei',
			'Producao' => false,
			'RepositorioArquivos' => '/var/sei/arquivos',
			'Modulos' => array('PeticionamentoIntegracao' => 'peticionamento',)
			),

4. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_peticionamento.php > atualizacao_peticionamento_sip.log

5. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_peticionamento.php > atualizacao_modulo_peticionamento_sei.log

6. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.
7. **IMPORTANTE**: Na execução dos dois scripts acima, ao final deve constar o termo "FIM" e informação de que a instalação ocorreu com sucesso (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no banco de dados correspondente, devendo recuperar o backup do banco pertinente e repetir o procedimento.
   - Constando o termo "FIM" e informação de que a instalação ocorreu com sucesso, pode logar no SEI e SIP e verificar no menu Infra > Parâmetros dos dois sistemas se consta o parâmetro "VERSAO_MODULO_PETICIONAMENTO" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts indicados nos itens 4 e 5 acima.
	- Caso não seja possível identificar a causa, entrar em contato com: Nei Jobson - neijobson@anatel.gov.br

## Orientações Negociais
1. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do Módulo pelo seguinte caminho: Administração > Peticionamento Eletrônico. Somente com tudo parametrizado adequadamente será possível o uso do módulo pelos Usuários Externos por meio da tela de Acesso Externo do SEI:

		http://[Servidor_PHP]/sei/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo=0

2. O script de banco do SIP já cria todos os Recursos e Menus e os associam automaticamente ao Perfil "Básico" ou ao Perfil "Administrador".
	- Independente da criação de outros Perfis, os recursos indicados para o Perfil "Básico" ou "Administrador" devem manter correspondência com os Perfis dos Usuários internos que utilizarão o Módulo e dos Usuários Administradores do Módulo.
	- O SIP não controla Perfil próprio para os Usuários Externos, cabendo diretamente ao código do Módulo o controle devido junto aos Recursos e Menus criados pelo Módulo para os Usuários Externos.
	- Tão quanto ocorre com as atualizações do SEI, versões futuras deste Módulo continuarão a atualizar e criar Recursos e associá-los apenas aos Perfis "Básico" e "Administrador".
	- Todos os recursos do Módulo iniciam pelo sufix **"md_pet_"**.
3. Acesse no link a seguir o Manual de Administração [https://goo.gl/pqIoZY](https://goo.gl/pqIoZY "Clique e acesse")
4. Acesse no link a seguir o Manual do Usuário Interno: [https://goo.gl/oo34ur](https://goo.gl/oo34ur "Clique e acesse")
5. Acesse no link a seguir o Manual do Usuário Externo: [https://goo.gl/eyJr12](https://goo.gl/eyJr12 "Clique e acesse")
	- Não foi possível fazer um Manual do Usuário Externo genérico para qualquer órgão, em razão das especificidades de cada órgão quanto aos procedimentos de credenciamento dos Usuários Externos e até mesmo de parametrização do Módulo. De qualquer forma, o Manual do Usuário Externo do SEI elaborado pela Anatel, acima, que pode ser quase que completamente aproveitado.