<?php

namespace Empresa;
/**
 * Classe que controle e mantem os dados de empresa.
 *
 * @author Anderson
 * @since 18/11/2017
 */
class Empresa {
    private $id = null;
    private $tipo = null;
    private $cnpj = null;
    private $razao_social = null;
    private $nome_fantasia = null;
    private $unidade = null;
    private $codigo = null;
    private $end_rua = null;
    private $end_numero = null;
    private $end_complemento = null;
    private $end_bairro = null;
    private $end_cidade = null;
    private $end_uf = null;
    private $end_cep = null;
    private $latitude = null;
    private $longitude = null;
    private $telefone_1 = null;
    private $telefone_2 = null;
    private $email = null;
    private $responsavel = null;
    private $responsavel_cargo = null;
    private $responsavel_telefone = null;
    private $responsavel_celular = null;
    private $responsavel_email = null;
    private $criacao_datahora = null;
    private $criacao_usuario = null;
    
    
    public function getId() {
        return $this->id;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function getRazao_social() {
        return $this->razao_social;
    }

    public function getNome_fantasia() {
        return $this->nome_fantasia;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function getCodigo() {
        return $this->codigo;
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

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getTelefone_1() {
        return $this->telefone_1;
    }

    public function getTelefone_2() {
        return $this->telefone_2;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function getResponsavel_cargo() {
        return $this->responsavel_cargo;
    }

    public function getResponsavel_telefone() {
        return $this->responsavel_telefone;
    }

    public function getResponsavel_celular() {
        return $this->responsavel_celular;
    }

    public function getResponsavel_email() {
        return $this->responsavel_email;
    }

    public function getCriacao_datahora() {
        return $this->criacao_datahora;
    }

    public function getCriacao_usuario() {
        return $this->criacao_usuario;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTipo($tipo) {
        $erros = array();
        $tipos = array();
        $conn = \Apoio\Conexoes::conectar170();
        $tipoBruto = $conn->query("select * from kronaone.empresas_tipos")->fetchAll();
        foreach ($tipoBruto as $t){
            array_push($tipos, $t["descricao"]);
        }
        if(strtoupper($tipo) == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "O tipo é obrigatório");
            array_push($erros, $erro);
        }
        if(!in_array(mb_strtoupper($tipo, mb_internal_encoding()), $tipos)){
            $erro = \Apoio\Erro::geraErro("Empresa", "Este tipo não consta em nossa lista de tipos, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tipo = mb_strtoupper($tipo, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCnpj($cnpj) {
        $erros = array();
        if($cnpj == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "O número de CNPJ é obrigatório");
            array_push($erros, $erro);
        }
		
        if(strlen($cnpj) == 11 && is_numeric($cnpj) == TRUE) {
			if(\Apoio\Helpers::valida_cpf($cnpj) == FALSE){
				$erro = \Apoio\Erro::geraErro("Empresa", "CNPJ invalido");
				array_push($erros, $erro);
			}				
		} else {
			if(strlen($cnpj) < 14 || strlen($cnpj) > 14 || is_numeric($cnpj) == FALSE){
				$erro = \Apoio\Erro::geraErro("Empresa", "Formato de CNPJ/CPF invalido, somente será aceito CNPJ com 14 dígitos, CPF com 11 dígitos e somente números");
				array_push($erros, $erro);
			}
			
			if(\Apoio\Helpers::valida_cnpj($cnpj) == FALSE){
				$erro = \Apoio\Erro::geraErro("Empresa", "CNPJ invalido");
				array_push($erros, $erro);
			}	
		}
		
        if(count($erros) > 0){
            return $erros;
        }else{
            $cnpjformatado = "0".substr($cnpj, 0, 2).".".substr($cnpj, 2, 3).".".substr($cnpj, 5,3)."/".substr($cnpj, 8, 4)."-".substr($cnpj, 12, 2);
            $this->cnpj = $cnpjformatado;
            return $erros;
        }
    }

    public function setRazao_social($razao_social) {
        $erros = array();
        if($razao_social == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "A razão social da empresa é obrigatória");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->razao_social = mb_strtoupper($razao_social, mb_internal_encoding());
            return $erros;
        }
    }

    public function setNome_fantasia($nome_fantasia) {
        $erros = array();
        if($nome_fantasia == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "Nome Fantasia da empresa é obrigatório");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->nome_fantasia = mb_strtoupper($nome_fantasia, mb_internal_encoding());
            return $erros;
        }
    }

    public function setUnidade($unidade) {
        $erros = array();
        if($unidade == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "A unidade é obrigatória");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros; 
        }else{
            $this->unidade = mb_strtoupper($unidade, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCodigo($codigo) {
        $erros = array();
        $this->codigo = $codigo;
        return $erros;
    }

    public function setEnd_rua($end_rua) {
        $erros = array();
        if($end_rua == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "Nome da rua é obrigatório");
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
            $erro = \Apoio\Erro::geraErro("Empresa", "O numero é obrigatório");
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
            $erro = \Apoio\Erro::geraErro("Empresa", "O bairro é obrigatório");
            array_push($erros, $erro);
        }
        if(count($erros)>0){
            return $erros;
        }else{
            $this->end_bairro = mb_strtoupper($end_bairro, mb_internal_encoding());
            return $erros;
        }
    }

    public function setEnd_cidade($end_cidade) {
        $erros = array();
        if($end_cidade == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "A cidade é obrigatória");
            array_push($erros, $erro);
        }
        if(count($erros)>0){
            return $erros;
        }else{
            $this->end_cidade = mb_strtoupper($end_cidade, mb_internal_encoding());
            return $erros;
        }
    }

    public function setEnd_uf($end_uf) {
        $erros = array();
        if($end_uf == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "A UF é obrigatória");
            array_push($erros, $erro);
        }
        if(strlen($end_uf) > 2 || strlen($end_uf) < 2){
            $erro = \Apoio\Erro::geraErro("Empresa", "Unidades federativas só serão aceitas com dois caracteres");
            array_push($erros, $erro);
        }
        if(count($erros)>0){
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
            $erro = \Apoio\Erro::geraErro("Motorista", "Quantidade de caracteres invalidos, CEP possui 8 caracteres");
            array_push($erros, $erro);
        }
        if(!is_numeric($end_cep)){
            $erro = \Apoio\Erro::geraErro("motorista", "No CEP não são permitidos outros caracteres alem de numeros");
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

    public function setLatitude($latitude) {
        $erros = array();
        $this->latitude = $latitude;
        return $erros;
    }

    public function setLongitude($longitude) {
        $erros = array();
        $this->longitude = $longitude;
        return $erros;
    }

    public function setTelefone_1($telefone_1) {
        $erros = array();
        if($telefone_1 == ""){
            $erro = \Apoio\Erro::geraErro("Empresa", "O telefone 1 é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($telefone_1) > 10 || strlen($telefone_1) < 10 || is_numeric($telefone_1) == FALSE){
            $erro = \Apoio\Erro::geraErro("Empresa", "O numero de telefone 1 deve conter 10 caracteres ja contando com o DDD, e somente numero");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $telFormatado = "(".substr($telefone_1, 0,2).")".substr($telefone_1, 2, 4)."-".substr($telefone_1, 6, 4);
            $this->telefone_1 = $telFormatado;
            return $erros;
        }
    }

    public function setTelefone_2($telefone_2) {
        $erros = array();
        if($telefone_2 != ""){
            if(strlen($telefone_2) > 10 || strlen($telefone_2) < 10 || is_numeric($telefone_2) == FALSE){
                $erro = \Apoio\Erro::geraErro("Empresa", "O numero de telefone 2 deve conter 10 caracteres ja contando com o DDD, e somente numero");
                array_push($erros, $erro);
            }
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            if($telefone_2 != ""){
                $telFormatado = "(".substr($telefone_2, 0,2).")".substr($telefone_2, 2, 4)."-".substr($telefone_2, 6, 4);
            }else{
                $telFormatado = "";
            }
            $this->telefone_2 = $telFormatado;
            return $erros;
        }
    }

    public function setEmail($email) {
        $erros = array();
        if($email != ""){
            if(\Apoio\Helpers::isMail($email) == FALSE){
                $erro = \Apoio\Erro::geraErro("Empresa", "Formato de email invalido");
                array_push($erros, $erro);
            }
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->email = mb_strtoupper($email, mb_internal_encoding());
            return $erros;
        }
    }

    public function setResponsavel($responsavel) {
        $erros = array();
        $this->responsavel = mb_strtoupper($responsavel, mb_internal_encoding());
        return $erros;
    }

    public function setResponsavel_cargo($responsavel_cargo) {
        $erros = array();
        $this->responsavel_cargo = $responsavel_cargo;
        return $erros;
    }

    public function setResponsavel_telefone($responsavel_telefone) {
        $erros = array();
        $this->responsavel_telefone = mb_strtoupper($responsavel_telefone, mb_internal_encoding());
        return $erros;
    }

    public function setResponsavel_celular($responsavel_celular) {
        $erros = array();
        $this->responsavel_celular = $responsavel_celular;
        return $erros;
    }

    public function setResponsavel_email($responsavel_email) {
        $erros = array();
        if($responsavel_email != ""){
            if(\Apoio\Helpers::isMail($responsavel_email) == FALSE){
                $erro = \Apoio\Erro::geraErro("Empresa", "Fromato de email de responsavel invalido");
                array_push($erros, $erro);
            }
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->responsavel_email = mb_strtoupper($responsavel_email, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCriacao_datahora($criacao_datahora) {
        $erros = array();
        $this->criacao_datahora = $criacao_datahora;
        return $erros;
    }

    public function setCriacao_usuario($criacao_usuario) {
        $erros = array();
        $this->criacao_usuario = mb_strtoupper($criacao_usuario, mb_internal_encoding());
        return $erros;
    }
    
    
    public function cadastrarEmpresa($usuario){
        $conn = \Apoio\Conexoes::conectar170();
        
        //Verificar se a empresa ja existe e retornar o id do mesmo--------------------
        $sql = "select emp.id from kronaone.empresas emp where emp.cnpj = :cnpj";
        $stm = $conn->prepare($sql);
        $cnpj = $this->getCnpj();
        $stm->bindParam(":cnpj", $cnpj);
        $stm->execute();
        $empresaBanco = $stm->fetchAll();
        //Verificar se a empresa ja existe e retornar o id do mesmo--------------------
        
        if(count($empresaBanco) > 0 && empty($usuario[0]['ws_atualiza_empresa'])) {
			return $empresaBanco[0]["id"];			
		}	
		
		$sql = (count($empresaBanco) > 0 ? "update" : "insert into")." 
			kronaone.empresas set 
            tipo = :tipo, 
            cnpj = :cnpj, 
            razao_social = :razao_social, 
            nome_fantasia = :nome_fantasia, 
            unidade = :unidade, 
            codigo = :codigo, 
            end_rua = :end_rua, 
            end_numero = :end_numero, 
            end_complemento = :end_complemento, 
            end_bairro = :end_bairro, 
            end_cidade = :end_cidade, 
            end_uf = :end_uf, 
            end_cep = :end_cep, 
            latitude = :latitude, 
            longitude = :longitude, 
            telefone_1 = :telefone_1, 
            telefone_2 = :telefone_2, 
            email = :email, 
            responsavel = :responsavel, 
            responsavel_cargo = :responsavel_cargo, 
            responsavel_telefone = :responsavel_telefone, 
            responsavel_celular = :responsavel_celular, 
            responsavel_email = :responsavel_email,
			".(count($empresaBanco) > 0 ? 
			
			"alteracao_datahora = :alteracao_datahora,
			alteracao_usuario = :alteracao_usuario
			where id=:id" : 
				
			"criacao_datahora = :criacao_datahora,
			criacao_usuario = :criacao_usuario
			
			");
            
		$stm2 = $conn->prepare($sql);
		
		$tipo = $this->getTipo(); 
		$cnpj = $this->getCnpj(); 
		$razao_social = $this->getRazao_social();
		$nome_fantasia = $this->getNome_fantasia(); 
		$unidade = $this->getUnidade();
		$codigo = $this->getCodigo();
		$end_rua = $this->getEnd_rua(); 
		$end_numero = $this->getEnd_numero(); 
		$end_complemento = $this->getEnd_complemento(); 
		$end_bairro = $this->getEnd_bairro(); 
		$end_cidade = $this->getEnd_cidade(); 
		$end_uf = $this->getEnd_uf();
		$end_cep = $this->getEnd_cep(); 
		$latitude = $this->getLatitude(); 
		$longitude = $this->getLongitude(); 
		$telefone_1 = $this->getTelefone_1(); 
		$telefone_2 = $this->getTelefone_2(); 
		$email = $this->getEmail(); 
		$responsavel = $this->getResponsavel(); 
		$responsavel_cargo = $this->getResponsavel_cargo();
		$responsavel_telefone = $this->getResponsavel_telefone(); 
		$responsavel_celular = $this->getResponsavel_celular(); 
		$responsavel_email = $this->getResponsavel_email();
		$criacao_datahora = date("Y-m-d H:i:s"); 
		$criacao_usuario = $usuario[0]['descricao'];
		
		
		$stm2->bindParam(":tipo", $tipo);
		$stm2->bindParam(":cnpj", $cnpj);
		$stm2->bindParam(":razao_social", $razao_social);
		$stm2->bindParam(":nome_fantasia", $nome_fantasia);
		$stm2->bindParam(":unidade", $unidade);
		$stm2->bindParam(":codigo", $codigo);
		$stm2->bindParam(":end_rua", $end_rua);
		$stm2->bindParam(":end_numero", $end_numero);
		$stm2->bindParam(":end_complemento", $end_complemento);
		$stm2->bindParam(":end_bairro", $end_bairro);
		$stm2->bindParam(":end_cidade", $end_cidade);
		$stm2->bindParam(":end_uf", $end_uf);
		$stm2->bindParam(":end_cep", $end_cep);
		$stm2->bindParam(":latitude", $latitude);
		$stm2->bindParam(":longitude", $longitude);
		$stm2->bindParam(":telefone_1", $telefone_1);
		$stm2->bindParam(":telefone_2", $telefone_2);
		$stm2->bindParam(":email", $email);
		$stm2->bindParam(":responsavel", $responsavel);
		$stm2->bindParam(":responsavel_cargo", $responsavel_cargo);
		$stm2->bindParam(":responsavel_telefone", $responsavel_telefone);
		$stm2->bindParam(":responsavel_celular", $responsavel_celular);
		$stm2->bindParam(":responsavel_email", $responsavel_email);
		
		if(count($empresaBanco) > 0){
			$stm2->bindParam(":alteracao_datahora" , $criacao_datahora);
			$stm2->bindParam(":alteracao_usuario" , $criacao_usuario);
			$stm2->bindParam(":id", $empresaBanco[0]["id"]);
		}
		else {
			$stm2->bindParam(":criacao_datahora" , $criacao_datahora);
			$stm2->bindParam(":criacao_usuario" , $criacao_usuario);
		}
		
		if($stm2->execute()){
			$id_empresa = count($empresaBanco) > 0 ? $empresaBanco[0]["id"] : $conn->lastInsertId();
			return $id_empresa;
		}else{
			return False;
		}
		
	
    }
    
    public function requisitaInterno($header, $dados){
        //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
        $erros = array();
        $tipoRequisicao = $header["CONTENT_TYPE"][0];
        //$dadosBrutos = $dadosReq;
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
        $empresa = new Empresa();
        $campo1 = $empresa->setTipo(is_array($dados['tipo']) == 1 ? "" : $dados['tipo']);
        $campo2 = $empresa->setCnpj(is_array($dados['cnpj']) == 1 ? "" : $dados['cnpj']);
        $campo3 = $empresa->setRazao_social(is_array($dados['razao_social']) == 1 ? "" : $dados['razao_social']);
        $campo4 = $empresa->setNome_fantasia(is_array($dados['nome_fantasia']) == 1 ? "" : $dados['nome_fantasia']);
        $campo5 = $empresa->setUnidade(is_array($dados['unidade']) == 1 ? "" : $dados['unidade']);
        $campo6 = $empresa->setCodigo(is_array($dados['codigo']) == 1 ? "" : $dados['codigo']);
        $campo7 = $empresa->setEnd_rua(is_array($dados['end_rua']) == 1 ? "" : $dados['end_rua']);
        $campo8 = $empresa->setEnd_numero(is_array($dados['end_numero']) == 1 ? "" : $dados['end_numero']);
        $campo9 = $empresa->setEnd_complemento(is_array($dados['end_complemento']) == 1 ? "" : $dados['end_complemento']);
        $campo10 = $empresa->setEnd_bairro(is_array($dados['end_bairro']) == 1 ? "" : $dados['end_bairro']);
        $campo11 = $empresa->setEnd_cidade(is_array($dados['end_cidade']) == 1 ? "" : $dados['end_cidade']);
        $campo12 = $empresa->setEnd_uf(is_array($dados['end_uf']) == 1 ? "" : $dados['end_uf']);
        $campo13 = $empresa->setEnd_cep(is_array($dados['end_cep']) == 1 ? "" : $dados['end_cep']);
        $campo14 = $empresa->setLatitude(is_array($dados['latitude']) == 1 ? "" : $dados['latitude']);
        $campo15 = $empresa->setLongitude(is_array($dados['longitude']) == 1 ? "" : $dados['longitude']);
        $campo16 = $empresa->setTelefone_1(is_array($dados['telefone_1']) == 1 ? "" : $dados['telefone_1']);
        $campo17 = $empresa->setTelefone_2(is_array($dados['telefone_2']) == 1 ? "" : $dados['telefone_2']);
        $campo18 = $empresa->setEmail(is_array($dados['email']) == 1 ? "" : $dados['email']);
        $campo19 = $empresa->setResponsavel(is_array($dados['responsavel']) == 1 ? "" : $dados['responsavel']);
        $campo20 = $empresa->setResponsavel_cargo(is_array($dados['responsavel_cargo']) == 1 ? "" : $dados['responsavel_cargo']);
        $campo21 = $empresa->setResponsavel_telefone(is_array($dados['responsavel_telefone']) == 1 ? "" : $dados['responsavel_telefone']);
        $campo22 = $empresa->setResponsavel_celular(is_array($dados['responsavel_celular']) == 1 ? "" : $dados['responsavel_celular']);
        $campo23 = $empresa->setResponsavel_email(is_array($dados['responsavel_email']) == 1 ? "" : $dados['responsavel_email']);
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
                $campo23
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
            $idEmpresa = $empresa->cadastrarEmpresa($usu);
            if($tipoRequisicao == "application/json"){
                $json['Resposta']['mensagem'] = "Empresa Cadastrada com sucesso";
                $json['Resposta']['idEmpresa'] = $idEmpresa;
                return json_encode($json);
            }else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<resposta/>");
                $xml->addChild("mensagem", "Empresa Cadastrada com sucesso");
                $xml->addChild("idEmpresa", $idEmpresa);
                return $xml->asXML();
            }
        }

    }
}
