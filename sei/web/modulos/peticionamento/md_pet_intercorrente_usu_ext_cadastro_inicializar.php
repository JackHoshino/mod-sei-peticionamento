<?php
    //INICIALIZACAO DE VARIAVEIS DA PAGINA
    $txtOrientacoes = "Este peticionamento serve para protocolizar documentos em processos j� existentes. Condicionado ao n�mero do processo a ser validado abaixo e parametriza��es da administra��o sobre o Tipo de Processo correspondente, os documentos poder�o ser inclu�dos diretamente no processo indicado ou protocolizado em processo novo apartado relacionado ao processo indicado.";

    $arrComandos   = array();
    $arrComandos[] = '<button type="button" accesskey="p" name="Peticionar" id="Peticionar" value="Peticionar" onclick="abrirPeticionar()" class="infraButton"><span class="infraTeclaAtalho">P</span>eticionar</button>';
    $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEIExterna::getInstance()->formatarXHTML(SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_controle_acessos&id_orgao_acesso_externo=0')) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

    //Links de acesso
    $strLinkUploadArquivo                = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=peticionamento_usuario_externo_upload_arquivo');
    $strUrlAjaxMontarSelectTipoDocumento = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=montar_select_tipo_documento');
    $strUrlAjaxMontarSelectNivelAcesso   = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=montar_select_nivel_acesso');
    $strUrlAjaxCriterioIntercorrente     = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=verificar_criterio_intercorrente');
    $strUrlAjaxMontarHipoteseLegal       = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=montar_select_hipotese_legal');
    //Fim Links

    //Msgs dos Tooltips de Ajuda
    $strMsgTooltipFormato                  = 'Selecione a op��o �Nato-digital� se o arquivo a ser carregado foi criado originalmente em meio eletr�nico.\n\n\n Selecione a op��o �Digitalizado� somente se o arquivo a ser carregado foi produzido da digitaliza��o de um documento em papel.';
    $strMsgTooltipTipoDocumento            = 'Selecione o Tipo de Documento que melhor identifique o documento a ser carregado e complemente o Tipo no campo ao lado.';
    $strMsgTooltipComplementoTipoDocumento = 'O Complemento do Tipo de Documento � o texto que completa a identifica��o do documento a ser carregado, adicionando ao nome do Tipo o texto que for digitado no referido campo (Tipo �Recurso� e Complemento �de 1� Inst�ncia� identificar� o documento como �Recurso de 1� Inst�ncia�).\n\n\n Exemplos: O Complemento do Tipo �Nota� pode ser �Fiscal Eletr�nica� ou �Fiscal n� 75/2016�. O Complemento do Tipo �Comprovante� pode ser �de Pagamento� ou �de Endere�o�.';
    //Msgs dos Tooltips de Ajuda
    $strMsgTooltipInteressadoProprioUsuarioExterno	= 'Para o Tipo de Processo escolhido o Interessado do processo a ser aberto somente pode ser o pr�prio Usu�rio Externo logado no sistema.';
    $strMsgTooltipInteressadoInformandoCPFeCNPJ		= 'Para o Tipo de Processo escolhido � poss�vel adicionar os Interessados do processo a ser aberto por meio da indica��o de CPF ou CNPJ v�lidos, devendo complementar seus cadastros caso necess�rio.';
    $strMsgTooltipInteressadoDigitadoNomeExistente	= 'Para o Tipo de Processo escolhido � poss�vel adicionar os Interessados do processo a ser aberto a partir da base de Interessados j� existentes. Caso necess�rio, clique na Lupa "Localizar Interessados" para uma pesquisa mais detalhada ou, na janela aberta, acessar o bot�o "Cadastrar Novo Interessado".';
    $strMsgTooltipTipoDocumentoPrincipal			= 'Como somente pode ter um Documento Principal, o Tipo de Documento correspondente j� � previamente definido. Deve, ainda, ser complementado no campo ao lado.';
    $strMsgTooltipTipoDocumento						= 'Selecione o Tipo de Documento que melhor identifique o documento a ser carregado e complemente o Tipo no campo ao lado.';
    $strMsgTooltipComplementoTipoDocumento			= 'O Complemento do Tipo de Documento � o texto que completa a identifica��o do documento a ser carregado, adicionando ao nome do Tipo o texto que for digitado no referido campo (Tipo �Recurso� e Complemento �de 1� Inst�ncia� identificar� o documento como �Recurso de 1� Inst�ncia�).\n\n\n Exemplos: O Complemento do Tipo �Nota� pode ser �Fiscal Eletr�nica� ou �Fiscal n� 75/2016�. O Complemento do Tipo �Comprovante� pode ser �de Pagamento� ou �de Endere�o�.';
    $strMsgTooltipNivelAcesso						= 'O N�vel de Acesso que for indicado � de sua exclusiva responsabilidade e estar� condicionado � an�lise por servidor p�blico, que poder�, motivadamente, alter�-lo a qualquer momento sem necessidade de pr�vio aviso.\n\n\n Selecione "P�blico" se no teor do documento a ser carregado n�o existir informa��es restritas. Se no teor do documento existir informa��es restritas, selecione "Restrito" e, em seguida, a Hip�tese Legal correspondente.';
    $strMsgTooltipHipoteseLegal						= 'Para o N�vel de Acesso "Restrito" � obrigat�ria a indica��o da Hip�tese Legal correspondente � informa��o restrita constante no teor do documento a ser carregado, sendo de sua exclusiva responsabilidade a referida indica��o. Em caso de d�vidas, pesquise sobre a legisla��o indicada entre par�nteses em cada Hip�tese listada.';
    $strMsgTooltipNivelAcessoPadraoPreDefinido		= 'Para o Tipo de Processo escolhido o N�vel de Acesso � previamente definido.';
    $strMsgTooltipHipoteseLegalPadraoPreDefinido	= 'Para o Tipo de Processo escolhido o N�vel de Acesso � previamente definido como "Restrito" e, assim, a Hip�tese Legal tamb�m � previamente definida.';
    $strMsgTooltipFormato							= 'Selecione a op��o �Nato-digital� se o arquivo a ser carregado foi criado originalmente em meio eletr�nico.\n\n\n Selecione a op��o �Digitalizado� somente se o arquivo a ser carregado foi produzido da digitaliza��o de um documento em papel.';
    //Fim Msgs



//RN Tamanho Maximo Arquivo
    $tamanhoMaximo = MdPetIntercorrenteINT::tamanhoMaximoArquivoPermitido();
    //Fim RN

    //RN Extensoes Permitidas
    $extensoesPermitidas = GerirExtensoesArquivoPeticionamentoINT::recuperaExtensoes(null, null, null, "N");
    //Fim RN

    //RN para exibir Hipotese Legal
    $exibirHipoteseLegal = true;
    //@todo N�o estou conseguindo instanciar aqui
    //$exibirHipoteseLegal = MdPetIntercorrenteINT::verificarHipoteseLegal();
    //Fim RN