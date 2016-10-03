<?php 
//$strLinkAjaxContatos = SessaoSEIExterna::getInstance()->assinarLink('controlador_ajax_externo.php?acao_ajax_externo=contato_pj_vinculada');
$strLinkAjaxContatos = SessaoSEIExterna::getInstance()->assinarLink('/sei/institucional/peticionamento/controlador_ajax_externo.php?acao_ajax_externo=contato_pj_vinculada&id_orgao_acesso_externo=0');
//$strLinkAjaxContatoRI0571 = SessaoSEIExterna::getInstance()->assinarLink('controlador_ajax_externo.php?acao_ajax_externo=contato_pj_vinculada');
$strLinkAjaxCidade = SessaoSEIExterna::getInstance()->assinarLink('controlador_ajax_externo.php?acao_ajax=cidade_montar_select_nome');
?>
<script type="text/javascript">
/**
* ANATEL
*
* 23/09/2016 - criado por marcelo.bezerra@cast.com.br - CAST
*
*/

var objAjaxNomeCidade = null;
var objSelectSiglaEstado = null;
var objSelectNomeCidade = null;
var objAjaxContatoRI0571 = null;
var objAutoCompletarContexto = null;

function selecionarPF(){
  mostrarCamposPF();
}

function selecionarPF1(){
  ocultarComboPJVinculada();
}

function selecionarPF2(){
	mostrarComboPJVinculada();	
}

function ocultarComboPJVinculada(){
  document.getElementById('lblPjVinculada').style.display = 'none';
  document.getElementById('txtPjVinculada').style.display = 'none';
  document.getElementById('txtPjVinculada').value = '';
}

function mostrarComboPJVinculada(){
  document.getElementById('lblPjVinculada').style.display = '';
  document.getElementById('txtPjVinculada').style.display = '';
}

function selecionarPJ(){
	mostrarCamposPJ();
}

function mostrarCamposPF(){
	
  document.getElementById('rdPF1').style.display = '';
  document.getElementById('rdPF2').style.display = '';
  document.getElementById('lblrdPF1').style.display = '';
  document.getElementById('lblrdPF2').style.display = '';

  document.getElementById('lblNome').style.display = '';
  document.getElementById('lblCPF').style.display = '';

  <?php if( !isset( $_GET['cpf']) ) { ?>
  document.getElementById('lblRazaoSocial').style.display = 'none';
  document.getElementById('lblCNPJ').style.display = 'none';
  document.getElementById('txtCNPJ').value='';
  document.getElementById('txtRazaoSocial').value='';
  <?php } ?>
  
  //mostrar campos Vocativo, Tratamento, Cargo
  document.getElementById('lblCargo').style.display = '';
  document.getElementById('lblVocativo').style.display = '';
  document.getElementById('lblTratamento').style.display = '';

  //mostrar campos RG, orgao expedidor, numero da OAB
  document.getElementById('div1').style.display = '';
  
}

function mostrarCamposPJ(){

  <?php if( !isset( $_GET['cnpj']) ) { ?>	
  document.getElementById('rdPF1').style.display = 'none';
  document.getElementById('rdPF2').style.display = 'none';
  
  document.getElementById('rdPF1').checked = false;
  document.getElementById('rdPF2').checked = false;
  document.getElementById('rdPF1').checked = '';
  document.getElementById('rdPF2').checked = '';
  
  document.getElementById('lblrdPF1').style.display = 'none';
  document.getElementById('lblrdPF2').style.display = 'none';
  <?php } ?>
  
  document.getElementById('lblPjVinculada').style.display = 'none';
  document.getElementById('txtPjVinculada').style.display = 'none';
  document.getElementById('txtPjVinculada').value='';

  <?php if( !isset( $_GET['cnpj']) ) { ?>
  document.getElementById('lblNome').style.display = 'none';
  document.getElementById('lblCPF').style.display = 'none';
  document.getElementById('txtNome').value='';
  document.getElementById('txtCPF').value='';
  <?php } ?>
  
  document.getElementById('lblCNPJ').style.display = '';
  document.getElementById('lblRazaoSocial').style.display = '';

  //ocultar campos Vocativo, Tratamento, Cargo
  document.getElementById('lblCargo').style.display = 'none';
  document.getElementById('cargo').value = '';

  document.getElementById('lblVocativo').style.display = 'none';
  document.getElementById('vocativo').value = '';
  
  document.getElementById('lblTratamento').style.display = 'none';
  document.getElementById('tratamento').value = '';

  //mostrar campos RG, orgao expedidor, numero da OAB
  document.getElementById('div1').style.display = 'none';
  
}

function enviarInteressado(){
	
	var arrDados = ["Banana1", "Orange1", "Apple1", "Mango1"];
	arrDados.push("Kiwi1");
	opener.receberInteressado(arrDados, true);

	var arrDados2 = ["Banana2", "Orange2", "Apple2", "Mango2"];
	arrDados2.push("Kiwi2");
	opener.receberInteressado(arrDados2, false);
	
}

