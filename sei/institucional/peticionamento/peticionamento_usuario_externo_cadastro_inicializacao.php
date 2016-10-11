<?
/**
* ANATEL
*
* 01/08/2016 - criado por marcelo.bezerra@cast.com.br - CAST
*
* Defini��o de objetos e variaveis necess�rias para a inicializa��o da p�gina
*
*/
//=====================================================
//INICIO - VARIAVEIS PRINCIPAIS E LISTAS DA PAGINA
//=====================================================

//extensoes permitidas para upload
if( $_GET['acao'] != "peticionamento_usuario_externo_download"){

	$strDesabilitar = '';

	$arrComandos = array();

	//pegar lista de extensoes parametrizadas do m�dulo
	$dtoTamanhoArquivoPrincipal = new GerirExtensoesArquivoPeticionamentoDTO();
	$dtoTamanhoArquivoPrincipal->retTodos();
	$dtoTamanhoArquivoPrincipal->setStrSinAtivo('S');
	$dtoTamanhoArquivoPrincipal->setStrSinPrincipal('S');
	
	$dtoTamanhoArquivoEssencialComplementar = new GerirExtensoesArquivoPeticionamentoDTO();
	$dtoTamanhoArquivoEssencialComplementar->retTodos();
	$dtoTamanhoArquivoEssencialComplementar->setStrSinAtivo('S');
	$dtoTamanhoArquivoEssencialComplementar->setStrSinPrincipal('N');
	
	$rnTamanhoArquivo = new GerirExtensoesArquivoPeticionamentoRN();
	
	$arrDTOTamanhoArquivoPrincipal = $rnTamanhoArquivo->listar( $dtoTamanhoArquivoPrincipal );
	$arrDTOTamanhoArquivoEssencialComplementar = $rnTamanhoArquivo->listar( $dtoTamanhoArquivoEssencialComplementar );
	$arrExtPermitidas = array();
	$arrExtPermitidasEssencialComplementar = array();
	
	$objArquivoExtensaoRN = new ArquivoExtensaoRN();
	
	//extensoes para doc principal	
	if(count($arrDTOTamanhoArquivoPrincipal) > 0)
	{
		
		$key = 0;
		
		foreach($arrDTOTamanhoArquivoPrincipal as $objTamanhoArquivoDTO)
		{
			
			$objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
			$objArquivoExtensaoDTO->retTodos();
			$objArquivoExtensaoDTO->setNumIdArquivoExtensao( $objTamanhoArquivoDTO->getNumIdArquivoExtensao() );
			$objArquivoExtensaoDTO = $objArquivoExtensaoRN->consultar( $objArquivoExtensaoDTO );
			
			$key = $key + 1;
			$chave = (string) $key;
			$chave = 'ext_'.$chave;
			$arrExtPermitidas[$chave] = $objArquivoExtensaoDTO->getStrExtensao();
		}
	} 
	
	//extensoes para docs essencial e complementar
	if(count($arrDTOTamanhoArquivoEssencialComplementar) > 0)
	{
		$keyEssencial = 0;
		
		foreach($arrDTOTamanhoArquivoEssencialComplementar as $objTamanhoArquivoDTOEssencialComplementar)
		{
			$objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
			$objArquivoExtensaoDTO->retTodos();
			$objArquivoExtensaoDTO->setNumIdArquivoExtensao( $objTamanhoArquivoDTOEssencialComplementar->getNumIdArquivoExtensao() );
			$objArquivoExtensaoDTO = $objArquivoExtensaoRN->consultar( $objArquivoExtensaoDTO );
			
			$keyEssencial = $keyEssencial + 1;
			$chave = (string) $keyEssencial;
			$chave = 'ext_'.$chave;
			$arrExtPermitidasEssencialComplementar[$chave] = $objArquivoExtensaoDTO->getStrExtensao();
		}
	}

	$jsonExtPermitidas =  count($arrExtPermitidas) > 0 ? json_encode($arrExtPermitidas) : null;
	$jsonExtEssencialComplementarPermitidas =  count($arrExtPermitidasEssencialComplementar) > 0 ? json_encode($arrExtPermitidasEssencialComplementar) : null;
	
	//echo " Principal :: " . $jsonExtPermitidas;
	//echo " Essencial Complementar :: " . $jsonExtEssencialComplementarPermitidas;
	//die();
}

