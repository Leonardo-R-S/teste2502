<?php

namespace Motorista;



/**
 * Classe que contorla e mantem dados de motorista.
 *
 * @author Anderson Moreira
 * @since 15/11/2017
 */
class Motorista {
    private $nome = null;
    private $cpf = null;
    private $rg = null;
    private $orgao_emissor = null;
    private $data_nascimento = null;
    private $nome_mae = null;
    private $estado_civil = null;
    private $escolaridade = null;
    private $cnh_numero = null;
    private $cnh_categoria = null;
    private $cnh_vencimento = null;
    private $end_rua = null;
    private $end_numero = null;
    private $end_complemento = null;
    private $end_bairro = null;
    private $end_cidade = null;
    private $end_uf = null;
    private $end_cep = null;
    private $tel_fixo = null;
    private $tel_celular = null;
    private $nextel = null;
    private $mopp = null;
    private $aso = null;
    private $cdd = null;
    private $capacitacao = null;
    private $vinculo = null;
    private $empresa_id = null;
    private $criacao_datahora = null;
    private $criacao_usuario = null;
    
    
    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getRg() {
        return $this->rg;
    }

    public function getOrgao_emissor() {
        return $this->orgao_emissor;
    }

    public function getData_nascimento() {
        return $this->data_nascimento;
    }

    public function getNome_mae() {
        return $this->nome_mae;
    }

    public function getEstado_civil() {
        return $this->estado_civil;
    }

    public function getEscolaridade() {
        return $this->escolaridade;
    }

    public function getCnh_numero() {
        return $this->cnh_numero;
    }

    public function getCnh_categoria() {
        return $this->cnh_categoria;
    }

    public function getCnh_vencimento() {
        return $this->cnh_vencimento;
    }

    public function getEnd_rua() {
        return $this->end_rua;
    }

    public function getEnd_numero() {
        return $this->end_numero;
    }

    public function getEnd_complemento() {
        return $this->end_complemento;
    }

    public function getEnd_bairro() {
        return $this->end_bairro;
    }

    public function getEnd_cidade() {
        return $this->end_cidade;
    }

    public function getEnd_uf() {
        return $this->end_uf;
    }

    public function getEnd_cep() {
        return $this->end_cep;
    }

    public function getTel_fixo() {
        return $this->tel_fixo;
    }

    public function getTel_celular() {
        return $this->tel_celular;
    }

    public function getNextel() {
        return $this->nextel;
    }

    public function getMopp() {
        return $this->mopp;
    }

    public function getAso() {
        return $this->aso;
    }

    public function getCdd() {
        return $this->cdd;
    }

    public function getCapacitacao() {
        return $this->capacitacao;
    }

    public function getVinculo() {
        return $this->vinculo;
    }

    public function getEmpresa_id() {
        return $this->empresa_id;
    }

    public function getCriacao_datahora() {
        return $this->criacao_datahora;
    }

    public function getCriacao_usuario() {
        return $this->criacao_usuario;
    }

    public function setNome($nome) {
        $erros = array();
        if($nome == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O nome do motorista é obrigatório");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->nome = mb_strtoupper($nome, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCpf($cpf) {
        $erros = array();
        if($cpf == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O CPF é obrigatório");
            array_push($erros, $erro);
        }
        if(\Apoio\Helpers::validaCPF($cpf) == FALSE){
            $erro = \Apoio\Erro::geraErro("Motorista", "CPF invalido");
        }
        if(strlen($cpf)<11 || strlen($cpf)>11){
            $erro = \Apoio\Erro::geraErro("Motorista", "Quantidade de caracteres inválidos, CPF possui 11 caracteres.");
            array_push($erros, $erro);
        }
        if(!is_numeric($cpf)){
            $erro = \Apoio\Erro::geraErro("motorista", "No CPF não são permitidos outros caracteres além de números.");
            array_push($erros, $erro);
        }
        
        if(count($erros)>0){
            return $erros;
        }else{
            $cpfFormatado = substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9, 2);
            $this->cpf = $cpfFormatado;
            return $erros;
        }
    }

    public function setRg($rg) {
        $erros = array();
        if($rg == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O RG é obrigatório");
            array_push($erros, $erro);
        }
        
        if(count($erros)> 0){
            return $erros;
        }else{
            $this->rg = mb_strtoupper($rg, mb_internal_encoding());
            return $erros;
        }
        
    }