function validarFormulario(){

	//valida campo especifica��o
	var textoEspecificacao = document.getElementById("txtEspecificacao").value;

	if( textoEspecificacao == '' ){
      alert('Informe a especifica��o.');
      document.getElementById("txtEspecificacao").focus();
      return false;      
	}

	return true;
}

function inicializar(){

	<?php if( isset( $_GET['edicao'] ) ) { ?>

      var idEdicao = window.opener.document.getElementById("hdnIdEdicao").value;
	  document.getElementById("hdnIdEdicaoAuxiliar").value = idEdicao;
	  document.frmEdicaoAuxiliar.submit();
	  return;
	
	<?php } else { ?>
	
	  var txtcpf = window.opener.document.getElementById("txtCPF").value;
	  var txtcnpj = window.opener.document.getElementById("txtCNPJ").value;
		
	  <?php if( isset( $_GET['cpf'] ) ) { ?>
	  document.getElementById("rdPF").click();
	  document.getElementById("txtCPF").value = txtcpf;
	  <?php } ?>
		
	  <?php if( isset( $_GET['cnpj'] ) ) { ?>
	  document.getElementById("rdPJ").click();
	  document.getElementById("txtCNPJ").value = txtcnpj;
	  <?php } ?>

	  <?php if( isset( $_GET['edicaoExibir'] ) && isset( $_GET['cnpj'] )  ) { ?>
      document.getElementById("txtCNPJ").value = "<?= InfraUtil::formatarCnpj( $_POST['txtCNPJ'] ) ?>";	  
	  <?php } ?>

	  <?php if( isset( $_GET['edicaoExibir'] ) && isset( $_GET['cpf'] ) ) { ?>
	  document.getElementById("txtCPF").value = "<?= InfraUtil::formatarCpf( $_POST['txtCPF'] ) ?>";	  
	  <?php } ?>

	//Preenchimento com o endere�o do contexto
	  //objAutoCompletarInteressado = new infraAjaxAutoCompletar('hdnIdInteressado','txtInteressado','<?=$strLinkAjaxInteressado?>');
      //objAjaxContatoRI0571 = new infraAjaxComplementar('hdnIdContextoContato','txtPjVinculada','<?=$strLinkAjaxContatoRI0571?>');
	  //objAjaxContatoRI0571.limparCampo = false;
	  
	  //objAjaxContatoRI0571.prepararExecucao = function(){
	    //return 'idContextoContato='+document.getElementById('hdnIdContextoContato').value;
	  //}

	  //objAjaxContatoRI0571.processarResultado = function(arr){
		//alert(arr);
	  //}
		
	  debugger;
	  objAutoCompletarContexto = new infraAjaxAutoCompletar('hdnIdContextoContato','txtPjVinculada','<?=$strLinkAjaxContatos?>');
	  objAutoCompletarContexto.limparCampo = false;

	  objAutoCompletarContexto.prepararExecucao = function(){
		debugger;
	    return 'id_tipo_contexto_contato='+document.getElementById('tipoInteressado').value+'&palavras_pesquisa='+document.getElementById('txtPjVinculada').value;
	  };
	  
	  objAutoCompletarContexto.processarResultado = function(id,descricao,complemento){

        console.log("Resultado:" + id );
		  
	    if (id!=''){
	      document.getElementById('hdnIdContextoContato').value = id;
	      document.getElementById('txtPjVinculada').value = descricao;
	      //objAjaxContatoRI0571.executar();
	    }
	    
	  }
	  	
	  //Ajax para carregar as cidades na escolha do estado
	  objAjaxCidade = new infraAjaxMontarSelectDependente('selEstado','selCidade','<?=$strLinkAjaxCidade?>');
	  objAjaxCidade.prepararExecucao = function(){
	    return infraAjaxMontarPostPadraoSelect('null','','null') + '&siglaUf='+document.getElementById('selEstado').value;
	  }
	  objAjaxCidade.processarResultado = function(){
	    //alert('terminou carregamento');
	  }
	  
	  infraEfeitoTabelas();
    
    <?php } ?>
  
}

function returnDateTime(valor){

	valorArray = valor != '' ? valor.split(" ") : '';

	if(Array.isArray(valorArray)){
	  var data = valorArray[0]
	  data = data.split('/');
	  var mes = parseInt(data[1]) - 1; 
      var horas = valorArray[1].split(':');

      var segundos = typeof horas[2] != 'undefined' ?  horas[2] : 00;
	  var dataCompleta = new Date(data[2], mes  ,data[0], horas[0] , horas[1] , segundos);
	  return dataCompleta;
	}

	return false;
}

function OnSubmitForm() {
		
	return true;
}

