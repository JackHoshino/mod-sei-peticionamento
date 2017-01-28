<?
/**
* ANATEL
*
* 15/06/2016 - criado por marcelo.bezerra - CAST
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MenuPeticionamentoUsuarioExternoRN extends InfraRN { 
	
	public static $TP_EXTERNO = 'E';
	public static $TP_CONTEUDO_HTML = 'H';
	
	public function __construct() {
		parent::__construct ();
	}
	
	protected function inicializarObjInfraIBanco() {
		return BancoSEI::getInstance ();
	}
	
	/**
	 * Short description of method listarConectado
	 *
	 * @access protected
	 * @author Marcelo Bezerra <marcelo.bezerra@cast.com.br>
	 * @param $objMenuPeticionamentoUsuarioExternoDTO
	 * @return mixed
	 */
	protected function listarConectado(MenuPeticionamentoUsuarioExternoDTO $objMenuPeticionamentoUsuarioExternoDTO) {
	
		try {
					
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			$ret = $objMenuPeticionamentoUsuarioExternoBD->listar($objMenuPeticionamentoUsuarioExternoDTO);
	
			return $ret;
		} catch (Exception $e) {
			throw new InfraException ('Erro listando Menu Peticionamento.', $e);
		}
	}
	
	/**
	 * Short description of method consultarConectado
	 *
	 * @access protected
	 * @author Marcelo Bezerra <marcelo.bezerra@cast.com.br>
	 * @param  $objMenuPeticionamentoUsuarioExternoDTO
	 * @return mixed
	 */
	protected function consultarConectado(MenuPeticionamentoUsuarioExternoDTO $objMenuPeticionamentoUsuarioExternoDTO) {
		try {
				
			// Valida Permissao
				
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			$ret = $objMenuPeticionamentoUsuarioExternoBD->consultar($objMenuPeticionamentoUsuarioExternoDTO);
				
			return $ret;
		} catch ( Exception $e ) {
			throw new InfraException('Erro consultando Menu Peticionamento.', $e);
		}
	}
	
	/**
	 * Short description of method desativarControlado
	 *
	 * @access protected
	 * @author Marcelo Bezerra <marcelo.bezerra@cast.com.br>
	 * @param  $arrMenuPeticionamentoUsuarioExternoDTO
	 * @return void
	 */
	protected function desativarControlado($arrMenuPeticionamentoUsuarioExternoDTO) {
	
		try {
	
			SessaoSEI::getInstance ()->validarAuditarPermissao('menu_peticionamento_usuario_externo_desativar', __METHOD__, $arrMenuPeticionamentoUsuarioExternoDTO);
				
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			for($i = 0; $i < count($arrMenuPeticionamentoUsuarioExternoDTO); $i ++) {
				$objMenuPeticionamentoUsuarioExternoBD->desativar($arrMenuPeticionamentoUsuarioExternoDTO[$i]);
			}
	
		} catch(Exception $e) {
			throw new InfraException ('Erro desativando Menu Peticionamento.', $e );
		}
	}
	
	/**
	 * Short description of method reativarControlado
	 *
	 * @access protected
	 * @author Marcelo Bezerra <marcelo.bezerra@cast.com.br>
	 * @param  $arrMenuPeticionamentoUsuarioExternoDTO
	 * @return void
	 */
	protected function reativarControlado($arrMenuPeticionamentoUsuarioExternoDTO) {
	
		try {
	
			SessaoSEI::getInstance ()->validarAuditarPermissao('menu_peticionamento_usuario_externo_reativar', __METHOD__, $arrMenuPeticionamentoUsuarioExternoDTO);
	
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			for($i = 0; $i < count($arrMenuPeticionamentoUsuarioExternoDTO); $i ++) {
				$objMenuPeticionamentoUsuarioExternoBD->reativar($arrMenuPeticionamentoUsuarioExternoDTO[$i]);
			}
	
		} catch(Exception $e) {
			throw new InfraException ('Erro reativando Menu Peticionamento.', $e );
		}
	}
	
	/**
	 * Short description of method excluirControlado
	 *
	 * @access protected
	 * @author Marcelo Bezerra <marcelo.bezerra@cast.com.br>
	 * @param  $arrMenuPeticionamentoUsuarioExternoDTO
	 * @return void
	 */
	protected function excluirControlado($arrMenuPeticionamentoUsuarioExternoDTO) {
	
		try {
	
			SessaoSEI::getInstance ()->validarAuditarPermissao('menu_peticionamento_usuario_externo_excluir', __METHOD__, $arrMenuPeticionamentoUsuarioExternoDTO);
			$relPeticionamentoSerieRN = new RelTipoProcessoSeriePeticionamentoRN();
				
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
				
			for($i = 0; $i < count($arrMenuPeticionamentoUsuarioExternoDTO); $i ++) {
	
				//removendo menu
				$objMenuPeticionamentoUsuarioExternoBD->excluir($arrMenuPeticionamentoUsuarioExternoDTO[$i] );
			}
	
		} catch(Exception $e) {
			throw new InfraException ('Erro excluindo Menu Peticionamento.', $e );
		}
	}
	
	/**
	 * Short description of method cadastrarControlado
	 *
	 * @access protected
	 * @author Alan Campos <alan.campos@castgroup.com.br>
	 * @param  $objMenuPeticionamentoUsuarioExternoDTO
	 * @return mixed
	 */
	protected function cadastrarControlado(MenuPeticionamentoUsuarioExternoDTO $objMenuPeticionamentoUsuarioExternoDTO) {
		try {
			// Valida Permissao
			SessaoSEI::getInstance ()->validarAuditarPermissao ('menu_peticionamento_usuario_externo_cadastrar', __METHOD__, $objMenuPeticionamentoUsuarioExternoDTO );
	
			$objInfraException = new InfraException();
				
			$this->_validarCamposObrigatorios($objMenuPeticionamentoUsuarioExternoDTO, $objInfraException);
			$this->_validarDuplicidade($objMenuPeticionamentoUsuarioExternoDTO, $objInfraException, true);				
			$objInfraException->lancarValidacoes();
				
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			$objMenuPeticionamentoUsuarioExternoDTO->setStrSinAtivo('S');
			$ret = $objMenuPeticionamentoUsuarioExternoBD->cadastrar($objMenuPeticionamentoUsuarioExternoDTO);
	
			return $ret;
		} catch ( Exception $e ) {
			throw new InfraException ('Erro cadastrando Menu.', $e );
		}
	}
	
	/**
	 * Short description of method alterarControlado
	 *
	 * @access protected
	 * @author Alan Campos <alan.campos@castgroup.com.br>
	 * @param  $objMenuPeticionamentoUsuarioExternoDTO
	 * @return mixed
	 */
	protected function alterarControlado(MenuPeticionamentoUsuarioExternoDTO $objMenuPeticionamentoUsuarioExternoDTO) {
		try {
			// Valida Permissao
			SessaoSEI::getInstance ()->validarAuditarPermissao ('menu_peticionamento_usuario_externo_alterar', __METHOD__, $objMenuPeticionamentoUsuarioExternoDTO );
	
			$objInfraException = new InfraException();
	
			$this->_validarCamposObrigatorios($objMenuPeticionamentoUsuarioExternoDTO, $objInfraException);
			$this->_validarDuplicidade($objMenuPeticionamentoUsuarioExternoDTO, $objInfraException, false);	
			$objInfraException->lancarValidacoes();
	
			$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());
			$ret = $objMenuPeticionamentoUsuarioExternoBD->alterar($objMenuPeticionamentoUsuarioExternoDTO);
	
			return $ret;
		} catch ( Exception $e ) {
			throw new InfraException ('Erro cadastrando Menu.', $e );
		}
	}
	
	/**
	 * Short description of method _validarCamposObrigatorios
	 *
	 * @access private
	 * @author Marcelo Bezerra <marcelo.bezerra@castgroup.com.br>
	 * @param  $objMenuPeticionamentoUsuarioExternoDTO
	 * @param  $objInfraException
	 * @return mixed
	 */
	private function _validarCamposObrigatorios($objMenuPeticionamentoUsuarioExternoDTO, $objInfraException){
	
		//Nome do Menu
		if (InfraString::isBolVazia ($objMenuPeticionamentoUsuarioExternoDTO->getStrNome())) {
			$objInfraException->adicionarValidacao('Nome do Menu n�o informado.');
		} else if( strlen($objMenuPeticionamentoUsuarioExternoDTO->getStrNome()) > 30 ){
			$objInfraException->adicionarValidacao('Nome do Menu possui tamanho superior a 30 caracteres.');
		}
		
		//Tipo de Menu
		if (InfraString::isBolVazia ($objMenuPeticionamentoUsuarioExternoDTO->getStrTipo())) {
			$objInfraException->adicionarValidacao('Tipo de Menu n�o informado.');
		}elseif ($objMenuPeticionamentoUsuarioExternoDTO->getStrTipo()=='E'){
			//Url
			if (InfraString::isBolVazia ($objMenuPeticionamentoUsuarioExternoDTO->getStrUrl())) {
					$objInfraException->adicionarValidacao('URL de Link Externo n�o informado.');
			} else {
				// RN10 - Validando
				require_once dirname(__FILE__).'/../util/UrlUtils.php';
				$UrlRetorno = UrlUtils::validarStrURL($objMenuPeticionamentoUsuarioExternoDTO->getStrUrl(),$objInfraException,'Tamanho do campo excedido (m�ximo 2083 caracteres).', 'URL do Link Externo inv�lido.');
				if($UrlRetorno!== true){
					$objInfraException->adicionarValidacao($UrlRetorno);
				}
			} 
		}elseif ($objMenuPeticionamentoUsuarioExternoDTO->getStrTipo()=='H'){
			//ConteudoHtml
			if (InfraString::isBolVazia ($objMenuPeticionamentoUsuarioExternoDTO->getStrConteudoHtml())) {
				$objInfraException->adicionarValidacao('Conte�do HTML n�o informado.');
			}
		}
		
	}
	
	/**
	 * Short description of method _validarDuplicidade
	 *
	 * @access private
	 * @author Marcelo Bezerra <marcelo.bezerra@castgroup.com.br>
	 * @param  $objMenuPeticionamentoUsuarioExternoDTO
	 * @param  $objInfraException
	 * @param  $cadastrar
	 * @return mixed
	 */
	private function _validarDuplicidade(MenuPeticionamentoUsuarioExternoDTO $objMenuPeticionamentoUsuarioExternoDTO, InfraException $objInfraException, $cadastrar){
		
		// VALIDA DUPLICA��O
		// VALIDACAO A SER EXECUTADA NA INSER�AO DE NOVOS REGISTROS
		//nao permitir nome duplicado
		
		$msg = 'J� existe Menu cadastrado.';
		$objMenuPeticionamentoUsuarioExternoDTO2 = new MenuPeticionamentoUsuarioExternoDTO();
		$objMenuPeticionamentoUsuarioExternoDTO2->setStrNome($objMenuPeticionamentoUsuarioExternoDTO->getStrNome());	
		$objMenuPeticionamentoUsuarioExternoBD = new MenuPeticionamentoUsuarioExternoBD($this->getObjInfraIBanco());	
	
		if ($cadastrar) {
			
			$ret = $objMenuPeticionamentoUsuarioExternoBD->contar($objMenuPeticionamentoUsuarioExternoDTO2);
	
			if ($ret > 0) {
				$objInfraException->adicionarValidacao ($msg);
			}
	
		} else {
	
			$dtoValidacao = new MenuPeticionamentoUsuarioExternoDTO();
			$dtoValidacao->setStrNome($objMenuPeticionamentoUsuarioExternoDTO->getStrNome(), InfraDTO::$OPER_IGUAL);
			$dtoValidacao->setNumIdMenuPeticionamentoUsuarioExterno( $objMenuPeticionamentoUsuarioExternoDTO->getNumIdMenuPeticionamentoUsuarioExterno(), InfraDTO::$OPER_DIFERENTE );
	
			$retDuplicidade = $objMenuPeticionamentoUsuarioExternoBD->contar( $dtoValidacao );
	
			if ($retDuplicidade > 0) {
				$objInfraException->adicionarValidacao($msg);
			}
		}
	}
}
?>