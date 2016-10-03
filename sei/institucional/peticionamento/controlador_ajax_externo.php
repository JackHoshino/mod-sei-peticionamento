<?
/**
 * ANATEL
 *
 * 22/07/2016 - criado por marcelo.bezerra@cast.com.br - CAST
 * Arquivo para realizar controle requisi��o ajax de usuario externo no modulo peticionamento.
 */

try{
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();
  	SessaoSEIExterna::getInstance()->validarLink();
    InfraAjax::decodificarPost();
  
 switch($_GET['acao_ajax_externo']){
    
 	case 'contato_pj_vinculada':
 		
 		$objContatoDTO = ContatoINT::obterSugestoesRI0571($_POST['idContextoContato']);
 		$xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objContatoDTO,array('Telefone','Fax','Email','SitioInternet','Endereco','Bairro','SiglaEstado','NomeCidade','NomePais','Cep'));
 		break;
 	
  	case 'contato_auto_completar_contexto_pesquisa':
  		//alterado para atender anatel exibir apenas nome contato
  		$objContatoDTO = new ContatoDTO();
  		$objContatoDTO->retNumIdContato();
  		$objContatoDTO->retStrSigla();
  		$objContatoDTO->retStrNome();
  		
  		$objContatoDTO->setStrPalavrasPesquisa($_POST['extensao']);
  		$objContatoDTO->setStrNome("%".$_POST['extensao']."%", InfraDTO::$OPER_LIKE);
  		
  		$objContatoDTO->adicionarCriterio(array('SinAtivo','Nome'),
  				array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_LIKE),
  				array('S', "%".$_POST['extensao']."%" ),
  				InfraDTO::$OPER_LOGICO_OR);
  		
  		$objContatoDTO->setStrSinContexto('S');
  		$objContatoDTO->setNumMaxRegistrosRetorno(50);
  		$objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objRelTipoContextoPeticionamentoDTO = new RelTipoContextoPeticionamentoDTO();
        $objRelTipoContextoPeticionamentoRN = new GerirTipoContextoPeticionamentoRN();
        $objRelTipoContextoPeticionamentoDTO->retTodos();
        $objRelTipoContextoPeticionamentoDTO->setStrSinSelecaoInteressado('S');
        $arrobjRelTipoContextoPeticionamentoDTO = $objRelTipoContextoPeticionamentoRN->listar( $objRelTipoContextoPeticionamentoDTO );
        if(!empty($arrobjRelTipoContextoPeticionamentoDTO)){
            $arrId = array();
            foreach($arrobjRelTipoContextoPeticionamentoDTO as $item){
                array_push($arrId, $item->getNumIdTipoContextoContato());
            }
            $objContatoDTO->adicionarCriterio(array('IdTipoContextoContato'),
                array(InfraDTO::$OPER_IN),
                array($arrId));
        }

        $objContatoRN = new ContatoRN();
        $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);

  		$objContatoRN = new ContatoRN();  		
  		//$arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);
  		$arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);
  		
  		$xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjContatoDTO,'IdContato', 'Nome');
  		InfraAjax::enviarXML($xml);
  		break;
      
   default:
      throw new InfraException("A��o '".$_GET['acao_ajax_externo']."' n�o reconhecida pelo controlador AJAX externo.");
  }
  
}catch(Exception $e){
	//LogSEI::getInstance()->gravar('ERRO AJAX: '.$e->__toString());
  InfraAjax::processarExcecao($e);
}
?>