function salvar(){
	
	//validar interessado
	var interessado1 = document.frmCadastro.tipoPessoa.value;
	var interessado2 = '';
	var tipoPessoaPF = document.frmCadastro.tipoPessoaPF;
	
	if( tipoPessoaPF != null && tipoPessoaPF != undefined ){
	  interessado2 = tipoPessoaPF.value;
	}
	
	if( interessado1 == '' ){
      alert('Informe o Interessado.');
      return;
	}

	else if( interessado1 == 'pf' && interessado2 == ''){
	  alert('Informe se o interessado possui ou n�o v�nculo com Pessoa Jur�dica.');
	  return;
	}
	
	//validar tipo de interessado
	var tipoInteressado = document.getElementById('tipoInteressado').value;
	
	if( tipoInteressado == '' || tipoInteressado == 'null'  ){
		alert('Informe o tipo de interessado.');
		document.getElementById('tipoInteressado').focus();
		return;
	}
	
	//validar nome ou razao social
	var nome = document.getElementById('txtNome').value;
	var razaoSocial = document.getElementById('txtRazaoSocial').value;

	if( interessado1 == 'pf' && nome == '' ){
		alert('Informe o nome.');
		document.getElementById('txtNome').focus();
		return;
		 
	} else if( interessado1 == 'pj' && razaoSocial == '' ){
		alert('Informe a raz�o social.');
		document.getElementById('txtRazaoSocial').focus();
		return;
	}
	
	//validar pj vinculada (caso exista)
	var pjVinculada = document.getElementById('txtPjVinculada').value;

	if( interessado1 == 'pf' && interessado2 == '1' && pjVinculada == '' ){
		alert('Informe a pessoa jur�dica vinculada.');
		document.getElementById('txtPjVinculada').focus();
		return;		 
	}
	
	//validar cpf ou cnpj
	var cpf = document.getElementById('txtCPF').value;
	var cnpj = document.getElementById('txtCNPJ').value;

	if( interessado1 == 'pf' && cpf == '' ){
      alert('Informe o CPF.');
      document.getElementById('txtCPF').focus();
      return;
      
	} else if( interessado1 == 'pj' && cnpj == '' ){
	  alert('Informe o CNPJ.');
	  document.getElementById('txtCNPJ').focus();
	  return;
	}

	//rg
	var rg = document.getElementById('rg').value;

	if( interessado1 == 'pf' && rg == '' ){
	  alert('Informe o RG.');
	  document.getElementById('rg').focus();
	  return;
	}
	
	//orgao expedidor
	var orgaoExpedidor = document.getElementById('orgaoExpedidor').value;

	if( interessado1 == 'pf' && orgaoExpedidor == '' ){
	  alert('Informe o �rg�o expedidor.');
	  document.getElementById('orgaoExpedidor').focus();
	  return;
	}
	
	//tratamento
	var tratamento = document.getElementById('tratamento').value;

	if( interessado1 == 'pf' && ( tratamento == 'null' || tratamento == '') ){
	  alert('Informe o tratamento.');
	  document.getElementById('tratamento').focus();
	  return;
	}
	
	//cargo
	var cargo = document.getElementById('cargo').value;

	if( interessado1 == 'pf' && ( cargo == 'null' || cargo == '') ){
	  alert('Informe o cargo.');
	  document.getElementById('cargo').focus();
	  return;
	}
	
	//vocativo
	var vocativo = document.getElementById('vocativo').value;

	if( interessado1 == 'pf' && ( vocativo == 'null' || vocativo == '') ){
	  alert('Informe o vocativo.');
	  document.getElementById('vocativo').focus();
	  return;
	}
		
	//telefone
	var telefone = document.getElementById('telefone').value;

	if( telefone == ''){
	  alert('Informe o telefone.');
	  document.getElementById('telefone').focus();
	  return;
	}

	//email
	if (!infraValidarEmail(infraTrim(document.getElementById('email').value))){
		
		alert('E-mail Inv�lido.');
		document.getElementById('email').focus();
		return false;
	
	}
	
	//endereco
	var endereco = document.getElementById('endereco').value;

	if( endereco == ''){
	  alert('Informe o endere�o.');
	  document.getElementById('endereco').focus();
	  return;
	}
	
	//bairro
	var bairro = document.getElementById('bairro').value;

	if( bairro == ''){
	  alert('Informe o  bairro.');
	  document.getElementById('bairro').focus();
	  return;
	}
	
	//pais
	/*
	var pais = document.getElementById('pais').value;

	if( pais == ''){
	  alert('Informe o  pa�s.');
	  document.getElementById('pais').focus();
	  return;
	} */
	
	//estado
	var estado = document.getElementById('selEstado').value;

	if( estado == ''){
	  alert('Informe o  estado.');
	  document.getElementById('estado').focus();
	  return;
	}
	
	//cidade
	var cidade = document.getElementById('selCidade').value;

	if( cidade == ''){
	  alert('Informe a cidade.');
	  document.getElementById('cidade').focus();
	  return;
	}
	
	//cep
	var cep = document.getElementById('cep').value;

	if( cep == '' ){
	  alert('Informe o CEP.');
	  document.getElementById('cep').focus();
	  return;
	}

	document.frmCadastro.submit();
	
}

</script>