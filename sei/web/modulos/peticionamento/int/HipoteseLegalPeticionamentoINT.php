<?
/**
* ANATEL
*
* 30/08/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class HipoteseLegalPeticionamentoINT extends InfraINT {

  public static function autoCompletarHipoteseLegal($strPalavrasPesquisa, $nivelAcesso = ''){

    $objHipoteseLegalDTO = new HipoteseLegalDTO();
    $objHipoteseLegalDTO->retTodos();
    $objHipoteseLegalDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objHipoteseLegalDTO->setStrNome('%'.$strPalavrasPesquisa. '%', InfraDTO::$OPER_LIKE);
    $objHipoteseLegalDTO->setStrStaNivelAcesso($nivelAcesso);
    $objHipoteseLegalDTO->setStrSinAtivo('S');

    $objHipoteseLegalRN = new HipoteseLegalRN();
    $arrObjHipoteseLegalDTO = $objHipoteseLegalRN->listar($objHipoteseLegalDTO);
    
    foreach($arrObjHipoteseLegalDTO as  $key=>$obj){
    	$arrObjHipoteseLegalDTO[$key]->setStrNome(HipoteseLegalPeticionamentoINT::formatarStrNome($arrObjHipoteseLegalDTO[$key]->getStrNome(), $arrObjHipoteseLegalDTO[$key]->getStrBaseLegal()));
    	//$arrObjHipoteseLegalDTO[$key]->setStrNome()
    }
    
    return $arrObjHipoteseLegalDTO;
  }
	
  public static function formatarStrNome($nome, $baseLegal){
  		return $nome .' ('.$baseLegal.')';
  }
	
}