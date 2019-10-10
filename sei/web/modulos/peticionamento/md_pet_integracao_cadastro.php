<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 25/01/2018 - criado por Usu�rio
*
* Vers�o do Gerador de C�digo: 1.41.0
*
* Vers�o no SVN: $Id$
*/
try {
  require_once dirname(__FILE__).'/../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  ///////////////////////////////////////////////////////////////////////pree///////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('md_pet_integracao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selMdPetIntegFuncionalid'));

  $objMdPetIntegracaoDTO = new MdPetIntegracaoDTO();

   //Recuperando prazo se existir
  $mes = array();
  $objMdPetIntegParametroDTO = new MdPetIntegParametroDTO();
  $objMdPetIntegParametroDTO->retTodos();
  $objMdPetIntegParametroDTO->setNumIdMdPetIntegracao($_GET['id_md_pet_integracao']);
  $objMdPetIntegParametroRN = new MdPetIntegParametroRN();
  $arrMdPetIntegParametroRN =  $objMdPetIntegParametroRN->listar($objMdPetIntegParametroDTO);
  $mes = InfraArray::converterArrInfraDTO($arrMdPetIntegParametroRN, 'ValorPadrao');

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_pet_integracao_cadastrar':
      $strTitulo = 'Nova Integra��o';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdPetIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdPetIntegracaoDTO->setNumIdMdPetIntegracao(null);
      $numIdMdPetIntegFuncionalid = PaginaSEI::getInstance()->recuperarCampo('selMdPetIntegFuncionalid');
      if ($numIdMdPetIntegFuncionalid!==''){
        $objMdPetIntegracaoDTO->setNumIdMdPetIntegFuncionalid($numIdMdPetIntegFuncionalid);
      }else{
        $objMdPetIntegracaoDTO->setNumIdMdPetIntegFuncionalid(null);
      }

      $objMdPetIntegracaoDTO->setStrNome($_POST['txtNome']);
      $objMdPetIntegracaoDTO->setStrStaUtilizarWs($_POST['rdStaUtilizarWs']);
      if($_POST['rdStaUtilizaWs'] == 'N'){
          $objMdPetIntegracaoDTO->setStrEnderecoWsdl('');
          $objMdPetIntegracaoDTO->setStrOperacaoWsdl('');
          $objMdPetIntegracaoDTO->setStrSinCache('');
          $objMdPetIntegracaoDTO->setStrSinAtivo('S');
      } else {
          $objMdPetIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
          $objMdPetIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacaoWsdl']);
          $objMdPetIntegracaoDTO->setStrSinCache('');
          $objMdPetIntegracaoDTO->setStrSinAtivo('S');
      }


      if (isset($_POST['sbmCadastrarMdPetIntegracao'])) {
        try{
            
          $objMdPetIntegracaoRN = new MdPetIntegracaoRN();
          $objMdPetIntegracaoDTO->setStrSinCache(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCache']));
          $objMdPetIntegracaoDTO = $objMdPetIntegracaoRN->cadastrarCompleto($objMdPetIntegracaoDTO);
          
          PaginaSEI::getInstance()->adicionarMensagem('Integra��o "'.$objMdPetIntegracaoDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_pet_integracao='.$objMdPetIntegracaoDTO->getNumIdMdPetIntegracao().PaginaSEI::getInstance()->montarAncora($objMdPetIntegracaoDTO->getNumIdMdPetIntegracao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_pet_integracao_alterar':
    
      $strTitulo = 'Alterar Integra��o';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdPetIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_pet_integracao'])){
        $objMdPetIntegracaoDTO->setNumIdMdPetIntegracao($_GET['id_md_pet_integracao']);
        $objMdPetIntegracaoDTO->setBolExclusaoLogica(false);
        $objMdPetIntegracaoDTO->retTodos();
        $objMdPetIntegracaoRN = new MdPetIntegracaoRN();
        $objMdPetIntegracaoDTO = $objMdPetIntegracaoRN->consultar($objMdPetIntegracaoDTO);
        if ($objMdPetIntegracaoDTO==null){
          throw new InfraException("Registro n�o encontrado.");
        }
      } else {
      	$objMdPetIntegracaoDTO->setNumIdMdPetIntegracao($_POST['hdnIdMdPetIntegracao']);
        $objMdPetIntegracaoDTO->setNumIdMdPetIntegFuncionalid($_POST['selMdPetIntegFuncionalid']);
        $objMdPetIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdPetIntegracaoDTO->setStrStaUtilizarWs($_POST['rdStaUtilizarWs']);
          if($_POST['rdStaUtilizarWs'] == 'N'){
              $objMdPetIntegracaoDTO->setStrEnderecoWsdl('');
              $objMdPetIntegracaoDTO->setStrOperacaoWsdl('');
              $objMdPetIntegracaoDTO->setStrSinCache('');
              $objMdPetIntegracaoDTO->setStrSinAtivo('S');
          } else {
              $objMdPetIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
              $objMdPetIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacaoWsdl']);
              $objMdPetIntegracaoDTO->setStrSinCache(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCache']));
              $objMdPetIntegracaoDTO->setStrSinAtivo('S');
          }
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdPetIntegracaoDTO->getNumIdMdPetIntegracao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdPetIntegracao'])) {
        
        try{
          $objMdPetIntegracaoRN = new MdPetIntegracaoRN();
          $objMdPetIntegracaoRN->alterarCompleto($objMdPetIntegracaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Integra��o "'.$objMdPetIntegracaoDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdPetIntegracaoDTO->getNumIdMdPetIntegracao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_pet_integracao_consultar':
      $strTitulo = 'Consultar Integra��o';
      $arrComandos[] = '<button type="button" accesskey="c" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_pet_integracao'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdPetIntegracaoDTO->setNumIdMdPetIntegracao($_GET['id_md_pet_integracao']);
      $objMdPetIntegracaoDTO->setBolExclusaoLogica(false);
      $objMdPetIntegracaoDTO->retTodos();
      $objMdPetIntegracaoRN = new MdPetIntegracaoRN();
      $objMdPetIntegracaoDTO = $objMdPetIntegracaoRN->consultar($objMdPetIntegracaoDTO);
      if ($objMdPetIntegracaoDTO===null){
        throw new InfraException("Registro n�o encontrado.");
      }
      break;

    default:
      throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
  }

  if (count($objMdPetIntegracaoDTO)>0){
      if($objMdPetIntegracaoDTO->getStrStaUtilizarWs() == 'N') {
          $staUtilizarWsNao = "checked='checked'";
          $staUtilizarWsSim = "";
      } elseif($objMdPetIntegracaoDTO->getStrStaUtilizarWs() == 'S') {
          $staUtilizarWsNao = "";
          $staUtilizarWsSim = "checked='checked'";
      }

  	$objMdPetIntegParametroDTO = new MdPetIntegParametroDTO;
    $objMdPetIntegParametroDTO->setNumIdMdPetIntegracao($objMdPetIntegracaoDTO->getNumIdMdPetIntegracao());
    $objMdPetIntegParametroDTO->setStrTpParametro('D');
    $objMdPetIntegParametroDTO->setBolExclusaoLogica(false);
    $objMdPetIntegParametroDTO->retTodos();
    $objMdPetIntegParametroRN = new MdPetIntegParametroRN();
    $arrObjMdPetIntegParametroDTO = $objMdPetIntegParametroRN->listar($objMdPetIntegParametroDTO);
    if (count($arrObjMdPetIntegParametroDTO)>0){
  		$strItensSelCacheDataArmazenamento = $arrObjMdPetIntegParametroDTO[0]->getStrNome(); 
    }
  	$objMdPetIntegParametroDTO = new MdPetIntegParametroDTO;
    $objMdPetIntegParametroDTO->setNumIdMdPetIntegracao($objMdPetIntegracaoDTO->getNumIdMdPetIntegracao());
    $objMdPetIntegParametroDTO->setStrTpParametro('P');
    $objMdPetIntegParametroDTO->setBolExclusaoLogica(false);
    $objMdPetIntegParametroDTO->retTodos();
    $objMdPetIntegParametroRN = new MdPetIntegParametroRN();
    $arrObjMdPetIntegParametroDTO = $objMdPetIntegParametroRN->listar($objMdPetIntegParametroDTO);
    if (count($arrObjMdPetIntegParametroDTO)>0){
  		$strItensSelCachePrazoExpiracao = $arrObjMdPetIntegParametroDTO[0]->getStrNome();		
    }

    $strItensSelMdPetIntegFuncionalid = MdPetIntegFuncionalidINT::montarSelectNomeNaoUtilizado('null','&nbsp;',$objMdPetIntegracaoDTO->getNumIdMdPetIntegFuncionalid(),$objMdPetIntegracaoDTO->getNumIdMdPetIntegracao());

  }else{
    $strItensSelMdPetIntegFuncionalid = MdPetIntegFuncionalidINT::montarSelectNomeNaoUtilizado('null','&nbsp;',$objMdPetIntegracaoDTO->getNumIdMdPetIntegFuncionalid());
      $staUtilizarWsNao = "";
      $staUtilizarWsSim = "";
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?php if($_GET['acao'] == 'md_pet_integracao_consultar'){  ?>
    #btnValidar {display: none}
<?php } ?>
#container{
  width: 100%;
}

.clear {
  clear: both;
}

.bloco {
  float: left;
  margin-top: 0%;
  margin-right: 1%;
}

label[for^=txt] {
  display: block;
  white-space: nowrap;
}
label[for^=s] {
  display: block;
  white-space: nowrap;
}
label[for^=file] {
  display: block;
  white-space: nowrap;
}

img[name=ajuda] {
  margin-bottom: -4px;
  width: 16px !important;
  height: 16px !important;
}

#txtNome {
  width:610px;
}
#selMdPetIntegFuncionalid {
  width:615px;
}
#txtEnderecoWsdl {
  width:610px;
}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();

$strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_pet_integracao_busca_operacao_wsdl');
$strLinkAjaxBuscarParametroWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_pet_integracao_busca_parametro_wsdl');
?>
<script type="text/javascript" charset="iso-8859-1">
var preencheCache=false;
var staUtilizaWs=false;
var staUtilizaWsCheck=null;
function inicializar(){
  habilitaWs();
  if ('<?=$_GET['acao']?>'=='md_pet_integracao_cadastrar'){
    document.getElementById('selMdPetIntegFuncionalid').focus();
      $('#blcEnderecoWs').css('display', 'none');
      $('#blcOperacaoWs').css('display', 'none');
      $('#blcCacheWs').css('display', 'none');
      $('#fldParametrosCache').css('display', 'none');
      $('#blcTextoSemWs').css('display', 'none');
  } else if ('<?=$_GET['acao']?>'=='md_pet_integracao_consultar'){
    infraDesabilitarCamposAreaDados();
    if(staUtilizaWs) {
        validarWsdl();
    }
  }else{
    document.getElementById('btnCancelar').focus();
    if(staUtilizaWs) {
        validarWsdl();
    }
  }
  infraEfeitoTabelas();

}

function habilitaWs(){
    var itensIntegracao = document.getElementsByName('rdStaUtilizarWs');

    $.each(itensIntegracao, function(i, item){
        if(item.checked == true){
            staUtilizaWsCheck = true;
            if(item.value == 'N'){
                staUtilizaWs = false;
                $('#blcEnderecoWs').css('display', 'none');
                $('#blcOperacaoWs').css('display', 'none');
                $('#blcCacheWs').css('display', 'none');
                $('#fldParametrosCache').css('display', 'none');
                $('#blcTextoSemWs').css('display', 'block');
            } else {
                staUtilizaWs = true;
                $('#blcEnderecoWs').css('display', 'block');
                $('#blcOperacaoWs').css('display', 'block');
                $('#blcCacheWs').css('display', 'block');
                cacheMarcaDesmarca($('#fldParametrosCache'));
                $('#blcTextoSemWs').css('display', 'none');
            }
        }
    });

    console.log(staUtilizaWsCheck);
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (!infraSelectSelecionado('selMdPetIntegFuncionalid')) {
    alert('Selecione uma Funcionalidade.');
    document.getElementById('selMdPetIntegFuncionalid').focus();
    return false;
  }
    if (staUtilizaWsCheck==null) {
        alert('Informe Indica��o de Integra��o com a Receita Federal.');
        document.getElementById('txtNome').focus();
        return false;
    }
  if(staUtilizaWs == true) {

      if (infraTrim(document.getElementById('txtEnderecoWsdl').value)=='') {
        alert('Informe Endere�o do Webservice.');
        document.getElementById('txtEnderecoWsdl').focus();
        return false;
      }

      if (infraTrim(document.getElementById('txtEnderecoWsdl').value)=='') {
          alert('Informe Endere�o do Webservice.');
          document.getElementById('txtEnderecoWsdl').focus();
          return false;
      }

      if (infraTrim(document.getElementById('selOperacaoWsdl').value)=='') {
        alert('Informe Opera��o.');
        document.getElementById('selOperacaoWsdl').focus();
        return false;
      }

      if (document.getElementById('chkSinCache').checked) {
          if (infraTrim(document.getElementById('selCacheDataArmazenamento').value) == '') {
              alert('Informe Data de Armazenamento do Registro.');
              document.getElementById('selCacheDataArmazenamento').focus();
              return false;
          }
          if (infraTrim(document.getElementById('selCachePrazoExpiracao').value) == '') {
              alert('Informe Prazo de Expira��o do Cache.');
              document.getElementById('selCachePrazoExpiracao').focus();
              return false;
          }
          if (infraTrim(document.getElementById('selCachePrazoExpiracao').value) == '') {
              alert('Informe Prazo de Expira��o do Cache.');
              document.getElementById('selCachePrazoExpiracao').focus();
              return false;
          }
          if (infraTrim(document.getElementById('txtPrazo').value) == '') {
              alert('Informe o Prazo.');
              return false;
          }
      }
  }

  return true;
}

function OnSubmitForm() {
  if (!validarCadastro()){
    return false;
  }

  var select = document.getElementById('selParametrosE');
  for (i=0;i<select.length;i++){
    select.options[i].selected=true;
  }	  

  var select = document.getElementById('selParametrosS');
  for (i=0;i<select.length;i++){
    select.options[i].selected=true;
  }	  

  return true;
	
  //return validarCadastro();
}

function validarWsdl(){
	
    var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
    if(enderecoWsdl == ''){
        alert('Preenche o campo Endere�o WSDL.');
        return false;
    }

    $.ajax({
        type: "POST",
        url: "<?= $strLinkAjaxValidarWsdl ?>",
        dataType: "xml",
        data: {
        endereco_wsdl: enderecoWsdl
        },
        beforeSend: function(){
            infraExibirAviso(false);
        },
        success: function (result) {
            var select = document.getElementById('selOperacaoWsdl');
            //limpar todos os options
            select.options.length = 0;

            if($(result).find('success').text() == 'true'){
                var opt = document.createElement('option');
                opt.value = '';
                opt.innerHTML = '';
                select.appendChild(opt);
                var selectedValor = '<?= PaginaSEI::tratarHTML( $objMdPetIntegracaoDTO->getStrOperacaoWsdl() );?>';
                $.each($(result).find('operacao'), function(key, value){
                    var opt = document.createElement('option');
                    opt.value = $(value).text();
                    opt.innerHTML = $(value).text();
                    if($(value).text() == selectedValor){
                        opt.selected = true;
                        preencheCache = true;
                    }                    
                    select.appendChild(opt);
                });
                if (preencheCache){
                    select.onchange();
                    chkSinCache.onchange();
                }
                preencheCache=false;
                //document.getElementById('gridOperacao').style.display = "block";
            }else{
                alert($(result).find('msg').text());
                //document.getElementById('gridOperacao').style.display = "none";
            }
        },
        error: function (msgError) {
        msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
        console.log(msgCommit);
        },
        complete: function (result) {
            infraAvisoCancelar();
        }
    });

}


function buscarParametroWsdl(tipo_parametro){

    var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
    var operacaoWsdl = document.getElementById('selOperacaoWsdl').value;

    /*
    if(enderecoWsdl == ''){
        alert('Preenche o campo Endere�o WSDL.');
        return false;
    }
    */
    $.ajax({
    	async: false,
        type: "POST",
        url: "<?= $strLinkAjaxBuscarParametroWsdl ?>",
        dataType: "xml",
        data: {
            endereco_wsdl: enderecoWsdl	,
            operacao_wsdl: operacaoWsdl ,
            tipo_parametro: tipo_parametro
        },
        beforeSend: function(){
            infraExibirAviso(false);
        },
        success: function (result) {

            if (tipo_parametro=='e'){
                var select = document.getElementById('selParametrosE');
            }else{
                var select = document.getElementById('selParametrosS');
            }

            //limpar todos os options
            select.options.length = 0;

            if($(result).find('success').text() == 'true'){

                $.each($(result).find('parametro'), function(key, value){
                    var opt = document.createElement('option');
                    opt.value = $(value).text();
                    opt.innerHTML = $(value).text();
                    select.appendChild(opt);
                });

                //document.getElementById('gridOperacao').style.display = "block";
            }else{
                alert($(result).find('msg').text());
                //document.getElementById('gridOperacao').style.display = "none";
            }

        },
        error: function (msgError) {
        msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
        console.log(msgCommit);
        },
        complete: function (result) {
            infraAvisoCancelar();
        }
    });

}

function buscarParametroWsdlCache(tipo_parametro){

    var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
    var operacaoWsdl = document.getElementById('selOperacaoWsdl').value;

    /*
    if(enderecoWsdl == ''){
        alert('Preenche o campo Endere�o WSDL.');
        return false;
    }
    */
    $.ajax({
        async: false,
        type: "POST",
        url: "<?= $strLinkAjaxBuscarParametroWsdl ?>",
        dataType: "xml",
        data: {
            endereco_wsdl: enderecoWsdl	,
            operacao_wsdl: operacaoWsdl ,
            tipo_parametro: tipo_parametro
        },
        beforeSend: function(){
            infraExibirAviso(false);
        },
        success: function (result) {

            if (tipo_parametro=='e'){
                var select = document.getElementById('selCachePrazoExpiracao');
                var selectedValor = '<?= $strItensSelCachePrazoExpiracao;?>';
            }else{
                var select = document.getElementById('selCacheDataArmazenamento');
                var selectedValor = '<?= $strItensSelCacheDataArmazenamento;?>';
            }

            //limpar todos os options
            select.options.length = 0;

            if($(result).find('success').text() == 'true'){
                $.each($(result).find('parametro'), function(key, value){
                    var opt = document.createElement('option');
                    opt.value = $(value).text();
                    opt.innerHTML = $(value).text();
                    if($(value).text() == selectedValor ){
                        opt.selected = true;
                    }else{
                        opt.selected = false;
                    }
                    select.appendChild(opt);
                });

                //document.getElementById('gridOperacao').style.display = "block";
            }else{
                alert($(result).find('msg').text());
                //document.getElementById('gridOperacao').style.display = "none";
            }

        },
        error: function (msgError) {
        msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
        console.log(msgCommit);
        },
        complete: function (result) {
            infraAvisoCancelar();
        }
    });

}

function cacheMarcaDesmarca(objeto){
    if(document.getElementById('selOperacaoWsdl').value != '') {
        if (objeto.checked) {
            document.getElementById('fldParametrosCache').style.display='';
            buscarParametroWsdlCache('e');
            buscarParametroWsdlCache('s');
        } else {
            document.getElementById('fldParametrosCache').style.display='none';
            var select = document.getElementById('selCachePrazoExpiracao');
            select.options.length = 0;

            var select = document.getElementById('selCacheDataArmazenamento');
            select.options.length = 0;
        }
    }

}

function operacaoSelecionar(){
    checkbox = document.getElementById('chkSinCache'); 
    if (!preencheCache){
        checkbox.checked=false;
    }
    cacheMarcaDesmarca(checkbox);
    buscarParametroWsdl('e');
    buscarParametroWsdl('s');
}
</script>
<?
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdPetIntegracaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados('auto');
?>
<div class="container">
    <div class="bloco">
        <label class="infraLabelObrigatorio" for="txtNome" id="lblNome">Nome:</label>
        <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdPetIntegracaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>

    <div class="clear">&nbsp;</div>

    <div class="bloco">
        <label class="infraLabelObrigatorio" for="selMdPetIntegFuncionalid" id="lblMdPetIntegFuncionalid">Funcionalidade:</label>
        <select id="selMdPetIntegFuncionalid" name="selMdPetIntegFuncionalid" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelMdPetIntegFuncionalid?>
        </select>            
    </div>

    <div class="clear">&nbsp;</div>

    <div style="margin-top: 15px!important;">
        <fieldset class="infraFieldset" style="width:75%;">
            <legend class="infraLegend">&nbsp;Indica��o de Integra��o com a Receita Federal&nbsp; <img id="imgAjuda2" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('� extremamente recomendado que se utilize Integra��o com a base de dados da Receita Federal para validar se o CPF do Usu�rio Externo que est� formalizando a vincula��o como Respons�vel Legal de Pessoa Jur�dica � de fato do Respons�vel Legal pelo CNPJ constante na Receita Federal. \n \n Caso opte por ativar as funcionalidades afetas a Pessoa Jur�dica e Procura��o Eletr�nica para os Usu�rios Externos Sem Integra��o com a base da Receita Federal, os Usu�rios Externos continuar�o a declarar a responsabilidade, at� penal, sobre as informa��es prestadas, mas poder�o ocorrer contradi��o e, caso necessite, Suspens�o e Altera��o da vincula��o podem ser efetivadas pelo menu Administra��o > Peticionamento Eletr�nico > Vincula��es e Procura��es Eletr�nicas.') ?> alt="Ajuda" class="infraImg"/></legend>
            <div>
                <input <?php echo $staUtilizarWsNao; ?> type="radio" name="rdStaUtilizarWs" id="rdStaUtilizarWsNao" value="N" onclick="habilitaWs()" >
                <label for="rdStaUtilizarWsNao" id="lblStaUtilizarWsNao" class="infraLabelRadio">Sem Integra��o <img id="imgAjuda2" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Ao selecionar esta op��o, o CPF do Usu�rio Externo que est� formalizando a vincula��o como Respons�vel Legal de Pessoa Jur�dica ser� validado por integra��o configurada abaixo se � de fato fato do Respons�vel Legal pelo CNPJ constante na Receita Federal. \n \n Se n�o ocorrer a valida��o o Usu�rio Externo n�o poder� prosseguir com o Peticionamento inicial de Respons�vel Legal de Pessoa Jur�dica.') ?> alt="Ajuda" class="infraImg"/></label>

                <input <?php echo $staUtilizarWsSim; ?> type="radio" name="rdStaUtilizarWs" id="rdStaUtilizarWsSim" value="S" onclick="habilitaWs()">
                <label name="rdStaUtilizarWsSim" id="lblStaUtilizarWsSim" for="rdStaUtilizarWsSim" class="infraLabelRadio">Com Integra��o <img id="imgAjuda2" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Ao selecionar esta op��o, n�o ocorrer� qualquer valida��o se o CPF do Usu�rio Externo que est� formalizando a vincula��o como Respons�vel Legal de Pessoa Jur�dica � de fato do Respons�vel Legal pelo CNPJ constante na Receita Federal, ficando exclusivamente sob responsabilidade, at� penal, da auto declara��o efetivada pelo Usu�rio Externo e documentos que anexar no Peticionamento de formaliza��o.') ?> alt="Ajuda" class="infraImg"/></label>

            </div>
        </fieldset>
    </div>

    <div class="bloco" id="blcTextoSemWs" style="width: 75%;">
        <p style="font-size: 12px; padding-top: 10px">
            <span STYLE="color: red; font-weight: bold">ATEN�AO</span>: � extremamente recomendado que se utilize Integra��o com a base de dados da Receita Federal para validar se o CPF do Usu�rio Externo que est� formalizando a vincula��o como Respons�vel Legal de Pessoa Jur�dica � de fato do Respons�vel Legal pelo CNPJ constante na Receita Federal.<br />
            <br />
            Caso opte por ativar as funcionalidades afetas a Pessoa Jur�dica e Procura��o Eletr�nica para os Usu�rios Externos Sem Integra��o com a base da Receita Federal, n�o ocorrer� qualquer valida��o se o CPF do Usu�rio Externo que est� formalizando a vincula��o como Respons�vel Legal de Pessoa Jur�dica � de fato do Respons�vel Legal pelo CNPJ constante na Receita Federal, ficando exclusivamente sob responsabilidade, at� penal, da auto declara��o efetivada pelo Usu�rio Externo e documentos que anexar no Peticionamento de formaliza��o.<br />
            <br />
            Ao selecionar a op��o Sem Integra��o, contradi��es podem ocorrer e, caso necessite, Suspens�o e Altera��o da vincula��o podem ser efetivadas pelo menu Administra��o > Peticionamento Eletr�nico > Vincula��es e Procura��es Eletr�nicas.
        </p>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="bloco" id="blcEnderecoWs">
        <label id="lblEnderecoWsdl" for="txtEnderecoWsdl" class="infraLabelObrigatorio">Endere�o do Webservice:</label>
        <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdPetIntegracaoDTO->getStrEnderecoWsdl());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <button type="button" accesskey="V" name="btnValidar" id="btnValidar" value="Validar" class="infraButton" onclick="validarWsdl();"><span class="infraTeclaAtalho">V</span>alidar</button>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="bloco"  id="blcOperacaoWs">
        <label id="lblOperacaoWsdl" for="selOperacaoWsdl" class="infraLabelObrigatorio">Opera��o:</label>
        <select id="selOperacaoWsdl" name="selOperacaoWsdl" onchange="operacaoSelecionar()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"></select>
        <select id="selParametrosE" name="selParametrosE[]" multiple style="left:400px;display:none"></select>
        <select id="selParametrosS" name="selParametrosS[]" multiple style="left:500px;display:none"></select>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="bloco" id="blcCacheWs">
        <input type="checkbox" id="chkSinCache" name="chkSinCache" onchange="cacheMarcaDesmarca(this);" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objMdPetIntegracaoDTO->getStrSinCache())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinCache" for="chkSinCache" class="infraLabelCheckbox">Marque caso seu Webservice tenha controle de expira��o de cache</label>
        <img style="margin-bottom: -4px;width:16px; height:16px !important" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Marque caso a opera��o selecionada acima utilize controle de expira��o de cache das informa��es recuperadas da Receita Federal.') ?>alt="Ajuda" class="infraImg"/>
    </div>

    <div class="clear">&nbsp;</div>

    <!-- div id="divSinCache" class="infraDivCheckbox" -->
    <fieldset style="display:none" id="fldParametrosCache" class="infraFieldset">
    <legend class="infraLegend">&nbsp;Par�metros do Cache&nbsp;</legend>
        <div class="container">
            <div class="bloco" style="display:none;">
                <label id="lblCacheDataArmazenamento" for="selCacheDataArmazenamento" class="infraLabelObrigatorio">Campo de Retorno da Data de Armazenamento: <img    src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione o campo da Opera��o que retorna a Data de Armazenamento do cache da informa��es da Receita Federal e que foi utilizado na valida��o do per�odo de expira��o definido nos campo abaixo.') ?>alt="Ajuda" class="infraImg"/>
</label>
                <select id="selCacheDataArmazenamento" style="width:300px;" name="selCacheDataArmazenamento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"></select>
            </div>

            <div class="clear">&nbsp;</div>

            <div class="bloco">
                <label id="lblCachePrazoExpiracao" for="selCachePrazoExpiracao" class="infraLabelObrigatorio">Campo de Entrada para Prazo de Expira��o do Cache: <img    src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione o campo de entrada da Opera��o que define o Prazo de Expira��o do Cache das informa��es da Receita Federal. ') ?>alt="Ajuda" class="infraImg"/>
</label>
                <select id="selCachePrazoExpiracao" style="width:340px;" name="selCachePrazoExpiracao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"></select>
            </div>

             <div class="bloco">
        <label class="infraLabelObrigatorio" for="txtPrazo" id="lblPrazo">Par�metro de Meses de Expira��o: <img    src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" <?= PaginaSEI::montarTitleTooltip('Defina a quantidade de meses que o SEI deve considerar as informa��es de cache da Receita como atualizadas. Se atribuido o valor igual a "0", o SEI ir� ignorar o cache e obter� as informa��es direto da Receita Federal.') ?>alt="Ajuda" class="infraImg"/>
</label>
        <input type="text" id="txtPrazo" style="width:220px;" name="txtPrazo" class="infraText" value="<?php echo $mes[0]; ?>" onkeypress="return infraMascaraNumero(this,event,2);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>

            <div class="clear">&nbsp;</div>

            <!-- div class="bloco">
                <label id="lblQtdMes" for="txtQtdMesx" class="infraLabelObrigatorio">Quantidade de Meses:</label><br>
                <input type="text" id="txtQtdMes" name="txtQtdMes" class="infraText" size=2 value="<?=$objMdPetIntegracaoDTO->getStrNome();?>" onkeypress="return infraMascaraTexto(this,event,2);" maxlength="2" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div-->
        </div>
    </fieldset>
    <!-- /div -->
</div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
<input type="hidden" id="hdnIdMdPetIntegracao" name="hdnIdMdPetIntegracao" value="<?=$objMdPetIntegracaoDTO->getNumIdMdPetIntegracao();?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
