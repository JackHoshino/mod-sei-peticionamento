<?
    /**
     * ANATEL
     *
     * 25/11/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdPetIntercorrenteINT extends InfraINT
    {

        /**
         * Fun��o respons�vel por gerar o XML para valida��o do n�mero Processo
         * @param int $numeroProcesso
         * @return string
         * @since  28/11/2016
         * @author Jaqueline Mendes jaqueline.mendes@castgroup.com.br
         */
        public static function gerarXMLvalidacaoNumeroProcesso($numeroProcesso)
        {

            $objMdPetIntercorrenteRN = new MdPetIntercorrenteProcessoRN();
            $objProtocoloDTO         = new ProtocoloDTO();
            $objProtocoloDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($numeroProcesso, false));
            $objProtocoloDTO = $objMdPetIntercorrenteRN->pesquisarProtocoloFormatado($objProtocoloDTO);

            $xml = '<Validacao>';

            if (!is_null($objProtocoloDTO)) {
                $objProcedimentoDTO = new ProcedimentoDTO();
                $objProcedimentoRN  = new ProcedimentoRN();
                $objProcedimentoDTO->setDblIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
                $objProcedimentoDTO->retTodos(true);
                $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
                $msg                = '';

                if (!is_null($objProcedimentoDTO)) {

                    $unidadeValida  = $objMdPetIntercorrenteRN->validarUnidadeProcesso($objProcedimentoDTO);
                    $idProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

                    if ($unidadeValida) {
                        $idTpProcedimento = $objProcedimentoDTO->getNumIdTipoProcedimento();
                        $tpProcedimento   = $objProcedimentoDTO->getStrNomeTipoProcedimento();

                        $objCriterioIntercorrenteDTO = new CriterioIntercorrentePeticionamentoDTO();
                        $objCriterioIntercorrenteRN  = new CriterioIntercorrentePeticionamentoRN();
                        $objCriterioIntercorrenteDTO->setNumIdTipoProcedimento($idTpProcedimento);
                        $objCriterioIntercorrenteDTO->setStrSinCriterioPadrao('N');
                        $objCriterioIntercorrenteDTO->retTodos(true);

                        $count                 = $objCriterioIntercorrenteRN->contar($objCriterioIntercorrenteDTO);
                        $processoIntercorrente = $count > 0 ? 'Direto no Processo Indicado.' : 'Em Processo Novo Relacionado ao Processo indicado';

                        $urlValida = PaginaSEIExterna::getInstance()->formatarXHTML(SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?id_procedimento=' . $idProcedimento . '&id_tipo_procedimento=' . $idTpProcedimento . '&acao=md_pet_intercorrente_usu_ext_assinar&tipo_selecao=2'));

                        $xml .= '<IdTipoProcedimento>' . $idTpProcedimento . '</IdTipoProcedimento>';
                        $xml .= '<IdProcedimento>' . $idProcedimento . '</IdProcedimento>';
                        $xml .= '<numeroProcesso>' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '</numeroProcesso>';
                        $xml .= '<TipoProcedimento> ' . $tpProcedimento . ' </TipoProcedimento>';
                        $xml .= '<ProcessoIntercorrente>' . $processoIntercorrente . '</ProcessoIntercorrente>';
                        $xml .= '<UrlValida>' . htmlentities($urlValida) . '</UrlValida>';

                    } else {
                        $msg = 'O processo indicado n�o aceita peticionamento intercorrente. Utilize o Peticionamento de Processo Novo para protocolizar sua demanda.';
                        //$xml .= '<MensagemValidacao>. $msg.</MensagemValidacao>';
                    }
                } else {
                    $msg = 'O n�mero de processo indicado n�o existe no sistema. Verifique se o n�mero est� correto e completo, inclusive com o D�gito Verificador.';
                }
            } else {
                $msg = 'O n�mero de processo indicado n�o existe no sistema. Verifique se o n�mero est� correto e completo, inclusive com o D�gito Verificador.';
            }

            if ($msg != '') {
                $xml .= '<MensagemValidacao>' . $msg . '</MensagemValidacao>';
            }

            $xml .= '</Validacao>';

            return $xml;
        }


        /**
         * Fun��o respons�vel por montar os options do select "Confer�ncia com o documento digitalizado"
         * @param $strPrimeiroItemValor
         * @param $strPrimeiroItemDescricao
         * @param $strValorItemSelecionado
         * @return string
         * @since  29/11/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function montarSelectTipoConferencia($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {
            $objTipoConferenciaDTO = new TipoConferenciaDTO();
            $objTipoConferenciaDTO->retNumIdTipoConferencia();
            $objTipoConferenciaDTO->retStrDescricao();
            $objTipoConferenciaDTO->setStrSinAtivo('S');
            $objTipoConferenciaDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objTipoConferenciaRN     = new TipoConferenciaRN();
            $arrObjTipoConferenciaDTO = $objTipoConferenciaRN->listar($objTipoConferenciaDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoConferenciaDTO, 'IdTipoConferencia', 'Descricao');
        }


        /**
         * Fun��o respons�vel por montar os options do select "Tipo de Documento"
         * @param $strPrimeiroItemValor
         * @param $strPrimeiroItemDescricao
         * @param $strValorItemSelecionado
         * @return string
         * @since  29/11/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function montarSelectTipoDocumento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {

            $objSerieRN  = new SerieRN();
            $objSerieDTO = new SerieDTO();
            $objSerieDTO->retTodos(true);
            $objSerieDTO->setStrSinAtivo('S');
            $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objSerieDTO->adicionarCriterio(array('StaAplicabilidade'),
                                            array(InfraDTO::$OPER_IN),
                                            array(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_EXTERNO)));

            $arrSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);


            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrSerieDTO, 'IdSerie', 'Nome');
        }

        /**
         * Fun��o respons�vel por retornoar o tamanho maximo permitido para upload
         * Configura��o realizada em Administra��o > Peticionamento Eletr�nico > Tamanho M�ximo de Arquivos
         * @return string
         * @since  29/11/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function tamanhoMaximoArquivoPermitido()
        {
            $tamanhoMaximo          = "Limite n�o configurado na Administra��o do Sistema.";
            $objTamanhoPermitidoDTO = new TamanhoArquivoPermitidoPeticionamentoDTO();
            $objTamanhoPermitidoDTO->setNumIdTamanhoArquivo(TamanhoArquivoPermitidoPeticionamentoRN::$ID_FIXO_TAMANHO_ARQUIVO);
            $objTamanhoPermitidoDTO->setStrSinAtivo('S');
            $objTamanhoPermitidoDTO->retNumValorDocComplementar();
            $objTamanhoPermitidoRN  = new TamanhoArquivoPermitidoPeticionamentoRN();
            $arrTamanhoPermitidoDTO = $objTamanhoPermitidoRN->listarTamanhoMaximoConfiguradoParaUsuarioExterno($objTamanhoPermitidoDTO);
            $objTamanhoPermitidoDTO = reset($arrTamanhoPermitidoDTO);
            if ($objTamanhoPermitidoDTO) {
                $tamanhoMaximo = (int)$objTamanhoPermitidoDTO->getNumValorDocComplementar();
            }

            return $tamanhoMaximo;
        }

        /**
         * Fun��o respons�vel por verificar se a hipotese legal vai ser exibida ou n�o no fieldset Documentos
         * SOMENTE deve ser exibido SE no Infra > Par�metros a op��o SEI_HABILITAR_HIPOTESE_LEGAL estiver configurado
         * como 1 ou 2.
         * @return string
         * @since  05/12/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function verificarHipoteseLegal()
        {
            $objInfraParametroDTO = new InfraParametroDTO();
            $objInfraParametroDTO->setStrNome('SEI_HABILITAR_HIPOTESE_LEGAL');
            $objInfraParametroDTO->retTodos();
            $objInfraParametroRN = new InfraParametroRN();

            $objInfraParametroDTO = $objInfraParametroRN->consultar($objInfraParametroDTO);

            return $objInfraParametroDTO->isSetStrValor() &&
            ($objInfraParametroDTO->getStrValor() == 1 || $objInfraParametroDTO->getStrValor() == 2);
        }


        /**
         * Fun��o respons�vel por verificar se existe criterio intercorrente cadastrado ou intercorrente padr�o
         * cadastrado.
         * @param $idTipoProcessoPeticionamento
         * @return array
         * @since  06/12/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function verificarCriterioIntercorrente($idTipoProcessoPeticionamento)
        {

            //Verifica se tem criterio intercorrente cadastrado;
            $objCriterioIntercorrenteDTO = new CriterioIntercorrentePeticionamentoDTO();
            $objCriterioIntercorrenteDTO->setNumIdTipoProcedimento($idTipoProcessoPeticionamento);
            $objCriterioIntercorrenteDTO->setStrSinCriterioPadrao('N');
            $objCriterioIntercorrenteDTO->setStrSinAtivo('S');
            $objCriterioIntercorrenteDTO->retTodos(true);

            $objCriterioIntercorrenteRN  = new CriterioIntercorrentePeticionamentoRN();
            $objCriterioIntercorrenteDTO = $objCriterioIntercorrenteRN->consultar($objCriterioIntercorrenteDTO);

            //Se n�o tem criterio intercorrente cadastrado, verifica se tem interorrente padr�o cadastrado.
            if (is_null($objCriterioIntercorrenteDTO)) {
                $objCriterioIntercorrenteDTO = new CriterioIntercorrentePeticionamentoDTO();
                $objCriterioIntercorrenteDTO->setNumIdTipoProcedimento($idTipoProcessoPeticionamento);
                $objCriterioIntercorrenteDTO->setStrSinCriterioPadrao('S');
                $objCriterioIntercorrenteDTO->setStrSinAtivo('S');
                $objCriterioIntercorrenteDTO->retTodos(true);

                $objCriterioIntercorrenteRN  = new CriterioIntercorrentePeticionamentoRN();
                $objCriterioIntercorrenteDTO = $objCriterioIntercorrenteRN->consultar($objCriterioIntercorrenteDTO);
            }


            $arrRetorno = array();
            if (!is_null($objCriterioIntercorrenteDTO)) {

                $arrDescricaoNivelAcesso = ['P' => 'P�blico', 'I' => 'Restrito'];
                $arrIdNivelAcesso        = ['P' => 0, 'I' => 1];

                if ($objCriterioIntercorrenteDTO->getStrStaNivelAcesso() == 2) { //2 = Padr�o Pr�-definido
                    $descricaoNivel = $arrDescricaoNivelAcesso[$objCriterioIntercorrenteDTO->getStrStaTipoNivelAcesso()];

                    $arrRetorno['nivelAcesso'] = array(
                        'id'        => $arrIdNivelAcesso[$objCriterioIntercorrenteDTO->getStrStaTipoNivelAcesso()],
                        'descricao' => utf8_encode($descricaoNivel)
                    );

                    if ($objCriterioIntercorrenteDTO->getStrStaTipoNivelAcesso() == 'I') {// I = Restrito
                        $descricaoHipotese = $objCriterioIntercorrenteDTO->getStrNomeHipoteseLegal() .
                            ' (' . $objCriterioIntercorrenteDTO->getStrBaseLegalHipoteseLegal() . ')';

                        $arrRetorno['hipoteseLegal'] = array(
                            'id'        => $objCriterioIntercorrenteDTO->getNumIdHipoteseLegal(),
                            'descricao' => utf8_encode($descricaoHipotese)
                        );

                    }
                }

            }

            return $arrRetorno;

        }

        /**
         * Fun��o respons�vel por montar os options do select "Hip�tese Legal"
         * @param $objEntradaListarHipotesesLegaisAPI
         * @return string
         * @since  08/12/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function montarSelectHipoteseLegal($objEntradaListarHipotesesLegaisAPI)
        {

            $seiRN                  = new SeiRN();
            $arrObjHipoteseLegalAPI = $seiRN->listarHipotesesLegais($objEntradaListarHipotesesLegaisAPI);
            $strOptions             = '<option value="null"> </option>';

            foreach ($arrObjHipoteseLegalAPI as $objHipoteseLegalAPI) {
                $nomeBaseLegal = $objHipoteseLegalAPI->getNome() . ' (' . $objHipoteseLegalAPI->getBaseLegal() . ')';
                $strOptions .= '<option value="' . $objHipoteseLegalAPI->getIdHipoteseLegal() . '">';
                $strOptions .= $nomeBaseLegal;
                $strOptions .= '</option>';
            }

            return InfraAjax::gerarXMLSelect($strOptions);
        }

        /**
         * Fun��o respons�vel por montar o array com os documentos que foram adicionados na grid da tela Peticionamento
         * Interorrente
         * @param $idProcedimento
         * @param $hdnTabelaDinamicaDocumento
         * @return  array $arrDocumentoAPI
         * @since  15/12/2016
         * @author Andr� Luiz <andre.luiz@castgroup.com.br>
         */
        public static function montarArrDocumentoAPI($idProcedimento, $hdnTabelaDinamicaDocumento)
        {

            $objRemetenteAPI = new RemetenteAPI();
            $objRemetenteAPI->setNome(SessaoSEIExterna::getInstance()->getStrNomeUsuarioExterno());
            $objRemetenteAPI->setSigla(SessaoSEIExterna::getInstance()->getStrSiglaUsuarioExterno());

            /*
            $objDestinatarioAPI = new DestinatarioAPI();
            $objDestinatarioAPI->set
            */

            $arrItensTbDocumento = PaginaSEIExterna::getInstance()->getArrItensTabelaDinamica($hdnTabelaDinamicaDocumento);
            $arrDocumentoAPI     = array();

            foreach ($arrItensTbDocumento as $itemTbDocumento) {
                $documentoAPI = new DocumentoAPI();
                $documentoAPI->setIdProcedimento($idProcedimento);
                $documentoAPI->setIdSerie($itemTbDocumento[1]);
                $documentoAPI->setDescricao($itemTbDocumento[2]);
                $documentoAPI->setNivelAcesso($itemTbDocumento[3]);
                $documentoAPI->setIdHipoteseLegal($itemTbDocumento[4]);
                //$documentoAPI->setCampos($itemTbDocumento[5]);
                $documentoAPI->setIdTipoConferencia($itemTbDocumento[6]);
                $documentoAPI->setNomeArquivo($itemTbDocumento[9]);
                $documentoAPI->setTipo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
                $documentoAPI->setConteudo(base64_encode(file_get_contents(DIR_SEI_TEMP . '/' . $itemTbDocumento[7])));
                $documentoAPI->setData(InfraData::getStrDataAtual());
                $documentoAPI->setIdArquivo($itemTbDocumento[7]);
                $documentoAPI->setSinAssinado('S');
                $documentoAPI->setSinBloqueado('S');
                $documentoAPI->setRemetente($objRemetenteAPI);

                //$documentoAPI->setDestinatarios();

                $arrDocumentoAPI[] = $documentoAPI;
            }

            return $arrDocumentoAPI;
        }

        /**
         * Fun��o respons�vel por Retornar o Id do Anexo Salvo
         * @param SaidaIncluirDocumentoAPI $ret
         * @return  string $idAnexo
         * @since  20/12/2016
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         */
        public static function retornaIdAnexo($idDocumento){

            $arrObjAnexoDTO = array();
            $objAnexoDTO = new AnexoDTO();
            $objAnexoDTO->retNumIdAnexo();
            $objAnexoDTO->setDblIdProtocolo($idDocumento);
            $objAnexoRN =  new AnexoRN();
            $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);
            $objAnexoDTO = count($arrObjAnexoDTO) > 0 ? current($arrObjAnexoDTO) : null;

            $idAnexo = $objAnexoDTO->getNumIdAnexo();
            return $idAnexo;
        }

        /**
         * Fun��o respons�vel por gerar o XML para valida��o do n�mero Processo
         * @param array $params
         * @return ReciboDocumentoAnexoPeticionamentoDTO $objReciboDocAnexPetDTO
         * @since  20/12/2016
         * @author Jaqueline Mendes jaqueline.mendes@castgroup.com.br
         */
        public static function retornaObjReciboDocPreenchido($params){
            $idDocumento = $params[0];
            $formato = $params[1];
            $idAnexo = MdPetIntercorrenteINT::retornaIdAnexo($idDocumento);
            $objReciboDocAnexPetDTO = new ReciboDocumentoAnexoPeticionamentoDTO();
            $objReciboDocAnexPetDTO->setNumIdAnexo($idAnexo);
            $objReciboDocAnexPetDTO->setNumIdDocumento($idDocumento);
            $objReciboDocAnexPetDTO->setStrClassificacaoDocumento(null);
            $objReciboDocAnexPetDTO->setStrFormatoDocumento($formato);

            return $objReciboDocAnexPetDTO;
    }


        /**
         * Fun��o que verifica o tipo de formato de acorodo com o Documento
         * @param SaidaIncluirDocumentoAPI $objRetornoDoc
         * @return array $arrRetorno
         * @since  21/12/2016
         * @author Jaqueline Mendes jaqueline.mendes@castgroup.com.br
         */
        public static function retornaTipoFormatoDocumento($objDoc){
            $ids = null;
            $retorno = null;
            if(!is_null($objDoc))
            {

                if(is_array($objDoc)) {
                    $ids = array();
                    foreach ($objDoc as $doc) {
                        $idDocumento = $doc->getIdDocumento();
                        array_push($ids, $idDocumento);
                    }
                }else{
                    $ids = $objDoc->getIdDocumento();
                }

                $objDocumentoRN = new DocumentoRN();
                $objDocumentoDTO = new DocumentoDTO();
                is_array($ids) ? $objDocumentoDTO->setDblIdDocumento($ids, InfraDTO::$OPER_IN) : $objDocumentoDTO->setDblIdDocumento($ids);
                $objDocumentoDTO->retDblIdDocumento();
                $objDocumentoDTO->retNumIdTipoConferencia();
                $objDocs =  is_array($ids) ? $objDocumentoRN->listarRN0008($objDocumentoDTO) : $objDocumentoRN->consultarRN0005($objDocumentoDTO);


                if(is_array($objDocs)){
                    $retorno = array();
                foreach($objDocs as $objDoc){
                    $retorno[$objDoc->getDblIdDocumento()] = is_null($objDoc->getNumIdTipoConferencia()) ? MdPetIntercorrenteProcessoRN::$FORMATO_NATO_DIGITAL : MdPetIntercorrenteProcessoRN::$FORMATO_DIGITALIZADO;
                }
                }else{
                    $retorno = is_null($objDocs->getNumIdTipoConferencia()) ? MdPetIntercorrenteProcessoRN::$FORMATO_NATO_DIGITAL : MdPetIntercorrenteProcessoRN::$FORMATO_DIGITALIZADO;
                }
               
            }

            return $retorno;
        }

    }