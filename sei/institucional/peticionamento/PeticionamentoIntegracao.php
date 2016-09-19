<?
/**
 * ANATEL
 *
 * 21/06/2016 - criado por marcelo.bezerra@cast.com.br - CAST
 *
 */
class PeticionamentoIntegracao extends SeiIntegracao {
		
	public function __construct(){
	}
		
	public function montarMenuUsuarioExterno(){ 
				
		$menuExternoRN = new MenuPeticionamentoUsuarioExternoRN();
		$menuExternoDTO = new MenuPeticionamentoUsuarioExternoDTO();
		$menuExternoDTO->retTodos();
		$menuExternoDTO->setStrSinAtivo('S');
		
		$menuExternoDTO->setOrd("Nome", InfraDTO::$TIPO_ORDENACAO_ASC);

		$objLista = $menuExternoRN->listar( $menuExternoDTO );		
		$numRegistros = count($objLista);
		
		//utilizado para ordena��o
		$urlBase = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');
		$arrMenusNomes = array();
		
		//$arrMenusNomes["Peticionar Processo Inicio"] = $urlBase .'/controlador_externo.php?acao=peticionamento_usuario_externo_iniciar';
		$arrMenusNomes["Peticionamento"] = $urlBase .'/controlador_externo.php?acao=peticionamento_usuario_externo_iniciar';
		
		$arrMenusNomes["Recibos Eletr�nicos de Protocolo"] = $urlBase .'/controlador_externo.php?acao=recibo_peticionamento_usuario_externo_listar';
		
		if( is_array( $objLista ) && $numRegistros > 0 ){
			
			for($i = 0;$i < $numRegistros; $i++){
			
			 $item = $objLista[$i];
			 	
		  	 if( $item->getStrTipo() == MenuPeticionamentoUsuarioExternoRN::$TP_EXTERNO ) {
		  	 	$link = "javascript:";
		  	 	$link .= "var a = document.createElement('a'); ";
				$link .= "a.href='" . $item->getStrUrl() ."'; ";
				$link .= "a.target = '_blank'; ";
				$link .= "document.body.appendChild(a); ";
				$link .= "a.click(); ";
				$arrMenusNomes[$item->getStrNome()] = $link; 
		  	 }
		  	 
		  	 else if( $item->getStrTipo() == MenuPeticionamentoUsuarioExternoRN::$TP_CONTEUDO_HTML ) {
		  	 	
		  	 	$idItem = $item->getNumIdMenuPeticionamentoUsuarioExterno();		  	 	
		  	 	$strLinkMontado = SessaoSEIExterna::getInstance()->assinarLink($urlBase . '/controlador_externo.php?acao=pagina_conteudo_externo_peticionamento&id_md_pet_usu_externo_menu='. $idItem);
		  	 	$arrMenusNomes[$item->getStrNome()] = $strLinkMontado;
		  	 	
		  	 }
		  	
		  }
		}
		
		$arrLink = array();		
		$numRegistrosMenu = count($arrMenusNomes);
		
		if( is_array( $arrMenusNomes ) && $numRegistrosMenu > 0 ){
				
		    foreach ( $arrMenusNomes as $key => $value) {
		    	$urlLink = $arrMenusNomes[ $key ];
		    	$nomeMenu = $key;
		    	if($nomeMenu=='Peticionamento'){
		    		$arrLink[] = '-^^^' . $nomeMenu .'^';
		    		$arrLink[] = '--^' . $urlLink .'^^' . 'Processo Novo' .'^';	
		    	}else{
		    		$arrLink[] = '-^' . $urlLink .'^^' . $nomeMenu .'^';	
		    	}
		    	
		    }
		}

		return $arrLink; 
	}
}
?>