//tipo de processo escolhido

$idTipoProc = $_GET['id_tipo_procedimento'];
$objTipoProcDTO = new TipoProcessoPeticionamentoDTO();
$objTipoProcDTO->retTodos();
$objTipoProcDTO->retStrNomeProcesso();
$objTipoProcDTO->setNumIdTipoProcessoPeticionamento( $idTipoProc );
$objTipoProcRN = new TipoProcessoPeticionamentoRN();
$objTipoProcDTO = $objTipoProcRN->consultar( $objTipoProcDTO );

//texto de orientacoes
$txtOrientacoes = $objTipoProcDTO->getStrOrientacoes();

//obtendo a unidade do tipo de processo selecionado - Pac 10 - pode ser uma ou MULTIPLAS unidades selecionadas
$relTipoProcUnidadeDTO = new RelTipoProcessoUnidadePeticionamentoDTO();
$relTipoProcUnidadeDTO->retTodos();
$relTipoProcUnidadeRN = new RelTipoProcessoUnidadePeticionamentoRN();
$relTipoProcUnidadeDTO->setNumIdTipoProcessoPeticionamento( $idTipoProc );
$arrRelTipoProcUnidadeDTO = $relTipoProcUnidadeRN->listar( $relTipoProcUnidadeDTO );

$arrUnidadeUFDTO = null;
$idUnidadeTipoProcesso = null;

//APENAS UMA UNIDADE
if( $arrRelTipoProcUnidadeDTO != null && count( $arrRelTipoProcUnidadeDTO ) == 1 ) {
  
	$idUnidadeTipoProcesso = $arrRelTipoProcUnidadeDTO[0]->getNumIdUnidade();
  
}

//MULTIPLAS UNIDADES
else if( $arrRelTipoProcUnidadeDTO != null && count( $arrRelTipoProcUnidadeDTO ) > 1 ){
		
	$arrIdUnidade = array();
	
	//consultar UFs das unidades informadas
	foreach( $arrRelTipoProcUnidadeDTO as $itemRelTipoProcDTO ){
		$arrIdUnidade[] = $itemRelTipoProcDTO->getNumIdUnidade();
	}
	
	$objUnidadeDTO = new UnidadeDTO();
	$objUnidadeDTO->retNumIdUnidade();
	$objUnidadeDTO->retStrSiglaUf();
	
	$objUnidadeDTO->adicionarCriterio(array('IdUnidade', 'SinAtivo'),
			array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IGUAL),
			array( $arrIdUnidade,'S'),
			InfraDTO::$OPER_LOGICO_AND);
	
	$objUnidadeRN = new UnidadeRN();
	$arrUnidadeUFDTO = $objUnidadeRN->listarRN0127( $objUnidadeDTO );
	
}

$ObjRelTipoProcessoSeriePeticionamentoRN = new RelTipoProcessoSeriePeticionamentoRN();
$ObjRelTipoProcessoSeriePeticionamentoDTO = new RelTipoProcessoSeriePeticionamentoDTO();
$ObjRelTipoProcessoSeriePeticionamentoDTO->retTodos();
$ObjRelTipoProcessoSeriePeticionamentoDTO->setNumIdTipoProcessoPeticionamento( $idTipoProc );
$arrTiposDocumentosComplementares = $ObjRelTipoProcessoSeriePeticionamentoRN->listar( $ObjRelTipoProcessoSeriePeticionamentoDTO );

//ler configura�oes necessarias para aplicar a RN 8
/*
 [RN8]	O sistema deve verificar na funcionalidade �Gerir Tipos de Processo para Peticionamento� se o documento principal
selecionado foi �Externo (Anexa��o de Arquivo)�. Caso tenha sido selecionado ao preencher os dados do
	novo peticionamento, o sistema permitir� anexar o arquivo conforme o tipo informado.
*/
$isDocPrincipalGerado = $objTipoProcDTO->getStrSinDocGerado();
$isDocPrincipalExterno = $objTipoProcDTO->getStrSinDocExterno();

