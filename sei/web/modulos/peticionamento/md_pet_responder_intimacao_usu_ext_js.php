<script type="text/javascript">
    "use strict";

    var RESTRITO = '<?= ProtocoloRN::$NA_RESTRITO?>';
    var TAMANHO_MAXIMO = '<?=$tamanhoMaximo?>';
    var EXIBIR_HIPOTESE_LEGAL = '<?=$exibirHipoteseLegal?>';
    var arrExtensoesPermitidas = [<?=$extensoesPermitidas?>];
    var objAjaxSelectTipoDocumento = null;
    var objAjaxSelectHipoteseLegal = null;
    var objUploadArquivo = null;
    var objTabelaDinamicaDocumento = null;

    function inicializar() {
        infraEfeitoTabelas();
        if (EXIBIR_HIPOTESE_LEGAL) {
            verificarHipoteseLegal();
        }
        iniciarObjUploadArquivo();
        iniciarTabelaDinamicaDocumento();
    }

    function fechar() {
        document.location = '<?= $strUrlFechar ?>';
    }

    function iniciarObjAjaxSelectHipoteseLegal() {
        objAjaxSelectHipoteseLegal = new infraAjaxMontarSelect('selHipoteseLegal', '<?= $strUrlAjaxMontarHipoteseLegal?>');
        objAjaxSelectHipoteseLegal.processarResultado = function () {
            return 'nivelAcesso=' + RESTRITO;
        }
    }

    function iniciarTabelaDinamicaDocumento() {
        objTabelaDinamicaDocumento = new infraTabelaDinamica('tbDocumento', 'hdnTbDocumento', false, true);
        objTabelaDinamicaDocumento.gerarEfeitoTabela = true;
        objTabelaDinamicaDocumento.remover = function () {
            verificarTabelaVazia(2);
            return true;
        };
    }

    function exibirTipoConferencia() {
        var formatoDigitalizado = document.getElementById('rdoDigitalizado');
        var divTipoConferencia = document.getElementById('divTipoConferencia');
        var selTipoConferencia = document.getElementById('selTipoConferencia');
        selTipoConferencia.value = 'null';

        if (formatoDigitalizado.checked) {
            divTipoConferencia.style.display = 'inline-block';
        } else {
            divTipoConferencia.style.display = 'none';
        }
    }

    function exibirHipoteseLegal() {
        var selNivelAcesso = document.getElementById('selNivelAcesso');
        var divBlcHipoteseLegal = document.getElementById('divBlcHipoteseLegal');
        var selHipoteseLegal = document.getElementById('selHipoteseLegal');
        var hdnNivelAcesso = document.getElementById('hdnNivelAcesso');
        hdnNivelAcesso.value = selNivelAcesso.value;

        if (selNivelAcesso.value == RESTRITO && EXIBIR_HIPOTESE_LEGAL) {
            divBlcHipoteseLegal.style.display = '';
            selHipoteseLegal.value = '';
        } else {
            divBlcHipoteseLegal.style.display = 'none';
        }
    }

    function adicionarDocumento() {
        if (validarDocumento()) {
            objUploadArquivo.executar();
            document.getElementById('tbDocumento').style.display = '';
        }
    }

    function responderIntimacao() {

        var frm = document.getElementById('frmResponderIntimacao');

        if (validarResposta()) {
            //@todo trocar url para a correta do intercorrente ou nova do resposta  
            infraAbrirJanela('<?=PaginaSEIExterna::getInstance()->formatarXHTML(SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=md_pet_responder_intimacao_usu_ext_assinar&tipo_selecao=2'))?>',
               'concluirPeticionamentoRespostaIntimacao',
               770,
               480,
               '', //options
               false); //modal 
        }

    }

    function iniciarObjUploadArquivo() {
        var tbDocumento = document.getElementById('tbDocumento');
        objUploadArquivo = new infraUpload('frmResponderIntimacao', '<?=$strUrlUploadArquivo?>');
        objUploadArquivo.finalizou = function (arr) {

            //Tamanho do Arquivo
            var fileArquivo = document.getElementById('fileArquivo');
            var tamanhoArquivo = (arr['tamanho'] / 1024 / 1024).toFixed(2);
            if (tamanhoArquivo > parseInt(TAMANHO_MAXIMO)) {
                alert('Tamanho m�ximo para o arquivo � de ' + TAMANHO_MAXIMO + 'Mb');
                fileArquivo.value = '';
                fileArquivo.focus();
                verificarTabelaVazia(1);
                return false;
            }


            //Arquivo com o mesmo nome j� adicionado
            for (var i = 0; i < tbDocumento.rows.length; i++) {

                var tr = tbDocumento.getElementsByTagName('tr')[i];
                
                if (arr['nome'].toLowerCase().trim() == tr.cells[9].innerText.toLowerCase().trim()) {
                    alert('N�o � permitido adicionar documento com o mesmo nome de arquivo.');
                    fileArquivo.value = '';
                    fileArquivo.focus();
                    verificarTabelaVazia(1);
                    return false;
                }
                
            }

            criarRegistroTabelaDocumento(arr);
            corrigirPosicaoAcaoExcluir();
            limparCampoDocumento();
        };

        objUploadArquivo.validar = function () {
            var fileArquivo = document.getElementById('fileArquivo');
            var ext = fileArquivo.value.split('.').pop().toLowerCase();
            var extensaoConfigurada = arrExtensoesPermitidas.length > 0;

            var tamanhoConfigurado = parseInt(TAMANHO_MAXIMO) > 0;
            if (!tamanhoConfigurado) {
                alert('Limite n�o configurado na Administra��o do Sistema.');
                fileArquivo.value = '';
                fileArquivo.focus();
                return false;
            }

            if (!extensaoConfigurada) {
                alert('Extens�o de Arquivos Permitidos n�o foi configurado na Administra��o do Sistema.');
                fileArquivo.value = '';
                fileArquivo.focus();
                return false;
            }

            var arquivoPermitido = arrExtensoesPermitidas.indexOf(ext) != -1;
            if (!arquivoPermitido) {
                alert("O arquivo selecionado n�o � permitido.\n" +
                    "Somente s�o permitidos arquivos com as extens�es:\n" +
                    arrExtensoesPermitidas.join().replace(/,/g, ' '));
                fileArquivo.value = '';
                fileArquivo.focus();
                return false;
            }
            return true;
        };
    }


    function verificarTabelaVazia(qtdLinha) {
        var tbDocumento = document.getElementById('tbDocumento');
        var ultimoRegistro = tbDocumento.rows.length == qtdLinha;
        if (ultimoRegistro) {
            tbDocumento.style.display = 'none';
        }
    }

    function limparCampoDocumento() {
        document.getElementById('fileArquivo').value = '';
        document.getElementById('selTipoDocumento').value = 'null';
        document.getElementById('txtComplementoTipoDocumento').value = '';

        document.getElementById('selNivelAcesso').value = '';
        document.getElementById('hdnNivelAcesso').value = '';
        if (EXIBIR_HIPOTESE_LEGAL){
            document.getElementById('selHipoteseLegal').value = '';
            document.getElementById('hdnHipoteseLegal').value = '';
        }
        document.getElementById('divBlcHipoteseLegal').style.display = 'none';

        document.getElementById('rdoNatoDigital').checked = false;
        document.getElementById('rdoDigitalizado').checked = false;
        document.getElementById('selTipoConferencia').value = 'null';
        document.getElementById('divTipoConferencia').style.display = 'none';
    }

    function limparTabelaDocumento() {
        objTabelaDinamicaDocumento.limpar();
        verificarTabelaVazia(1);
    }


    function gerarIdDocumento() {
        var hdnIdDocumento = document.getElementById('hdnIdDocumento');
        hdnIdDocumento.value = parseInt(hdnIdDocumento.value) + 1;
        return hdnIdDocumento.value;
    }

    function validarDocumento() {
        var tipoDocumento = document.getElementById('selTipoDocumento');
        tipoDocumento = tipoDocumento.options[tipoDocumento.selectedIndex];

        var complementoTipoDocumento = document.getElementById('txtComplementoTipoDocumento').value.trim();

        var formato = document.getElementsByName('rdoFormato');
        var formatoInformado = false;
        for (var i = 0; i < formato.length; i++) {
            if (formato[i].checked) {
                formatoInformado = true;
                break;
            }
        }
        var selTipoConferencia = document.getElementById('selTipoConferencia');
        var tipoConferencia = document.getElementById('selTipoConferencia');
        tipoConferencia = tipoConferencia.options[tipoConferencia.selectedIndex];

        var fileArquivo = document.getElementById('fileArquivo');

        if (fileArquivo.value.trim() == '') {
            alert('Informe o arquivo para upload.');
            fileArquivo.focus();
            return false;
        }

        if (tipoDocumento == null || tipoDocumento.value == 'null') {
            alert('Informe o Tipo de Documento.');
            document.getElementById('selTipoDocumento').focus();
            return false;
        }

        if (complementoTipoDocumento == '') {
            alert('Informe o Complemento do Tipo de Documento. \nPara mais informa��es, clique no �cone de Ajuda ao lado do nome do campo.');
            document.getElementById('txtComplementoTipoDocumento').focus();
            return false;
        }


        var selNivelAcesso = document.getElementById('selNivelAcesso');
        if (selNivelAcesso.nodeName == 'SELECT') {
            var nivelAcesso = selNivelAcesso.options[selNivelAcesso.selectedIndex];
            if (nivelAcesso == null || nivelAcesso.value == '') {
                alert('Informe o N�vel de Acesso.');
                document.getElementById('selNivelAcesso').focus();
                return false;
            }
        }

        if (EXIBIR_HIPOTESE_LEGAL) {
            var selHipoteseLegal = document.getElementById('selHipoteseLegal');
            if (selHipoteseLegal.nodeName == 'SELECT' && selHipoteseLegal.offsetHeight > 0) {
                var hipoteseLegal = selHipoteseLegal.options[selHipoteseLegal.selectedIndex];
                if (hipoteseLegal == null || hipoteseLegal.value == '') {
                    alert('Informe a Hip�tese Legal.');
                    selHipoteseLegal.focus();
                    return false;
                }
            }
        }

        if (!formatoInformado) {
            alert('Informe o Formato do Documento.');
            document.getElementById('rdoNatoDigital').focus();
            return false;
        }

        if (selTipoConferencia.offsetHeight > 0) {
            if (tipoConferencia == null || tipoConferencia.value == 'null') {
                alert('Informe a Confer�ncia com o documento digitalizado.');
                selTipoConferencia.focus();
                return false;
            }
        }

        return true;

    }


    function criarRegistroTabelaDocumento(arr) {
        var nomeArquivo = arr['nome'];
        var nomeArquivoHash = arr['nome_upload'];
        var tamanhoArquivo = arr['tamanho'];
        var tamanhoArquivoFormatado = infraFormatarTamanhoBytes(tamanhoArquivo);
        var dataHora = arr['data_hora'];

        var rdoNatoDigital = document.getElementById('rdoNatoDigital');
        var rdoDigitalizado = document.getElementById('rdoDigitalizado');
        var formato = rdoNatoDigital.checked ? rdoNatoDigital.nextSibling.nextSibling.innerHTML.trim() :
            rdoDigitalizado.nextSibling.nextSibling.innerHTML.trim();

        var tipoDocumento = document.getElementById('selTipoDocumento');
        tipoDocumento = tipoDocumento.options[tipoDocumento.selectedIndex].text;

        var complementoTipoDocumento = document.getElementById('txtComplementoTipoDocumento').value.trim();
        complementoTipoDocumento = $("<pre>").text(complementoTipoDocumento).html();
        var documento = tipoDocumento + ' ' + complementoTipoDocumento;

        var nivelAcesso = document.getElementById('selNivelAcesso');
        if (nivelAcesso.nodeName == 'SELECT') {
            nivelAcesso = nivelAcesso.options[nivelAcesso.selectedIndex].text;
        } else {
            nivelAcesso = nivelAcesso.innerHTML.trim();
        }

        var idLinha = gerarIdDocumento();
        var idTipoDocumento = document.getElementById('selTipoDocumento').value;
        var complementoTipoDocumento = document.getElementById('txtComplementoTipoDocumento').value;
        var idNivelAcesso = document.getElementById('hdnNivelAcesso').value;

        var idHipoteseLegal;
        if (EXIBIR_HIPOTESE_LEGAL) {
            idHipoteseLegal = document.getElementById('hdnHipoteseLegal').value;
        }

        var idFormato = rdoNatoDigital.checked ? rdoNatoDigital.value : rdoDigitalizado.value;
        var idTipoConferencia = document.getElementById('selTipoConferencia').value;

        var dados = [
            idLinha, idTipoDocumento, complementoTipoDocumento, idNivelAcesso,
            idHipoteseLegal, idFormato, idTipoConferencia, nomeArquivoHash, tamanhoArquivo, nomeArquivo,
            dataHora, tamanhoArquivoFormatado, documento, nivelAcesso, formato
        ];

        objTabelaDinamicaDocumento.adicionar(dados);
    }

    function corrigirPosicaoAcaoExcluir() {
        var trs = document.getElementById('tbDocumento').getElementsByTagName('tr');
        for (var i = 1; i < trs.length; i++) {
            var tds = trs[i].getElementsByTagName('td');
            var td = tds[tds.length - 1];
            td.setAttribute('valign', 'center');
        }
    }

    function salvarValorHipoteseLegal(el) {
        if (EXIBIR_HIPOTESE_LEGAL){
           var hdnHipoteseLegal = document.getElementById('hdnHipoteseLegal');
           hdnHipoteseLegal.value = el.value;
        }
    }

    function verificarHipoteseLegal() {
        var selNivelAcesso = document.getElementById('selNivelAcesso');

        if (selNivelAcesso.nodeName == 'SELECT') {
            selNivelAcesso.addEventListener('change', exibirHipoteseLegal);
        }

    }

    function exibirFieldsetDocumentos(el) {
        var fieldDocumentos = document.getElementById('fieldDocumentos');
        var hdnNomeTipoResposta = document.getElementById('hdnNomeTipoResposta');
        fieldDocumentos.style.display = 'none';

        if (el.value != 'null') {
            
            fieldDocumentos.style.display = '';
            hdnNomeTipoResposta.value = el.options[el.selectedIndex].text.trim();
            objTabelaDinamicaDocumento.limpar();
            document.getElementById('tbDocumento').style.display='none';

            //fileArquivo
            document.getElementById('fileArquivo').value = '';
            
            //rdoDigitalizado
            document.getElementById('rdoDigitalizado').checked = false;
            
            //rdoNatoDigital
            document.getElementById('rdoNatoDigital').click();
            document.getElementById('rdoNatoDigital').checked = false;
            
            //selHipoteseLegal
            if (EXIBIR_HIPOTESE_LEGAL){
                document.getElementById('selHipoteseLegal').selectedIndex = 0;
            }

            //selTipoConferencia
            document.getElementById('selTipoConferencia').selectedIndex = 0;
            
            //selNivelAcesso
            document.getElementById('selNivelAcesso').selectedIndex = 0;
            
            //txtComplementoTipoDocumento
            document.getElementById('txtComplementoTipoDocumento').value = '';
            
            //selTipoDocumento
            document.getElementById('selTipoDocumento').selectedIndex = 0;
        }
        
    }

    function validarResposta() {
        var selTipoResposta = document.getElementById('selTipoResposta');
        var tbDocumento = document.getElementById('tbDocumento');

        if (selTipoResposta.value == 'null') {
            alert('Informe o Tipo de Resposta!');
            selTipoResposta.focus();
            return false;
        }

        if (tbDocumento.rows.length <= 1) {
            alert('Informe ao menos um documento!');
            return false;
        }

        return true;
    }


</script>