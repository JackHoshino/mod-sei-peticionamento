<?
/**
* ANATEL
*
* 14/04/2016 - criado por jaqueline.mendes - CAST
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class TipoProcessoPeticionamentoDTO extends InfraDTO  {

	public function getStrNomeTabela() {
		return 'md_pet_tipo_processo';
	}
	
	public function montar() {
	
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
				'IdTipoProcessoPeticionamento',
				'id_md_pet_tipo_processo');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
				'IdProcedimento',
				'id_tipo_procedimento');

		//#6155 - removido. Agora ser�o n Unidades
		//$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
		//		'IdUnidade',
		//		'id_unidade');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
				'IdSerie',
				'id_serie');
		
		//REMOVIDO MODELO A PEDIDO DO CLIENTE
		//$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
		//		'IdModelo',
		//		'id_modelo');
		
		//ADICIONADO HIPOTESE LEGAL A PEDIDO DO CLIENTE
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
				'IdHipoteseLegal',
				'id_hipotese_legal');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'StaNivelAcesso',
				'sta_nivel_acesso');
	
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinIIProprioUsuarioExterno',
				'sin_ii_proprio_usuario_externo');
	
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinIIIndicacaoDireta',
				'sin_ii_indicacao_direta');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinIIIndicacaoDiretaCpfCnpj',
				'sin_ii_indicacao_direta_cpf_cnpj');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinIIIndicacaoDiretaContato',
				'sin_ii_indicacao_direta_contato');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinNaUsuarioExterno',
				'sin_na_usuario_externo');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinNaPadrao',
				'sin_na_padrao');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinDocGerado',
				'sin_doc_gerado');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinDocExterno',
				'sin_doc_externo');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'SinAtivo',
				'sin_ativo');
				
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
				'Orientacoes',
				'orientacoes');
				
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProcesso', 'tipo.nome', 'tipo_procedimento tipo');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeHipoteseLegal', 'hl.nome', 'hipotese_legal hl');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 's.nome', 'serie s');
		
		$this->configurarPK('IdTipoProcessoPeticionamento',InfraDTO::$TIPO_PK_NATIVA);
		
		$this->configurarFK('IdProcedimento', 'tipo_procedimento tipo', 'tipo.id_tipo_procedimento');
		$this->configurarFK('IdSerie', 'serie s', 's.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
		
		//ADICIONADO HIPOTESE LEGAL A PEDIDO DO CLIENTE
		$this->configurarFK('IdHipoteseLegal', 'hipotese_legal hl', 'hl.id_hipotese_legal', InfraDTO::$TIPO_FK_OPCIONAL);
				
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelTipoProcessoSerieDTO');
		
		//#6155 - Add Campo a pedindo do cliente
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelTipoProcessoSerieEssDTO');
	}}
?>