$serieRN = new SerieRN();
$serieDTO = new SerieDTO();
$serieDTO->retTodos();
$serieDTO->setNumIdSerie( $objTipoProcDTO->getNumIdSerie() );
$serieDTO = $serieRN->consultarRN0644( $serieDTO );
$strTipoDocumentoPrincipal = $serieDTO->getStrNome();

//ler configura�oes necessarias para aplicar RN18
/*
 [RN18]	Os campos �N�veis de Acesso� e �Hip�tese Legal� deve conter as op��es de acordo com o cadastro realizado:

- CENARIO 1 ::: Na funcionalidade �Gerir Tipo de Processo� tenha sido selecionado como n�vel de acesso a op��o �Padr�o�, o sistema
deve apresentar o n�vel de acesso para o tipo cadastrado na pr�pria funcionalidade de Gerir Tipo de Processo,
apresentando somente o registro cadastrado.

- CENARIO 2 ::: Caso tenha sido selecionada a op��o �Usu�rio Externo pode Indicar dentre os permitidos para o Tipo de Processo�
dever� ser apresentada as op��es: P�blico, Restrito e Sigiloso.
*/

$isNivelAcessoPadrao = $objTipoProcDTO->getStrSinNaPadrao();
$nivelAcessoPadrao = $objTipoProcDTO->getStrStaNivelAcesso();

if( $isNivelAcessoPadrao == 'S' && $nivelAcessoPadrao == "1"){
	
	$objTipoProcDTO->retTodos(true);
	$objTipoProcDTO = $objTipoProcRN->consultar( $objTipoProcDTO );
	$idHipoteseLegalPadrao = $objTipoProcDTO->getNumIdHipoteseLegal();
    $strHipoteseLegalPadrao = $objTipoProcDTO->getStrNomeHipoteseLegal();
    $strHipoteseLegalPadrao .= " (".$objTipoProcDTO->getStrBaseLegalHipoteseLegal().")";
}

$isUsuarioExternoPodeIndicarNivelAcesso = $objTipoProcDTO->getStrSinNaUsuarioExterno();
$strNomeNivelAcessoPadrao = "";

if( $isNivelAcessoPadrao == 'S'){
	 
	if( $nivelAcessoPadrao == "0"){ $strNomeNivelAcessoPadrao = "P�blico"; }
	else if( $nivelAcessoPadrao == "1"){ $strNomeNivelAcessoPadrao = "Restrito"; }
	else if( $nivelAcessoPadrao == "2"){ $strNomeNivelAcessoPadrao = "Sigiloso"; }
	 
}

//checando se Documento Principal est� parametrizado para "Externo (Anexa��o de Arquivo) ou Gerador (editor do SEI)
$objTipoProcessoPeticionamentoRN = new TipoProcessoPeticionamentoRN();
$objTipoProcessoPeticionamentoDTO = new TipoProcessoPeticionamentoDTO();
$objTipoProcessoPeticionamentoDTO->setStrSinAtivo('S', InfraDTO::$OPER_IGUAL);
$objTipoProcessoPeticionamentoDTO->retTodos();
$objTipoProcessoPeticionamentoDTO->setNumIdProcedimento( $objTipoProcDTO->getNumIdProcedimento() , InfraDTO::$OPER_IGUAL );
$ObjTipoProcessoPeticionamentoDTO = $objTipoProcessoPeticionamentoRN->consultar( $objTipoProcessoPeticionamentoDTO );

$txtTipoProcessoEscolhido = $objTipoProcDTO->getStrNomeProcesso();

//preeche a lista de interessados PF/PJ CASO 2
$arrPFPJInteressados = array();

//preenche a combo de interessados - CASO 3
$arrContatosInteressados = array();

//preenche a combo "Tipo"
$arrTipo = array();

//preenche a combo "Nivel de acesso"
$arrNivelAcesso = array();

//monta combo "Nivel de acesso"
$strItensSelNivelAcesso  = TipoProcessoPeticionamentoINT::montarSelectNivelAcesso(null, null, null, $objTipoProcDTO->getNumIdProcedimento());

