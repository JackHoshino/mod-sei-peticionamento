<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 08/02/2012 - criado por bcu
*
* Vers�o do Gerador de C�digo: 1.32.1
*
* Vers�o no CVS: $Id$
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class ArquivoExtensaoPeticionamentoINT extends InfraINT {

  /*
   * @author Alan Campos <alan.campos@castgroup.com.br>
   * 
   */
  
  public static function autoCompletarExtensao($strExtensao){
  	  
  	$objArquivoExtensaoPeticionamentoDTO = new ArquivoExtensaoPeticionamentoDTO();
  	$objArquivoExtensaoPeticionamentoDTO->retNumIdArquivoExtensao();
  	$objArquivoExtensaoPeticionamentoDTO->retStrExtensao();
  	$objArquivoExtensaoPeticionamentoDTO->retStrDescricao();
  	  
  	$objArquivoExtensaoPeticionamentoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  	if ($strExtensao!=''){
  		$objArquivoExtensaoPeticionamentoDTO->setStrPalavrasPesquisa($strExtensao);
  	}
  	
  	$objArquivoExtensaoPeticionamentoRN = new ArquivoExtensaoPeticionamentoRN();
  	$arrObjArquivoPeticionamentoDTO = $objArquivoExtensaoPeticionamentoRN->listarAutoComplete($objArquivoExtensaoPeticionamentoDTO);
 
  	return $arrObjArquivoPeticionamentoDTO;
  }
}
?>