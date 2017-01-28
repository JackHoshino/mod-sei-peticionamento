<?
/**
* ANATEL
*
* 28/06/2016 - criado por marcelo.bezerra - CAST
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class ReciboPeticionamentoIntercorrenteRN extends ReciboPeticionamentoRN {
	
	//m�todo utilizado para gerar recibo ao final do cadastramento de um processo de peticionamento de usuario externo
	protected function montarReciboControlado( $arrParams ){
		
		$reciboDTO = $arrParams[4];
		$arrDocumentos = $arrParams[5];

		//gerando documento recibo (nao assinado) dentro do processo do SEI
		$objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
		
		$arrParametros = $arrParams[0]; //parametros adicionais fornecidos no formulario de peticionamento
		$objUnidadeDTO = $arrParams[1]; //UnidadeDTO da unidade geradora do processo
		$objProcedimentoDTO = $arrParams[2]; //ProcedimentoDTO para vincular o recibo ao processo correto
		$arrParticipantesParametro = $arrParams[3]; //array de ParticipanteDTO
		
		//tentando simular sessao de usuario interno do SEI
		SessaoSEI::getInstance()->setNumIdUnidadeAtual( $objUnidadeDTO->getNumIdUnidade() );
		SessaoSEI::getInstance()->setNumIdUsuario( SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno() );
		
		$grauSigiloDocPrincipal = $arrParametros['grauSigiloDocPrincipal'];
		$hipoteseLegalDocPrincipal = $arrParametros['hipoteseLegalDocPrincipal'];

        $htmlRecibo = $this->gerarHTMLConteudoDocRecibo( $arrParams );

		$protocoloRN = new ProtocoloPeticionamentoRN();
		
		//$numeroDocumento = $protocoloRN->gerarNumeracaoDocumento();
		$idSerieRecibo = $objInfraParametro->getValor('ID_SERIE_RECIBO_MODULO_PETICIONAMENTO');
		
		//=============================================
		//MONTAGEM DO PROTOCOLODTO DO DOCUMENTO
		//=============================================
		
		$protocoloReciboDocumentoDTO = new ProtocoloDTO();
		
		$protocoloReciboDocumentoDTO->setDblIdProtocolo(null);
		$protocoloReciboDocumentoDTO->setStrDescricao( null );
		$protocoloReciboDocumentoDTO->setStrStaNivelAcessoLocal( ProtocoloRN::$NA_PUBLICO );
		//$protocoloReciboDocumentoDTO->setStrProtocoloFormatado( $numeroDocumento );
		//$protocoloReciboDocumentoDTO->setStrProtocoloFormatadoPesquisa( $numeroDocumento );
		$protocoloReciboDocumentoDTO->setNumIdUnidadeGeradora( $objUnidadeDTO->getNumIdUnidade() );
		$protocoloReciboDocumentoDTO->setNumIdUsuarioGerador( SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno() );
		$protocoloReciboDocumentoDTO->setStrStaProtocolo( ProtocoloRN::$TP_DOCUMENTO_GERADO );
		
		$protocoloReciboDocumentoDTO->setStrStaNivelAcessoLocal( ProtocoloRN::$NA_PUBLICO );
		$protocoloReciboDocumentoDTO->setNumIdHipoteseLegal( null );
		$protocoloReciboDocumentoDTO->setStrStaGrauSigilo(null);
					
		$protocoloReciboDocumentoDTO->setDtaGeracao( InfraData::getStrDataAtual() );
		$protocoloReciboDocumentoDTO->setArrObjAnexoDTO(array());
		$protocoloReciboDocumentoDTO->setArrObjRelProtocoloAssuntoDTO(array());
		$protocoloReciboDocumentoDTO->setArrObjRelProtocoloProtocoloDTO(array());
		
		$protocoloReciboDocumentoDTO->setStrStaEstado( ProtocoloRN::$TE_NORMAL );
		$protocoloReciboDocumentoDTO->setArrObjLocalizadorDTO(array());
		$protocoloReciboDocumentoDTO->setArrObjObservacaoDTO( array() );
		$protocoloReciboDocumentoDTO->setArrObjParticipanteDTO( $arrParticipantesParametro );
		$protocoloReciboDocumentoDTO->setNumIdSerieDocumento( $idSerieRecibo );

		//==========================
		//ATRIBUTOS
		//==========================
		$arrRelProtocoloAtributo = AtributoINT::processar(null, null);
		
		$arrObjRelProtocoloAtributoDTO = array();
		
		for($x = 0;$x<count($arrRelProtocoloAtributo);$x++){
			$arrRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
			$arrRelProtocoloAtributoDTO->setStrValor($arrRelProtocoloAtributo[$x]->getStrValor());
			$arrRelProtocoloAtributoDTO->setNumIdAtributo($arrRelProtocoloAtributo[$x]->getNumIdAtributo());
			$arrObjRelProtocoloAtributoDTO[$x] = $arrRelProtocoloAtributoDTO;
		}
		
		$protocoloReciboDocumentoDTO->setArrObjRelProtocoloAtributoDTO($arrObjRelProtocoloAtributoDTO);

		//=============================================
		//MONTAGEM DO DOCUMENTODTO
		//=============================================
					
		//TESTE COMENTADO $documentoBD = new DocumentoBD( $this->getObjInfraIBanco() );
		$docRN = new DocumentoPeticionamentoRN();
		
		$documentoReciboDTO = new DocumentoDTO();
		$documentoReciboDTO->setDblIdDocumento( $protocoloReciboDocumentoDTO->getDblIdProtocolo() );
		$documentoReciboDTO->setDblIdProcedimento( $objProcedimentoDTO->getDblIdProcedimento() );
		$documentoReciboDTO->setNumIdSerie( $idSerieRecibo );
		$documentoReciboDTO->setNumIdUnidadeResponsavel( $objUnidadeDTO->getNumIdUnidade() );
		$documentoReciboDTO->setObjProtocoloDTO( $protocoloReciboDocumentoDTO );
		
		$documentoReciboDTO->setNumIdConjuntoEstilos(null);
		
		$documentoReciboDTO->setNumIdTipoConferencia( null );
		$documentoReciboDTO->setStrNumero(''); //sistema atribui numeracao sequencial automatica						
		$documentoReciboDTO->setStrConteudo( $htmlRecibo );
		
		$documentoReciboDTO->setStrConteudoAssinatura(null);			
		$documentoReciboDTO->setStrCrcAssinatura(null);			
		$documentoReciboDTO->setStrQrCodeAssinatura(null);
		
		$documentoReciboDTO->setStrSinBloqueado('S');			
		
		$documentoReciboDTO->setStrStaDocumento(DocumentoRN::$TD_FORMULARIO_AUTOMATICO);
		
		$documentoReciboDTO->setNumIdTextoPadraoInterno(null);
		$documentoReciboDTO->setStrProtocoloDocumentoTextoBase('');
		
		$documentoReciboDTO = $docRN->gerarRN0003Customizado( $documentoReciboDTO );
				
		return $reciboDTO;

    }
  
    private function gerarHTMLConteudoDocRecibo( $arrParams ){
        $arrParametros      = $arrParams[0]; //parametros adicionais fornecidos no formulario de peticionamento
        $objUnidadeDTO      = $arrParams[1]; //UnidadeDTO da unidade geradora do processo
        $objProcedimentoDTO = $arrParams[2]; //ProcedimentoDTO para vincular o recibo ao processo correto
        $arrParticipantes   = $arrParams[3]; //array de ParticipanteDTO
        $reciboDTO          = $arrParams[4]; //ReciboPeticionamentoDTO
        $arrDocumentos      = $arrParams[5]; //ReciboPeticionamentoDTO

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->retTodos();
        $objUsuarioDTO->setNumIdUsuario( $reciboDTO->getNumIdUsuario() );

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489( $objUsuarioDTO );

        $html = '';

        $html .= '<table align="center" style="width: 90%" border="0">';
        $html .= '<tbody><tr>';
        $html .= '<td style="font-weight: bold; width: 300px;">Usu�rio Externo (signat�rio):</td>';
        $html .= '<td>' . $objUsuarioDTO->getStrNome() . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">IP utilizado: </td>';
        $html .= '<td>' . $reciboDTO->getStrIpUsuario() .'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">Data e Hor�rio:</td>';
        $html .= '<td>' . $reciboDTO->getDthDataHoraRecebimentoFinal() .  '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">Tipo de Peticionamento:</td>';
        $html .= '<td>' . $reciboDTO->getStrStaTipoPeticionamentoFormatado() . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">N�mero do Processo:</td>';
        $html .= '<td>' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() .  '</td>';
        $html .= '</tr>';

        //obter interessados (apenas os do tipo interessado, nao os do tipo remetente)
        $arrInteressados = array();
        /*
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->setDblIdProtocolo( $reciboDTO->getNumIdProtocolo() );
        $objParticipanteDTO->setStrStaParticipacao( ParticipanteRN::$TP_INTERESSADO );
        $objParticipanteDTO->retNumIdContato();
        */
        $objParticipanteRN = new ParticipanteRN();
        //$arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

        foreach ($arrParticipantes as $objParticipanteDTO) {
            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setNumIdContato($objParticipanteDTO->getNumIdContato());
            $objContatoDTO->retStrNome();
            $objContatoRN      = new ContatoRN();
            $arrInteressados[] = $objContatoRN->consultarRN0324($objContatoDTO);
        }

        $html .= '<tr>';
        $html .= '<td colspan="2" style="font-weight: bold;">Interessados:</td>';
        $html .= '</tr>';

        if( $arrInteressados != null && count( $arrInteressados ) > 0 ){
            foreach ($arrInteressados as $interessado) {
               $html .= '<tr>';
               $html .= '<td colspan="2" >&nbsp&nbsp&nbsp&nbsp ' . $interessado->getStrNome() . '</td>';
               $html .= '</tr>';
             }
        }

        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;">Protocolos dos Documentos (N�mero SEI):</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        /*
        $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($arrParametros['hdnTbDocumento']);
        ob_start();
        var_dump($arr);
        var_dump($arrParams);
        $dump = ob_get_contents();
        ob_end_clean();

        $html .= $dump;
        */
        if( $arrDocumentos != null && count( $arrDocumentos ) > 0  ){
          foreach($arrDocumentos as $documentoDTO){
            $strNumeroSEI = $documentoDTO->getStrProtocoloDocumentoFormatado();
            //concatenar tipo e complemento
            $strNome = $documentoDTO->getStrNomeSerie() . ' ' . $documentoDTO->getStrNumero();
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - ' . $strNome . '</td>';
            $html .= '<td>' . $strNumeroSEI . '</td>';
            $html .= '</tr>';
          }
        }

        $html .= '</tbody></table>';

        $orgaoRN = new OrgaoRN();
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->retTodos();
        $objOrgaoDTO->setNumIdOrgao( $objUnidadeDTO->getNumIdOrgao() );
        $objOrgaoDTO->setStrSinAtivo('S');
        $objOrgaoDTO = $orgaoRN->consultarRN1352( $objOrgaoDTO );

        $html .= '<p>O Usu�rio Externo acima identificado foi previamente avisado que o peticionamento importa na aceita��o dos termos e condi��es que regem o processo eletr�nico, al�m do disposto no credenciamento pr�vio, e na assinatura dos documentos nato-digitais e declara��o de que s�o aut�nticos os digitalizados, sendo respons�vel civil, penal e administrativamente pelo uso indevido. Ainda, foi avisado que os n�veis de acesso indicados para os documentos estariam condicionados � an�lise por servidor p�blico, que poder�, motivadamente, alter�-los a qualquer momento sem necessidade de pr�vio aviso, e de que s�o de sua exclusiva responsabilidade:</p><ul><li>a conformidade entre os dados informados e os documentos;</li><li>a conserva��o dos originais em papel de documentos digitalizados at� que decaia o direito de revis�o dos atos praticados no processo, para que, caso solicitado, sejam apresentados para qualquer tipo de confer�ncia;</li><li>a realiza��o por meio eletr�nico de todos os atos e comunica��es processuais com o pr�prio Usu�rio Externo ou, por seu interm�dio, com a entidade porventura representada;</li><li>a observ�ncia de que os atos processuais se consideram realizados no dia e hora do recebimento pelo SEI, considerando-se tempestivos os praticados at� as 23h59min59s do �ltimo dia do prazo, considerado sempre o hor�rio oficial de Bras�lia, independente do fuso hor�rio em que se encontre;</li><li>a consulta peri�dica ao SEI, a fim de verificar o recebimento de intima��es eletr�nicas.</li></ul><p>A exist�ncia deste Recibo, do processo e dos documentos acima indicados pode ser conferida no Portal na Internet do(a) ' . $objOrgaoDTO->getStrDescricao() . '.</p>';
        return $html;
  }


	protected function gerarReciboSimplificadoIntercorrenteControlado($arr) {
		if(is_array($arr)){

			$idProcedimento    = array_key_exists('idProcedimento', $arr) ? $arr['idProcedimento'] : null;
			$idProcedimentoRel = array_key_exists('idProcedimentoRel', $arr) ? $arr['idProcedimentoRel'] : null;

				if(!is_null($idProcedimento))
				{
					$reciboDTO = new ReciboPeticionamentoDTO();

					$reciboDTO->setNumIdProtocolo( $idProcedimento );
					$reciboDTO->setNumIdUsuario( SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno() );
					$reciboDTO->setDthDataHoraRecebimentoFinal( InfraData::getStrDataHoraAtual() );
					$reciboDTO->setStrIpUsuario( InfraUtil::getStrIpUsuario() );
					$reciboDTO->setStrSinAtivo('S');
					$reciboDTO->setStrStaTipoPeticionamento('I');

                    if(!is_null($idProcedimentoRel)){
						$reciboDTO->setDblIdProtocoloRelacionado($idProcedimentoRel);
					}

					$objBD = new ReciboPeticionamentoBD($this->getObjInfraIBanco());
					$ret = $objBD->cadastrar( $reciboDTO );
					return $ret;
				}
		}

		return null;
	}

}
?>