//ler valor do parametro SEI_HABILITAR_HIPOTESE_LEGAL
//aplicar RA 5: Os campos �Hip�tese Legal� somente ser�o apresentados se na funcionalidade Infra > Par�metros
//a op��o SEI_HABILITAR_HIPOTESE_LEGAL estiver configurado como 1 ou 2 sendo assim obrigat�rio.
$objInfraParametro = new InfraParametro(BancoSEI::getInstance());
$valorConfigHipoteseLegal = $objInfraParametro->getValor('SEI_HABILITAR_HIPOTESE_LEGAL', false);
$isConfigHipoteseLegal = false;

if( $valorConfigHipoteseLegal == 1 || $valorConfigHipoteseLegal == 2){

	$isConfigHipoteseLegal = true;
	 
	//verificar se irei trazer hipoteses legais da parametriza�ao do peticionamento ou se irei consultar as hipoteses cadastradas no sistema
	$objHipoteseLegalPeticionamentoRN = new HipoteseLegalPeticionamentoRN();
	$objHipoteseLegalPeticionamentoDTO = new HipoteseLegalPeticionamentoDTO();
	$objHipoteseLegalPeticionamentoDTO->retTodos(true);
	$arrObjHipoteseLegalPeticionamentoDTO = $objHipoteseLegalPeticionamentoRN->listar( $objHipoteseLegalPeticionamentoDTO );
	
	$arrIdsHipotesesParametrizadas = array();
	if( $arrObjHipoteseLegalPeticionamentoDTO != null && count( $arrObjHipoteseLegalPeticionamentoDTO ) > 0){
		
		foreach( $arrObjHipoteseLegalPeticionamentoDTO as $itemDTO ){
			$arrIdsHipotesesParametrizadas[] = $itemDTO->getNumIdHipoteseLegalPeticionamento();
		}
		
		//trazer uma lista contendo apenas as hipoteses legais parametrizadas
		
		$arrHipoteseLegal = $objHipoteseLegalPeticionamentoRN->listarHipotesesParametrizadas( $arrIdsHipotesesParametrizadas );
		
	} else {
	
		//preenche a combo "Hipotese legal"
		$hipoteseRN = new HipoteseLegalRN();
		$hipoteseDTO = new HipoteseLegalDTO();
		$hipoteseDTO->retTodos();
		$hipoteseDTO->setStrSinAtivo('S');
		$arrHipoteseLegal = $hipoteseRN->listar( $hipoteseDTO );
	
	}
}

//preenche a combo "Documento Objeto da Digitaliza��o era"
$arrDocumentoObjetoDigitalizacao = array();

//preenche tabela de documentos (final da tela)
$arrTabelaDocumentos = array();

//DTO basico de Processo Peticionamento Novo
$objIndisponibilidadePeticionamentoDTO = new IndisponibilidadePeticionamentoDTO();

//listagem da combo "Documento objeto da Digitaliza��o era:�
$tipoConferenciaRN = new TipoConferenciaRN();
$tipoConferenciaDTO = new TipoConferenciaDTO();
$tipoConferenciaDTO->retTodos();
$tipoConferenciaDTO->setStrSinAtivo('S');
$arrTipoConferencia = $tipoConferenciaRN->listar( $tipoConferenciaDTO );

//tamanho maximo de arquivo, tem o interno do SEI e tem o da parametriza�ao 
$numSeiTamMbDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');
$numSeiTamMbDocExterno = ($numSeiTamMbDocExterno < 1024 ? $numSeiTamMbDocExterno." MB" : (round($numSeiTamMbDocExterno/1024,2))." GB");

//limpando variavel de sessao que controla detalhes de exibicao internos 
//da janela de cadastro de interessado (quando � indicacao por nome)
SessaoSEIExterna::getInstance()->removerAtributo('janelaSelecaoPorNome');

//$arrTipoConferencia = ;
$urlBaseLink = "";
$arrComandos = array();
$arrComandos[] = '<button type="button" accesskey="p" name="Peticionar" id="Peticionar" value="Peticionar" onclick="abrirPeticionar()" class="infraButton"><span class="infraTeclaAtalho">P</span>eticionar</button>';
$arrComandos[] = '<button type="button" accesskey="v" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\''.PaginaSEIExterna::getInstance()->formatarXHTML(SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=peticionamento_usuario_externo_iniciar&id_orgao_acesso_externo=0')).'\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
?>