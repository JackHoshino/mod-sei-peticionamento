<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 29/03/2017 - criado por marcelo.cast
 *
 * Vers�o do Gerador de C�digo: 1.40.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdPetAgendamentoAutomaticoRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    /* M�todo que realiza cumprimento autom�tico de intima��es por decurso de prazo */
    protected function CumprirPorDecursoPrazoTacitoControlado( ){

        try{
            
            ini_set('max_execution_time','0');
            ini_set('memory_limit','1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();


            $objUsuarioPetRN  = new MdPetIntUsuarioRN();
            $idUsuarioPet = $objUsuarioPetRN->getObjUsuarioPeticionamento(true);
            SessaoSEI::getInstance(false)->simularLogin(null, SessaoSEI::$UNIDADE_TESTE, $idUsuarioPet , null);

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('CUMPRINDO INTIMA�OES POR DECURSO DE PRAZO');

            $objMdPetIntAceiteRN = new MdPetIntAceiteRN();
            $intimacoesPendentes = $objMdPetIntAceiteRN->verificarIntimacoesPrazoExpirado();

            InfraDebug::getInstance()->gravar('Qtd. Intima��es Pendentes: ' . count($intimacoesPendentes));

            if(count($intimacoesPendentes) > 0){
                $arrIntimacoes = $objMdPetIntAceiteRN->realizarEtapasAceiteAgendado($intimacoesPendentes);
            }

            InfraDebug::getInstance()->gravar('Qtd. Intima��es Cumpridas: ' . $arrIntimacoes['cumpridas']);
            InfraDebug::getInstance()->gravar('Qtd. Intima��es N�o Cumpridas: ' . $arrIntimacoes['naoCumpridas']);

            foreach ($arrIntimacoes['procedimentos'] as $procedimentos) {
                InfraDebug::getInstance()->gravar('Processo n� ' . $procedimentos[0] . ' - Motivo: ' . $procedimentos[1]);
            }

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');
            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
        }catch(Exception $e){
            SessaoSEI::getInstance()->setBolHabilitada(true);
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro cumprindo intima��o por decurso de prazo.',$e);
        }

    }

    /* M�todo que reintera via email acerca de Intima��o Eletr�nica:
     *    - Com Tipo de Resposta que Exige Resposta pelo Usu�rio Externo 
     *    e Pendente de Resposta ap�s a Intima��o ter sido Cumprida.
     */
    protected function ReiterarIntimacaoExigeRespostaControlado( ){

        try{

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('REITERANDO INTIMA��ES PENDENTES EXIGE RESPOSTA');


            $objMdPetIntimacaoRN = new MdPetIntimacaoRN();

            $intimacoesExigeRespostaDTO = $objMdPetIntimacaoRN->getIntimacoesPossuemData( array(true,true) );

            //Juridico DTO

            $intimacoesExigeRespostaJuridicoDTO = $objMdPetIntimacaoRN->getIntimacoesPossuemDataJuridico( array(true,true) );


            InfraDebug::getInstance()->gravar('Qtd. Intima��es Exige Resposta: ' . count($intimacoesExigeRespostaDTO));

            if(count($intimacoesExigeRespostaDTO) > 0){
                $objMdPetIntEmailNotificacaoRN = new MdPetIntEmailNotificacaoRN();

                InfraDebug::getInstance()->setBolLigado(true);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->setBolEcho(false);

                InfraDebug::getInstance()->gravar('REITERANDO INTIMA��ES PENDENTES EXIGE RESPOSTA');
                InfraDebug::getInstance()->gravar('Qtd. Intima��es Exige Resposta: ' . count($intimacoesExigeRespostaDTO));
                
                $qtdEnviadas = $objMdPetIntEmailNotificacaoRN->enviarEmailReiteracaoIntimacao(array($intimacoesExigeRespostaDTO,$pessoa = "F"));
                if (is_numeric($qtdEnviadas)){
                    InfraDebug::getInstance()->gravar('Qtd. Intima��es Reiteradas: ' . $qtdEnviadas);
                }
            }


            //Juridico
            InfraDebug::getInstance()->gravar('Qtd. Intima��es Exige Resposta Juridico: ' . count($intimacoesExigeRespostaJuridicoDTO));


            if(count($intimacoesExigeRespostaJuridicoDTO) > 0){
                $objMdPetIntEmailNotificacaoRN = new MdPetIntEmailNotificacaoRN();

                InfraDebug::getInstance()->setBolLigado(true);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->setBolEcho(false);

                InfraDebug::getInstance()->gravar('REITERANDO INTIMA��ES PENDENTES EXIGE RESPOSTA - JURIDICO');
                InfraDebug::getInstance()->gravar('Qtd. Intima��es Exige Resposta Juridico: ' . count($intimacoesExigeRespostaJuridicoDTO));
                
                $qtdEnviadasJuridico = $objMdPetIntEmailNotificacaoRN->enviarEmailReiteracaoIntimacaoJuridico(array($intimacoesExigeRespostaJuridicoDTO,$pessoa = "J"));
                if (is_numeric($qtdEnviadas)){
                    InfraDebug::getInstance()->gravar('Qtd. Intima��es Reiteradas Juridico: ' . $qtdEnviadasJuridico);
                }
            }



            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

        }catch(Exception $e){
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro reiterando intima��es pendentes exige resposta.',$e);
        }

    }


    /* M�todo que reintera via email acerca de Intima��o Eletr�nica:
   *    - Com Tipo de Resposta que Exige Resposta pelo Usu�rio Externo
   *    e Pendente de Resposta ap�s a Intima��o ter sido Cumprida.
   */
    protected function atualizarEstadoIntimacoesPrazoExternoVencidoControlado(){

        try{

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO O ESTADO DE INTIMA��ES VENCIDAS E SEM RESPOSTA');

            $objMdPetIntRelDestRN = new MdPetIntRelDestinatarioRN();

            $intimacoesVencidas = $objMdPetIntRelDestRN->retornaAtualizaIntimacoesSemRespostaVencidas();

            InfraDebug::getInstance()->gravar('Qtd. Intima��es Vencidas e Sem Resposta: ' . $intimacoesVencidas);

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

        }catch(Exception $e){
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando o estado das intima��es vencidas e sem resposta.',$e);
        }

    }

    protected function atualizarEstadoTodasIntimacoesControlado()
    {
        try {

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO O ESTADO DE TODAS AS INTIMA��ES J� REALIZADAS NO SEI');

            $objMdPetRegrasGeraisRN = new MdPetRegrasGeraisRN();
            $objMdPetIntRelDestRN = new MdPetIntRelDestinatarioRN();

            $objMdPetIntRelDestDTO = new MdPetIntRelDestinatarioDTO();
            $objMdPetIntRelDestDTO->retNumIdMdPetIntRelDestinatario();

            $count = $objMdPetIntRelDestRN->contar($objMdPetIntRelDestDTO);

            if ($count > 0) {
                $arrRetornoDTO = $objMdPetIntRelDestRN->listar($objMdPetIntRelDestDTO);
                $idsRelDest = InfraArray::converterArrInfraDTO($arrRetornoDTO, 'IdMdPetIntRelDestinatario');
                $arrSituacoesAtuais = $objMdPetRegrasGeraisRN->retornaSituacoesIntimacoes(array($idsRelDest, true));
                $arrSituacaoSeparado = $objMdPetRegrasGeraisRN->formatarArrSituacoes($arrSituacoesAtuais);

                $objMdPetIntRelDestRN->atualizarCadaEstadoIntimacao($arrSituacaoSeparado, MdPetIntimacaoRN::$INTIMACAO_PENDENTE);
                $objMdPetIntRelDestRN->atualizarCadaEstadoIntimacao($arrSituacaoSeparado, MdPetIntimacaoRN::$INTIMACAO_CUMPRIDA_PRAZO);
                $objMdPetIntRelDestRN->atualizarCadaEstadoIntimacao($arrSituacaoSeparado, MdPetIntimacaoRN::$INTIMACAO_CUMPRIDA_POR_ACESSO);
                $objMdPetIntRelDestRN->atualizarCadaEstadoIntimacao($arrSituacaoSeparado, MdPetIntimacaoRN::$INTIMACAO_RESPONDIDA);
                $objMdPetIntRelDestRN->atualizarCadaEstadoIntimacao($arrSituacaoSeparado, MdPetIntimacaoRN::$INTIMACAO_PRAZO_VENCIDO);
            }

            InfraDebug::getInstance()->gravar('Qtd. Intima��es Atualizadas: ' . $count);

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando o estado das intima��es.', $e);
        }


    }
}
?>