    public function setOrgao_emissor($orgao_emissor) {
        $erros = array();
        $this->orgao_emissor = mb_strtoupper($orgao_emissor, mb_internal_encoding());
        return $erros;
    }

    public function setData_nascimento($data_nascimento) {
        $erros = array();
        if($data_nascimento == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "Data de nascimento é obrigatória");
            array_push($erros, $erro);
        }
        if(strlen($data_nascimento) > 10 || strlen($data_nascimento) < 10 || strpos($data_nascimento, "/") === FALSE || strpos($data_nascimento, "/") !== 2){
            $erro = \Apoio\Erro::geraErro("Motorista", "O formato de data de nascimento deve seguir o modelo DD/MM/AAAA");
            array_push($erros, $erro);
        }
        
        $data_atual = new \DateTime(date("Y-m-d"));
        $data_nasc = new \DateTime(implode("-", array_reverse(explode("/", $data_nascimento))));
        $dif = $data_atual->diff($data_nasc);
        if($dif->y < 18){
            $erro = \Apoio\Erro::geraErro("Motorista", "O motorista não pode ser menor de idade.");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $data_nascimento)));
            $this->data_nascimento = $data;
            return $erros;
        }
    }

    public function setNome_mae($nome_mae) {
        $erros = array();
        $this->nome_mae = mb_strtoupper($nome_mae, mb_internal_encoding());
        return $erros;
    }

    public function setEstado_civil($estado_civil) {
        $erros = array();
        $this->estado_civil = mb_strtoupper($estado_civil, mb_internal_encoding());
        return $erros;
    }

    public function setEscolaridade($escolaridade) {
        $erros = array();
        $this->escolaridade = mb_strtoupper($escolaridade, mb_internal_encoding());
        return $erros;
    }

    public function setCnh_numero($cnh_numero) {
        $erros = array();
        if($cnh_numero == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "Número de CNH é obrigatório.");
            array_push($erros, $erro);
        }
        if(!is_numeric($cnh_numero)){
            $erro = \Apoio\Erro::geraErro("Motorista", "São peritos apenas números na CNH.");
            array_push($erros, $erro);
        }
        if(strlen($cnh_numero) > 11 || strlen($cnh_numero) < 11){
            $erro = \Apoio\Erro::geraErro("Motorista", "CNH deve possui 11 números.");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->cnh_numero = $cnh_numero;
            return $erros;
        }
        
    }

    public function setCnh_categoria($cnh_categoria) {
        $erros = array();
        $categorias = array("A", "AB", "AC", "AD", "AE", "B", "C", "D", "E");
        if($cnh_categoria == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "Categoria da CNH é obrigatória");
            array_push($erros, $erro);
        }
        if(!in_array(mb_strtoupper($cnh_categoria, mb_internal_encoding()), $categorias)){
            $erro = \Apoio\Erro::geraErro("Motorista", "A categoria informada não existe");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->cnh_categoria = mb_strtoupper($cnh_categoria, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCnh_vencimento($cnh_vencimento) {
        $erros = array();
        if($cnh_vencimento == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "Data de vencimento da CNH é obrigatória");
            array_push($erros, $erro);
        }
        if(strlen($cnh_vencimento) > 10 || strlen($cnh_vencimento) < 10 || strpos($cnh_vencimento, "/") === FALSE || strpos($cnh_vencimento, "/") !== 2){
            $erro = \Apoio\Erro::geraErro("Motorista", "O formato de data de vencimento da CNH deve seguir o modelo DD/MM/AAAA");
            array_push($erros, $erro);
        }
        
        $data_venc = new \DateTime(implode("-", array_reverse(explode("/", $cnh_vencimento))));
        $data_venc->add(new \DateInterval('P30D'));
        $data_atual = new \DateTime(date("Y-m-d"));
        if($data_atual > $data_venc){
            $erro = \Apoio\Erro::geraErro("Motorista", "A CNH deste motorista está vencida");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $cnh_vencimento)));
            $this->cnh_vencimento = $data;
            return $erros;
        }
    }

    public function setEnd_rua($end_rua) {
        $erros = array();
        if($end_rua == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "Rua é obrigatório");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_rua = mb_strtoupper($end_rua, mb_internal_encoding());
            return $erros;
        }
        
    }

    public function setEnd_numero($end_numero) {
        $erros = array();
        if($end_numero == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O número do endereço não pode ser em branco.");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_numero = $end_numero;
            return $erros;
        }
        
    }

    public function setEnd_complemento($end_complemento) {
        $erros = array();
        $this->end_complemento = mb_strtoupper($end_complemento, mb_internal_encoding());
        return $erros;
    }

    public function setEnd_bairro($end_bairro) {
        $erros = array();
        if($end_bairro == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O bairro é obrigatório.");
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_bairro = mb_strtoupper($end_bairro, mb_internal_encoding());
            return $erros;
        }
        
    }

    public function setEnd_cidade($end_cidade) {
        $erros = array();
        if($end_cidade == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "A cidade não pode ficar em branco");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_cidade = mb_strtoupper($end_cidade, mb_internal_encoding());
            return $erros;
        }
    }

    public function setEnd_uf($end_uf) {
        $erros = array();
        if($end_uf == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "UF é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($end_uf) > 2 || strlen($end_uf) < 2){
            $erro = \Apoio\Erro::geraErro("Motorista", "O formato da UF deve ser com dois caracteres Ex: SP");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_uf = mb_strtoupper($end_uf, mb_internal_encoding());
            return $erros;
        }
    }

    public function setEnd_cep($end_cep) {
        $erros = array();
        if($end_cep == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O CEP é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($end_cep)< 8 || strlen($end_cep)>8){
            $erro = \Apoio\Erro::geraErro("Motorista", "Quantidade de caracteres inválidos, CEP possui 8 caracteres");
            array_push($erros, $erro);
        }
        if(!is_numeric($end_cep)){
            $erro = \Apoio\Erro::geraErro("motorista", "No CEP não são permitidos outros caracteres além de números.");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $cepFormatado = substr($end_cep, 0, 5)."-".substr($end_cep, 4, 3);
            $this->end_cep = $cepFormatado;
            return $erros;
        }
        
    }

    public function setTel_fixo($tel_fixo) {
        $erros = array();
        if($tel_fixo != ""){
            if(strlen($tel_fixo) > 10 || strlen($tel_fixo) < 10 || is_numeric($tel_fixo) == false){
                $erro = \Apoio\Erro::geraErro("Motorista", "O número de telefone fixo deve conter 10 números já com DDD, ou seja sem barras ou parênteses");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros)>0){
            return $erros;
        }else{
            if($tel_fixo != ""){
                $telFormatado = "(".substr($tel_fixo, 0, 2).")".substr($tel_fixo, 1, 4)."-".substr($tel_fixo, 5, 4);
            }else{
                $telFormatado = "";
            }
            $this->tel_fixo = $telFormatado;
            return $erros;
        }
    }

    public function setTel_celular($tel_celular) {
        $erros = array();
        if($tel_celular == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O numero de telefone celular é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($tel_celular) > 11 || strlen($tel_celular) < 11 || is_numeric($tel_celular) == false){
            $erro = \Apoio\Erro::geraErro("Motorista", "O número de telefone celular deve conter 11 números já com DDD, ou seja sem barras ou parênteses");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tel_celular = $tel_celular;
            return $erros;
        }
    }

    public function setNextel($nextel) {
        $erros = array();
        $this->nextel = $nextel;
        return $erros;
    }

    public function setMopp($mopp) {
        $erros = array();
        if($mopp != ""){
            if(strlen($mopp) > 10 || strlen($mopp) < 10 || strpos($mopp, "/") === FALSE || strpos($mopp, "/") !== 2){
                $erro = \Apoio\Erro::geraErro("Motorista", "O formato da data do curso de MOPP deve seguir o modelo DD/MM/AAAA");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $mopp)));
            $this->mopp = $data;
            return $erros;
        }
    }

    public function setAso($aso) {
        $erros = array();
        if($aso != ""){
            if(strlen($aso) > 10 || strlen($aso) < 10 || strpos($aso, "/") === FALSE || strpos($aso, "/") !== 2){
                $erro = \Apoio\Erro::geraErro("Motorista", "O formato da data do curso de ASO deve seguir o modelo DD/MM/AAAA");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $aso)));
            $this->aso = $data;
            return $erros;
        }
    }

    public function setCdd($cdd) {
        $erros = array();
        if($cdd != ""){
            if(strlen($cdd) > 10 || strlen($cdd) < 10 || strpos($cdd, "/") === FALSE || strpos($cdd, "/") !== 2){
                $erro = \Apoio\Erro::geraErro("Motorista", "O formato da data do curso de CDD deve seguir o modelo DD/MM/AAAA");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $cdd)));
            $this->cdd = $data;
            return $erros;
        }
    }

    public function setCapacitacao($capacitacao) {
        $erros = array();
        if($capacitacao != ""){
            if(strlen($capacitacao) > 10 || strlen($capacitacao) < 10 || strpos($capacitacao, "/") === FALSE || strpos($capacitacao, "/") !== 2){
                $erro = \Apoio\Erro::geraErro("Motorista", "O formato da data do curso de capacitação deve seguir o modelo DD/MM/AAAA");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $capacitacao)));
            $this->capacitacao = $data;
            return $erros;
        }
    }

    public function setVinculo($vinculo) {
        $erros = array();
        $listaVinculo = array("FROTA", "AGREGADO", "CARRETEIRO");
        if($vinculo == ""){
            $erro = \Apoio\Erro::geraErro("Motorista", "O vínculo é obrigatório.");
            array_push($erros, $erro);
        }
        if(!in_array(mb_strtoupper($vinculo, mb_internal_encoding()), $listaVinculo)){
            $erro = \Apoio\Erro::geraErro("Motorista", "O vínculo somente pode ser 'FROTA', 'AGREGADO' OU 'CARRETEIRO'");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->vinculo = mb_strtoupper($vinculo, mb_internal_encoding());
            return $erros;
        }
        
    }

    public function setEmpresa_id($empresa_id) {
        $erros = array();
        $this->empresa_id = $empresa_id;
        return $erros;
    }

    public function setCriacao_datahora() {
        $erros = array();
        $this->criacao_datahora = date("Y-m-d H:i:s");
        return $erros;
    }

    public function setCriacao_usuario($criacao_usuario) {
        $erros = array();
        $this->criacao_usuario = mb_strtoupper($criacao_usuario, mb_internal_encoding());
        return $erros;
    }

    
	function bindParams($conn, $sql, $usuario, $insert) {
			
		$smt2 = $conn->prepare($sql);
		
		$nome = $this->getNome();
		$cpf = $this->getCpf();
		$rg = $this->getRg();
		$orgao_emissor = $this->getOrgao_emissor();
		$data_nascimento = $this->getData_nascimento();
		$nome_mae = $this->getNome_mae();
		$estado_civil = $this->getEstado_civil();
		$escolaridade = $this->getEscolaridade();
		$cnh_numero = $this->getCnh_numero();
		$cnh_categoria = $this->getCnh_categoria();
		$cnh_vencimento = $this->getCnh_vencimento();
		$end_rua = $this->getEnd_rua();
		$end_numero = $this->getEnd_numero();
		$end_complemento = $this->getEnd_complemento();
		$end_bairro = $this->getEnd_bairro();
		$end_cidade = $this->getEnd_cidade();
		$end_uf = $this->getEnd_uf();
		$end_cep = $this->getEnd_cep();
		$tel_fixo = $this->getTel_fixo();
		$tel_celular = $this->getTel_celular();
		$nextel = $this->getNextel();
		$mopp = $this->getMopp();
		$aso = $this->getAso();
		$cdd = $this->getCdd();
		$capacitacao = $this->getCapacitacao();
		$vinculo = $this->getVinculo();
		$empresa_id = $this->getEmpresa_id();
		$criacao_datahora = date("Y-m-d H:i:s");
		$criacao_usuario = 'WS-'.$usuario[0]['descricao'];
		
		$smt2->bindParam(":nome", $nome);
		$smt2->bindParam(":cpf", $cpf);
		$smt2->bindParam(":rg", $rg);
		$smt2->bindParam(":orgao_emissor", $orgao_emissor);
		$smt2->bindParam(":data_nascimento", $data_nascimento);
		$smt2->bindParam(":nome_mae", $nome_mae);
		$smt2->bindParam(":estado_civil", $estado_civil);
		$smt2->bindParam(":escolaridade", $escolaridade);
		$smt2->bindParam(":cnh_numero", $cnh_numero);
		$smt2->bindParam(":cnh_categoria", $cnh_categoria);
		$smt2->bindParam(":cnh_vencimento", $cnh_vencimento);
		$smt2->bindParam(":end_rua", $end_rua);
		$smt2->bindParam(":end_numero", $end_numero);
		$smt2->bindParam(":end_complemento", $end_complemento);
		$smt2->bindParam(":end_bairro", $end_bairro);
		$smt2->bindParam(":end_cidade", $end_cidade);
		$smt2->bindParam(":end_uf", $end_uf);
		$smt2->bindParam(":end_cep", $end_cep);
		$smt2->bindParam(":tel_fixo", $tel_fixo);
		$smt2->bindParam(":tel_celular", $tel_celular);
		$smt2->bindParam(":nextel", $nextel);
		$smt2->bindParam(":mopp", $mopp);
		$smt2->bindParam(":aso", $aso);
		$smt2->bindParam(":cdd", $cdd);
		$smt2->bindParam(":capacitacao", $capacitacao);
		$smt2->bindParam(":vinculo", $vinculo);
		$smt2->bindParam(":empresa_id", $empresa_id);
		if ($insert) {
			$smt2->bindParam(":criacao_datahora", $criacao_datahora);
			$smt2->bindParam(":criacao_usuario", $criacao_usuario);
		}
		else {
			$smt2->bindParam(":alteracao_datahora", $criacao_datahora);
			$smt2->bindParam(":alteracao_usuario", $criacao_usuario);
		}
		
		return $smt2;
	}
	
    function updateQuery() {
		return 
			"UPDATE motoristas SET
				nome=:nome, cpf=:cpf, rg=:rg, orgao_emissor=:orgao_emissor, data_nascimento=:data_nascimento, 
				nome_mae=:nome_mae, estado_civil=:estado_civil, escolaridade=:escolaridade, 
				cnh_numero=:cnh_numero, cnh_categoria=:cnh_categoria, cnh_vencimento=:cnh_vencimento, 
				end_rua=:end_rua, end_numero=:end_numero, end_complemento=:end_complemento, end_bairro=:end_bairro, 
				end_cidade=:end_cidade, end_uf=:end_uf, end_cep=:end_cep, 
				tel_fixo=:tel_fixo, tel_celular=:tel_celular, nextel=:nextel, 
				mopp=:mopp, aso=:aso, cdd=:cdd, capacitacao=:capacitacao, vinculo=:vinculo, 
				empresa_id=:empresa_id, alteracao_datahora=:alteracao_datahora, alteracao_usuario=:alteracao_usuario
				WHERE id=:id";
	}
	
	function insertQuery() {
		return 
			"insert into kronaone.motoristas set 
				nome = :nome, 
				cpf = :cpf, 
				rg = :rg, 
				orgao_emissor = :orgao_emissor, 
				data_nascimento = :data_nascimento, 
				nome_mae = :nome_mae, 
				estado_civil = :estado_civil, 
				escolaridade = :escolaridade, 
				cnh_numero = :cnh_numero, 
				cnh_categoria = :cnh_categoria, 
				cnh_vencimento = :cnh_vencimento, 
				end_rua = :end_rua, 
				end_numero = :end_numero, 
				end_complemento = :end_complemento, 
				end_bairro = :end_bairro, 
				end_cidade = :end_cidade, 
				end_uf = :end_uf, 
				end_cep = :end_cep, 
				tel_fixo = :tel_fixo, 
				tel_celular = :tel_celular, 
				nextel = :nextel, 
				mopp = :mopp, 
				aso = :aso, 
				cdd = :cdd, 
				capacitacao = :capacitacao, 
				vinculo = :vinculo, 
				empresa_id = :empresa_id, 
				criacao_datahora = :criacao_datahora, 
				criacao_usuario = :criacao_usuario";
	}

    public function cadastrarMotorista($usuario){
        $conn = \Apoio\Conexoes::conectar170();
        //Verificar se o motorista ja existe e retornar o id do mesmo --------------------
        $sql = "select mot.id from kronaone.motoristas mot where mot.cpf = :cpf";
        $stm = $conn->prepare($sql);
        $cpf = $this->getCpf();
        $stm->bindParam(":cpf", $cpf);
        $stm->execute();
        $motoristaBanco = $stm->fetchAll();
        //Verificar se o motorista ja existe e retornar o id do mesmo --------------------
        if(count($motoristaBanco) > 0){	
			if ($usuario[0]['ws_atualiza_motorista']) { // && $id == $dados['id']) {
				$sql = $this->updateQuery();
				
				$smt2 = $this->bindParams($conn, $sql, $usuario, false);
				
				try {
				$smt2->execute();
				} catch (Exception $ex) { return $ex->getMessage(); }
			}
			
            return $motoristaBanco[0]["id"];
			
        }else{
            //Caso não exista montar a sql de inserção preparando para bindagem dos dados---------
            $sql = $this->insertQuery();
		    $smt2 = $this->bindParams($conn, $sql, $usuario, true);
			
			if($smt2->execute()){
                $id_motorista = $conn->lastInsertId();
                return $id_motorista;
            }else{
                return False;
            }
            
            //Caso não exista montar a sql de inserção preparando para bindagem dos dados---------
        }
        
    }
    
    public function requisitaInterno($header, $dados){
        //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
        $erros = array();
        $tipoRequisicao = $header["CONTENT_TYPE"][0];
        //$dadosBrutos = $req->getBody();
        $dadosHeader = $header;
        //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----



        //Verifica de usuario e senha são validos-------------------------------------------------------------
        $conn = \Apoio\Conexoes::conectar170();
        $sql = "select ope.descricao from kronaone.operacoes ope where ope.descricao = :usuario and ope.senha = :senha ";
        $ste = $conn->prepare($sql);
        $usu = $dadosHeader['HTTP_USUARIO'][0];
        $sen = sha1($dadosHeader['HTTP_SENHA'][0]);
        $ste->bindParam(':usuario', $usu);
        $ste->bindParam(':senha', $sen);
        $ste->execute();
        $usuario = $ste->fetchAll();
        if(count($usuario) < 1){
            return "[DADOS INVALIDOS] - Usuario ou senha estão incorretos";
        }
        //Verifica de usuario e senha são validos-------------------------------------------------------------



        //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
//        if($tipoRequisicao == "application/json"){
//            try{
//                $dados = json_decode($dadosBrutos, true);
//            } catch (Exception $ex) {
//                $erro = Apoio\Erro::geraErro("Motorista", "Formato de json invalidos");
//            }    
//        }else if($tipoRequisicao == "application/xml"){
//            try{
//                $xml = simplexml_load_string($dadosBrutos);
//            } catch (Exception $ex) {
//                $erro = Apoio\Erro::geraErro("Motorista", "Formato de XML invalido");
//            }
//            $json = json_encode($xml);
//            $dados = json_decode($json,TRUE);
//        }else{
//            $erro = Apoio\Erro::geraErro("Motorista", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
//            array_push($erros, $erro);
//            return var_dump($erros);
//        }
        //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----



        //Seta-se todos o campo ja validando pelo pelo objeto motorista um array de erros-------------------
        $motorista = new Motorista();
        $campo1 = $motorista->setNome(is_array($dados["nome"]) == 1 ? "" : $dados["nome"]);
        $campo2 = $motorista->setCpf(is_array($dados["cpf"]) == 1 ? "" : $dados["cpf"]);
        $campo3 = $motorista->setRg(is_array($dados["rg"]) == 1 ? "" : $dados["rg"]);
        $campo4 = $motorista->setOrgao_emissor(is_array($dados["orgao_emissor"]) == 1 ? "" : $dados["orgao_emissor"]);
        $campo5 = $motorista->setData_nascimento(is_array($dados["data_nascimento"]) == 1 ? "" : $dados["data_nascimento"]);
        $campo6 = $motorista->setNome_mae(is_array($dados["nome_mae"]) == 1 ? "" : $dados["nome_mae"]);
        $campo7 = $motorista->setEstado_civil(is_array($dados["estado_civil"]) == 1 ? "" : $dados["estado_civil"]);
        $campo8 = $motorista->setEscolaridade(is_array($dados["escolaridade"]) == 1 ? "" : $dados["escolaridade"]);
        $campo9 = $motorista->setCnh_numero(is_array($dados["cnh_numero"]) == 1 ? "" : $dados["cnh_numero"]);
        $campo10 = $motorista->setCnh_categoria(is_array($dados["cnh_categoria"]) == 1 ? "" : $dados["cnh_categoria"]);
        $campo11 = $motorista->setCnh_vencimento(is_array($dados["cnh_vencimento"]) == 1 ? "" : $dados["cnh_vencimento"]);
        $campo12 = $motorista->setEnd_rua(is_array($dados["end_rua"]) == 1 ? "" : $dados["end_rua"]);
        $campo13 = $motorista->setEnd_numero(is_array($dados["end_numero"]) == 1 ? "" : $dados["end_numero"]);
        $campo14 = $motorista->setEnd_complemento(is_array($dados["end_complemento"]) == 1 ? "" : $dados["end_complemento"]);
        $campo15 = $motorista->setEnd_bairro(is_array($dados["end_bairro"]) == 1 ? "" : $dados["end_bairro"]);
        $campo16 = $motorista->setEnd_cidade(is_array($dados["end_cidade"]) == 1 ? "" : $dados["end_cidade"]);
        $campo17 = $motorista->setEnd_uf(is_array($dados["end_uf"]) == 1 ? "" : $dados["end_uf"]);
        $campo18 = $motorista->setEnd_cep(is_array($dados["end_cep"]) == 1 ? "" : $dados["end_cep"]);
        $campo19 = $motorista->setTel_fixo(is_array($dados["tel_fixo"]) == 1 ? "" : $dados["tel_fixo"]);
        $campo20 = $motorista->setTel_celular(is_array($dados["tel_celular"]) == 1 ? "" : $dados["tel_celular"]);
        $campo21 = $motorista->setNextel(is_array($dados["nextel"]) == 1 ? "" : $dados["nextel"]);
        $campo22 = $motorista->setMopp(is_array($dados["mopp"]) == 1 ? "" : $dados["mopp"]);
        $campo23 = $motorista->setAso(is_array($dados["aso"]) == 1 ? "" : $dados["aso"]);
        $campo24 = $motorista->setCdd(is_array($dados["cdd"]) == 1 ? "" : $dados["cdd"]);
        $campo25 = $motorista->setCapacitacao(is_array($dados["capacitacao"]) == 1 ? "" : $dados["capacitacao"]);
        $campo26 = $motorista->setVinculo(is_array($dados["vinculo"]) == 1 ? "" : $dados["vinculo"]);
        $campo27 = $motorista->setEmpresa_id(is_array($dados["empresa_id"]) == 1 ? "" : $dados["empresa_id"]);
        //Seta-se todos o campo ja validando pelo pelo objeto motorista um array de erros-------------------



        //Junta-se os erros formando um unico array------------------------
        $erros = array_merge(
                $campo1,
                $campo2,
                $campo3,
                $campo4,
                $campo5,
                $campo6,
                $campo7,
                $campo8,
                $campo9,
                $campo10,
                $campo11,
                $campo12,
                $campo13,
                $campo14,
                $campo15,
                $campo16,
                $campo17,
                $campo18,
                $campo19,
                $campo20,
                $campo21,
                $campo22,
                $campo23,
                $campo24,
                $campo25,
                $campo26,
                $campo27
                );
        //Junta-se os erros formando um unico array------------------------



        if(count($erros) > 0){
            //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
            if($tipoRequisicao == "application/json"){
                return \Apoio\Erro::saidaErrosJson($erros);
            }else if($tipoRequisicao == "application/xml"){
                return \Apoio\Erro::saidaErrosXml($erros);
            }
            //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        }else{
            $idMotorista = $motorista->cadastrarMotorista($usu);
            if($tipoRequisicao == "application/json"){
                $json['Resposta']['mensagem'] = "Motorista Cadastrado com sucesso";
                $json['Resposta']['idMotorista'] = $idMotorista;
                return json_encode($json);
            }else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<resposta/>");
                $xml->addChild("mensagem", "Motorista Cadastrado com sucesso");
                $xml->addChild("idMotorista", $idMotorista);
                return $xml->asXML();
            }
        }
    }
}
