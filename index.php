<?php

require_once '../vendor/autoload.php';
require_once './Motorista/Motorista.php';
require_once './Empresa/Empresa.php';
require_once './Veiculo/Veiculo.php';
require_once './Checklist/Checklist.php';
require_once './Apoio/Erro.php';
require_once './Apoio/Conexoes.php';
require_once './Apoio/Helpers.php';

$app = new \Slim\App();

// $app = new \Slim\App([
//     'settings' => [
//         'addContentLengthHeader' => false
//     ]
// ]);


$app->get('/', function(){
    return "[KronaApi] Sistema de API 1.0";
});

function validaLogin($dadosHeader, &$usuario, $conn) {

    $sql = "select ope.* from kronaone.operacoes ope where ope.descricao = :usuario and ope.senha = :senha ";
    $ste = $conn->prepare($sql);
    $usu = $dadosHeader['HTTP_USUARIO'][0];
    $sen = sha1($dadosHeader['HTTP_SENHA'][0]);
    $ste->bindParam(':usuario', $usu);
    $ste->bindParam(':senha', $sen);
    $ste->execute();
    $usuario = $ste->fetchAll();
    if(count($usuario) < 1){
        $return = array("erro" => "[DADOS INVALIDOS] - Usuario ou senha estão incorretos");
        return json_encode($return);
        //return "";
    }
	return "";
}

function inicializa($conn, $header, &$tempo_execucao) {
	require 'tempo-execucao.php';
	$tempo_execucao = new tempo_execucao($conn);
	return $tempo_execucao->gravar(array('tipo'=>'integracao_api', 'usuario'=>$header['HTTP_USUARIO'][0]));
}

function finaliza($conn, $tempo_execucao, $exec_id, $message, $dados) {	
	if (is_array($message)) {
		$message = json_encode($message, JSON_UNESCAPED_UNICODE);
	}
	
	if (is_array($dados)) {
		$dados = json_encode($message, JSON_UNESCAPED_UNICODE);
	}
	
	$tempo_execucao->gravar(array('id'=>$exec_id, 'observacao'=>$message.' '.$dados));
	unset($conn);
	$conn = null;
}

$app->post('/CadastroMotorista', function(\Slim\Http\Request $req) {
	// teste
	// return $resp->withStatus(200)
				// ->withHeader('Content-Type','application/json')
				// ->write(json_encode(['id'=>1]));
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    $erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
    
    //Verifica de usuario e senha são validos----------------------------------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Motorista", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Motorista", "Formato de XML invalido");
        }
        $json = json_encode($xml);
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Motorista", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    
    //Seta-se todos o campo ja validando pelo pelo objeto motorista um array de erros-------------------
    $motorista = new Motorista\Motorista();
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
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
    }else{		
		//try {
        $idMotorista = $motorista->cadastrarMotorista($usuario);
        //catch (Exception $err) { return $err->getMessage(); }
		if($tipoRequisicao == "application/json"){
			$json['Resposta']['mensagem'] = "Motorista Cadastrado com sucesso";
            $json['Resposta']['idMotorista'] = $idMotorista;
			finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
            return json_encode($json);
        }else if($tipoRequisicao == "application/xml"){

			$xml = new SimpleXMLElement("<resposta/>");
            $xml->addChild("mensagem", "Motorista Cadastrado com sucesso");
            $xml->addChild("idMotorista", $idMotorista);
			finaliza($conn, $tempo_execucao, $exec_id, $xml, $dados);
            return $xml->asXML();
        }
    }
});

$app->post('/CadastroEmpresa', function(\Slim\Http\Request $req){
    
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    $erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
    
    
    
    //Verifica de usuario e senha são validos-------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Motorista", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Motorista", "Formato de XML invalido");
        }
        $json = json_encode($xml);
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Motorista", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    
    
    //Seta-se todos o campo ja validando pelo pelo objeto motorista um array de erros-------------------
    $empresa = new Empresa\Empresa();
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
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
    }else{
        $idEmpresa = $empresa->cadastrarEmpresa($usuario);
        if($tipoRequisicao == "application/json"){
            $json['Resposta']['mensagem'] = "Empresa Cadastrada com sucesso";
            $json['Resposta']['idEmpresa'] = $idEmpresa;
			finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
            return json_encode($json);
        }else if($tipoRequisicao == "application/xml"){
            $xml = new SimpleXMLElement("<resposta/>");
            $xml->addChild("mensagem", "Empresa Cadastrada com sucesso");
            $xml->addChild("idEmpresa", $idEmpresa);
			finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
            return $xml->asXML();
        }
    }
    
});

$app->post('/CadastroVeiculo', function(\Slim\Http\Request $req){
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    $erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
    
    //Verifica de usuario e senha são validos-------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
        }
        $json = json_encode($xml);
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Veiculo", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    
    //Seta-se todos o campo ja validando pelo pelo objeto veiculo um array de erros-------------------
    $veiculo = new Veiculo\Veiculo();
    $campo1 = $veiculo->setPlaca((is_array($dados["placa"]) == 1 ? "" : $dados["placa"]));
    $campo2 = $veiculo->setRenavam((is_array($dados["renavam"]) == 1 ? "" : $dados["renavam"]));
    $campo3 = $veiculo->setMarca((is_array($dados["marca"]) == 1 ? "" : $dados["marca"]));
    $campo4 = $veiculo->setModelo((is_array($dados["modelo"]) == 1 ? "" : $dados["modelo"]));
    $campo5 = $veiculo->setCor((is_array($dados["cor"]) == 1 ? "" : $dados["cor"]));
    $campo6 = $veiculo->setAno((is_array($dados["ano"]) == 1 ? "" : $dados["ano"]));
    $campo7 = $veiculo->setTipo((is_array($dados["tipo"]) == 1 ? "" : $dados["tipo"]));
    $campo8 = $veiculo->setCapacidade((is_array($dados["capacidade"]) == 1 ? "" : $dados["capacidade"]));
    $campo9 = $veiculo->setNumero_antt((is_array($dados["numero_antt"]) == 1 ? "" : $dados["numero_antt"]));
    $campo10 = $veiculo->setValidade_antt((is_array($dados["validade_antt"]) == 1 ? "" : $dados["validade_antt"]));
    $campo11 = $veiculo->setProprietario((is_array($dados["proprietario"]) == 1 ? "" : $dados["proprietario"]));
    $campo12 = $veiculo->setProprietario_cpfcnpj((is_array($dados["proprietario_cpfcnpj"]) == 1 ? "" : $dados["proprietario_cpfcnpj"]));
    $campo13 = $veiculo->setEnd_rua((is_array($dados["end_rua"]) == 1 ? "" : $dados["end_rua"]));
    $campo14 = $veiculo->setEnd_numero((is_array($dados["end_numero"]) == 1 ? "" : $dados["end_numero"]));
    $campo15 = $veiculo->setEnd_complemento((is_array($dados["end_complemento"]) == 1 ? "" : $dados["end_complemento"]));
    $campo16 = $veiculo->setEnd_bairro((is_array($dados["end_bairro"]) == 1 ? "" : $dados["end_bairro"]));
    $campo17 = $veiculo->setEnd_cidade((is_array($dados["end_cidade"]) == 1 ? "" : $dados["end_cidade"]));
    $campo18 = $veiculo->setEnd_uf((is_array($dados["end_uf"]) == 1 ? "" : $dados["end_uf"]));
    $campo19 = $veiculo->setEnd_cep((is_array($dados["end_cep"]) == 1 ? "" : $dados["end_cep"]));
    $campo20 = $veiculo->setTecnologia((is_array($dados["tecnologia"]) == 1 ? "" : $dados["tecnologia"]));
    $campo21 = $veiculo->setId_rastreador((is_array($dados["id_rastreador"]) == 1 ? "" : $dados["id_rastreador"]));
    $campo22 = $veiculo->setComunicacao((is_array($dados["comunicacao"]) == 1 ? "" : $dados["comunicacao"]));
    $campo23 = $veiculo->setTecnologia_sec((is_array($dados["tecnologia_sec"]) == 1 ? "" : $dados["tecnologia_sec"]));
    $campo24 = $veiculo->setId_rastreador_sec((is_array($dados["id_rastreador_sec"]) == 1 ? "" : $dados["id_rastreador_sec"]));
    $campo25 = $veiculo->setComunicacao_sec((is_array($dados["comunicacao_sec"]) == 1 ? "" : $dados["comunicacao_sec"]));
    $campo26 = $veiculo->setFixo((is_array($dados["fixo"]) == 1 ? "" : $dados["fixo"]));
    //Seta-se todos o campo ja validando pelo pelo objeto veiculo um array de erros-------------------
    
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
            $campo26
            );
    //Junta-se os erros formando um unico array------------------------
    
    if(count($erros) > 0){
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        if($tipoRequisicao == "application/json"){
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
    }else{
		$idVeiculo = $veiculo->cadastrarVeiculo($usuario);
		//return $idVeiculo;
        if($tipoRequisicao == "application/json"){
            $json['Resposta']['mensagem'] = "Veiculo Cadastrado com sucesso";
            $json['Resposta']['idVeiculo'] = $idVeiculo;
			finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
            return json_encode($json);
        }else if($tipoRequisicao == "application/xml"){
            $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
            $xml->addChild("mensagem", "Veiculo Cadastrado com sucesso");
            $xml->addChild("idVeiculo", $idVeiculo);
			finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
            return $xml->asXML();
        }
    }
});

$app->post('/CadastroChecklist_OLD', function(\Slim\Http\Request $req){
    
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    $erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
    
    //Verifica de usuario e senha são validos-------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
        }
        $json = json_encode($xml);
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Veiculo", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    //Seta-se todos o campo ja validando pelo pelo objeto checklist um array de erros-------------------
    $checklist = new Checklist\Checklist();
    $campo1 = $checklist->setVeiculo_id((is_array($dados["veiculo_id"]) == 1 ? "" : $dados["veiculo_id"]));
    $campo2 = $checklist->setReboque1_id(is_array($dados["reboque1_id"]) == 1 ? "" : $dados["reboque1_id"]);
    $campo3 = $checklist->setReboque2_id(is_array($dados["reboque2_id"]) == 1 ? "" : $dados["reboque2_id"]);
    $campo4 = $checklist->setReboque3_id(is_array($dados["reboque3_id"]) == 1 ? "" : $dados["reboque3_id"]);
    $campo5 = $checklist->setOperacao_id(is_array($dados["operacao_id"]) == 1 ? "" : $dados["operacao_id"]);
    $campo6 = $checklist->setTransportador_id(is_array($dados["transportador_id"]) == 1 ? "" : $dados["transportador_id"]);
    $campo7 = $checklist->setMotorista_id(is_array($dados["motorista_id"]) == 1 ? "" : $dados["motorista_id"]);
    $campo8 = $checklist->setTipo_operacao(is_array($dados["tipo_operacao"]) == 1 ? "" : $dados["tipo_operacao"]);
    $campo9 = $checklist->setCond_abastecimento(is_array($dados["cond_abastecimento"]) == 1 ? "" : $dados["cond_abastecimento"]);
    $campo10 = $checklist->setObservacao(is_array($dados["observacao"]) == 1 ? "" : $dados["observacao"]);
    //Seta-se todos o campo ja validando pelo pelo objeto checklist um array de erros-------------------
    
    
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
            $campo10
            );
    //Junta-se os erros formando um unico array------------------------
    
    if(count($erros) > 0){
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        if($tipoRequisicao == "application/json"){
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
    }else{
        $idChecklist = $checklist->cadastraChecklist($usu);
        if($tipoRequisicao == "application/json"){
            $json['Resposta']['mensagem'] = "Checklist Cadastrado com sucesso";
            $json['Resposta']['idChecklist'] = $idChecklist;
			finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
            return json_encode($json);
        }else if($tipoRequisicao == "application/xml"){
            $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
            $xml->addChild("mensagem", "Checklist Cadastrado com sucesso");
            $xml->addChild("idChecklist", $idChecklist);
			finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
            return $xml->asXML();
        }
    }
    
});

$app->post('/CadastroChecklist', function(\Slim\Http\Request $req){
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
	$erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
        
    //Verifica de usuario e senha são validos-------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
    
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
        }
        $json = str_replace("{}", "\"\"", json_encode($xml));
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Veiculo", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    $emp = new Empresa\Empresa();
    $mot = new Motorista\Motorista();
    $vei = new Veiculo\Veiculo();
	
    $resEmp = $emp->requisitaInterno($dadosHeader, $dados["empresa"]);
    //$dados["motorista"]["empresa_id"] = "0";
    $resMot = $mot->requisitaInterno($dadosHeader, $dados["motorista"]);
    $resVei = $vei->requisitaInterno($dadosHeader, $dados["veiculo"]);
    
	
	if(isset($dados["reboque1"])){
        $resReb1 = $vei->requisitaInterno($dadosHeader, $dados["reboque1"]);
    }
    if(isset($dados["reboque2"])){
        $resReb2 = $vei->requisitaInterno($dadosHeader, $dados["reboque2"]);
    }
    if(isset($dados["reboque3"])){
        $resReb3 = $vei->requisitaInterno($dadosHeader, $dados["reboque3"]);
    }
    
    if($tipoRequisicao == "application/json"){
        try{
            $dadosEmp = json_decode($resEmp, true);
            $dadosMot = json_decode($resMot, true);
            $dadosVei = json_decode($resVei, true);
            
				
			if(isset($resReb1)){
                $dadosReb1 = json_decode($resReb1, true);
            }
            if(isset($resReb2)){
                $dadosReb2 = json_decode($resReb2, true);
            }
            if(isset($resReb3)){
                $dadosReb3 = json_decode($resReb3, true);
            }
            
            
            if(isset($dadosEmp["resposta"][0]["erro"])){
                foreach ($dadosEmp["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
			
            if(isset($dadosMot["resposta"][0]["erro"])){
                foreach ($dadosMot["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
				
			//return $dadosVei["resposta"][0]["erro"];
            if(isset($dadosVei["resposta"][0]["erro"])){
                foreach ($dadosVei["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
			
            if(isset($dadosReb1["resposta"][0]["erro"])){
                foreach ($dadosReb1["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
			
            if(isset($dadosReb2["resposta"][0]["erro"])){
                foreach ($dadosReb2["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
			
            if(isset($dadosReb3["resposta"][0]["erro"])){
                foreach ($dadosReb3["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
			
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
        }    
    }else{
        try{
            $xmlEmp = simplexml_load_string($resEmp);
            $xmlMot = simplexml_load_string($resMot);
            $xmlVei = simplexml_load_string($resVei);
            if(isset($resReb1)){
                $xmlReb1 = simplexml_load_string($resReb1);
            }
            if(isset($resReb2)){
                $xmlReb2 = simplexml_load_string($resReb2);
            }
            if(isset($resReb3)){
                $xmlReb3 = simplexml_load_string($resReb3);
            }
            
            $jsonEmp = str_replace("{}", "\"\"", json_encode($xmlEmp));
            $jsonMot = str_replace("{}", "\"\"", json_encode($xmlMot));
            $jsonVei = str_replace("{}", "\"\"", json_encode($xmlVei));
            if(isset($xmlReb1)){
                $jsonReb1 = str_replace("{}", "\"\"", json_encode($xmlReb1));
            }
            if(isset($xmlReb2)){
                $jsonReb2 = str_replace("{}", "\"\"", json_encode($xmlReb2));
            }
            if(isset($xmlReb3)){
                $jsonReb3 = str_replace("{}", "\"\"", json_encode($xmlReb3));
            }

            $dadosEmp = json_decode($jsonEmp,TRUE);
            $dadosMot = json_decode($jsonMot,TRUE);
            $dadosVei = json_decode($jsonVei,TRUE);
            if(isset($jsonReb1)){
                $dadosReb1 = json_decode($jsonReb1,TRUE);
            }
            if(isset($jsonReb2)){
                $dadosReb2 = json_decode($jsonReb2,TRUE);
            }
            if(isset($jsonReb3)){
                $dadosReb3 = json_decode($jsonReb3,TRUE);
            }
            
            if(isset($dadosEmp["erro"])){
                if(isset($dadosEmp["erro"][0])){
                    foreach ($dadosEmp["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosEmp as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
            }
            if(isset($dadosMot["erro"])){
                if(isset($dadosMot["erro"][0])){
                    foreach ($dadosMot["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosMot as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
                
            }
            if(isset($dadosVei["erro"])){
                if(isset($dadosVei["erro"][0])){
                    foreach ($dadosVei["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosVei as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
                
            }
            if(isset($dadosReb1["erro"])){
                if(isset($dadosReb1["erro"][0])){
                    foreach ($dadosReb1["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosReb1 as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
                
            }
            if(isset($dadosReb2["erro"])){
                if(isset($dadosReb2["erro"][0])){
                    foreach ($dadosReb2["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosReb2 as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
            }
            if(isset($dadosReb3["erro"])){
                if(isset($dadosReb3["erro"][0])){
                    foreach ($dadosReb3["erro"] as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }else{
                    foreach ($dadosReb1 as $Ierro){
                        $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                        array_push($erros, $erro);
                    }
                }
            }
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
        }
    }
    
    //Verificar se um checklist ja existe----------------------------------------------------------------------
    $sql1 = "SELECT 
				ckl.id, 
				ckl.checklist_status
				FROM kronaone.check_lists ckl
				join veiculos vei on vei.id = ckl.veiculo_id
				WHERE vei.placa = :placa
				AND ckl.checklist_status NOT IN ('CHECK-LIST APROVADO', 'CHECK-LIST CANCELADO', 'CHECK-LIST REPROVADO')
				LIMIT 1";

    if(strlen($dados["veiculo"]["placa"]) == "6"){
        $placa = substr($dados["veiculo"]["placa"], 0, 3)."-".substr($dados["veiculo"]["placa"], 3, 3);
    }else if(strlen($dados["veiculo"]["placa"]) == "7"){
        $placa = substr($dados["veiculo"]["placa"], 0, 3)."-".substr($dados["veiculo"]["placa"], 3, 4);
    }
    
	$stm1 = $conn->prepare($sql1);
    $stm1->bindParam(":placa", $placa);
    $stm1->execute();
    $checklistBanco1 = $stm1->fetchAll();
    //Verificar se um checklist ja existe----------------------------------------------------------------------

    if(count($checklistBanco1) > 0){
        $erro = \Apoio\Erro::geraErro("Checklist", "Já existem um Checklist para este veículo em andamento");
        array_push($erros, $erro);
    }
    
    //Verificar se um checklist ja existe e retornar o id e status do mesmo--------------------
    $sql = "SELECT 
    ckl.id, 
    ckl.checklist_status
    FROM kronaone.check_lists ckl
    WHERE ckl.operacao_id = :operacao_id AND ckl.checklist_status NOT IN ('CHECK-LIST APROVADO', 'CHECK-LIST CANCELADO', 'CHECK-LIST REPROVADO')
    ORDER BY ckl.id DESC
    LIMIT 6";

    $stm = $conn->prepare($sql);
    $idOperacao = $usuario[0]["id"];
    $stm->bindParam(":operacao_id", $idOperacao);
    $stm->execute();
    $checklistBanco = $stm->fetchAll();
    //Verificar se um checklist ja existe e retornar o id e status do mesmo--------------------

    
    
    if(count($checklistBanco) > $usuario[0]["checklists_simultaneos"]){
        $erro = \Apoio\Erro::geraErro("Checklist", "Ja existem 5 checklist's em andamento não são permitidos mais");
        array_push($erros, $erro);
    }
    
    if(count($erros) > 0){
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        if($tipoRequisicao == "application/json"){
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
        //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
    }else{
        if($tipoRequisicao == "application/json"){
            $r1 = isset($dadosReb1) == TRUE ? $dadosReb1["Resposta"]["idVeiculo"] : "";
            $r2 = isset($dadosReb2) == TRUE ? $dadosReb2["Resposta"]["idVeiculo"] : "";
            $r3 = isset($dadosReb3) == TRUE ? $dadosReb3["Resposta"]["idVeiculo"] : "";
            
            
            $jsonChecklist = "{\"veiculo_id\":\"{$dadosVei["Resposta"]["idVeiculo"]}\"
            ,\"reboque1_id\":\"{$r1}\"
            ,\"reboque2_id\":\"{$r2}\"
            ,\"reboque3_id\":\"{$r3}\"
            ,\"operacao_id\":\"{$usuario[0]["id"]}\"
            ,\"transportador_id\":\"{$dadosEmp["Resposta"]["idEmpresa"]}\"
            ,\"motorista_id\":\"{$dadosMot["Resposta"]["idMotorista"]}\"
            ,\"tipo_operacao\":\"{$dados["tipo_operacao"]}\"
            ,\"cond_abastecimento\":\"{$dados["cond_abastecimento"]}\"
            ,\"observacao\":\"{$dados["observacao"]}\"
            }";
            
            $ckl = new Checklist\Checklist();
            $resCkl = $ckl->requisitaInterno($dadosHeader, $jsonChecklist);
            try{
                $dadosCkl = json_decode($resCkl, true);
            } catch (Exception $ex) {
                $erro = Apoio\Erro::geraErro("Checklist", "Formato de JSON inválidos");
            }
            
            if(isset($dadosCkl["resposta"][0]["erro"])){
                foreach ($dadosCkl["resposta"] as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["erro"]["objeto"], $Ierro["erro"]["mensagem"]);
                    array_push($erros, $erro);
                }
            }
            
            if(count($erros) > 0){
				$erros = Apoio\Erro::saidaErrosJson($erros);
				finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
                return $erros;
            }else{
                //$idChecklist = $ckl->cadastraChecklist($usuario);
                $json['Resposta']['mensagem'] = $dadosCkl["Resposta"]["mensagem"];
                $json['Resposta']['idChecklist'] = $dadosCkl["Resposta"]["idChecklist"];
				finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
                return json_encode($json, JSON_UNESCAPED_UNICODE);
            }
        }else{
            
            $r1 = isset($dadosReb1) == TRUE ? $dadosReb1["idVeiculo"] : "";
            $r2 = isset($dadosReb2) == TRUE ? $dadosReb2["idVeiculo"] : "";
            $r3 = isset($dadosReb3) == TRUE ? $dadosReb3["idVeiculo"] : "";
            
            $xmlChecklist = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <checklist>
                <veiculo_id>{$dadosVei["idVeiculo"]}</veiculo_id>
                <reboque1_id>{$r1}</reboque1_id>
                <reboque2_id>{$r2}</reboque2_id>
                <reboque3_id>{$r3}</reboque3_id>
                <operacao_id>{$usuario[0]["id"]}</operacao_id>
                <transportador_id>{$dadosEmp["idEmpresa"]}</transportador_id>
                <motorista_id>{$dadosMot["idMotorista"]}</motorista_id>
                <tipo_operacao>{$dados["tipo_operacao"]}</tipo_operacao>
                <cond_abastecimento>{$dados["cond_abastecimento"]}</cond_abastecimento>
                <observacao>{$dados["observacao"]}</observacao>
            </checklist>";
                
            $ckl = new Checklist\Checklist();
            $resCkl = $ckl->requisitaInterno($dadosHeader, $xmlChecklist);
            try{
                $xmlCkl = simplexml_load_string($resCkl);
            } catch (Exception $ex) {
                $erro = Apoio\Erro::geraErro("Checklist", "Formato de XML invalido");
            }
            $json = json_encode($xmlCkl);
            $dadosCkl = json_decode($json,TRUE);
            
            if(isset($dadosCkl["erro"])){
                foreach ($dadosCkl as $Ierro){
                    $erro = \Apoio\Erro::geraErro($Ierro["Objeto"], $Ierro["Mensagem"]);
                    array_push($erros, $erro);
                }
            }
            
            if(count($erros) > 0){
				$erros = Apoio\Erro::saidaErrosXml($erros);
				finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
                return erros;
            }else{
                $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
                $xml->addChild("mensagem", "Checklist Cadastrado com sucesso");
                $xml->addChild("idChecklist", $dadosCkl["idChecklist"]);
				finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
                return $xml->asXML();
            }
        }
    }
    
    
});

$app->post('/ConsultaChecklist', function(\Slim\Http\Request $req){
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    $erros = array();
    $dadosHeader = $req->getHeaders();
    $tipoRequisicao = $req->getContentType();
    $dadosBrutos = $req->getBody();
    $tipo = explode(",", $tipoRequisicao);
    //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
    
    $tipoRequisicao = $tipo[0];
    
    //Verifica de usuario e senha são validos-------------------------------------------------------------
	$conn = \Apoio\Conexoes::conectar170();
	$exec_id = inicializa($conn, $dadosHeader, $tempo_execucao);
	if($message = validaLogin($dadosHeader, $usuario, $conn)){
		finaliza($conn, $tempo_execucao, $exec_id, $message, $dadosBrutos);
        return $message;
    }	
    //Verifica de usuario e senha são validos----------------------------------
    
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    if($tipoRequisicao == "application/json"){
        try{
            $dados = json_decode($dadosBrutos, true);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
        }    
    }else if($tipoRequisicao == "application/xml"){
        try{
            $xml = simplexml_load_string($dadosBrutos);
        } catch (Exception $ex) {
            $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
        }
        $json = json_encode($xml);
        $dados = json_decode($json,TRUE);
    }else{
        $erro = Apoio\Erro::geraErro("Veiculo", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
        array_push($erros, $erro);
		finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
        return var_dump($erros);
    }
    //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----
    
    if(!isset($dados["placa"])){
        $erro = \Apoio\Erro::geraErro("Checklist", "Placa é obrigatória");
        array_push($erros, $erro);
    }
    if(isset($dados["placa"]) && (strlen($dados["placa"]) < 6 || strlen($dados["placa"]) > 7)){
        $erro = \Apoio\Erro::geraErro("Checklist", "Formato de placa incorreto");
        array_push($erros, $erro);
    }
    switch (strlen($dados["placa"])){
        case 6:
            if(ctype_alpha(substr($dados["placa"], 0, 3)) == FALSE || ctype_digit(substr($dados["placa"], 3, 3)) == FALSE){
                $erro = \Apoio\Erro::geraErro("Veiculo", "Fromato de placa invalido, somente são permitidos AAA0000 ou AAA000");
            }
            break;
        case 7:
            if(ctype_alpha(substr($dados["placa"], 0, 3)) == FALSE || ctype_digit(substr($dados["placa"], 3, 4)) == FALSE){
                $erro = \Apoio\Erro::geraErro("Veiculo", "Fromato de placa invalido, somente são permitidos AAA0000 ou AAA000");
            }
            break;
    }
    if(count($erros) > 0){
        if($tipoRequisicao == "application/json"){
			$erros = Apoio\Erro::saidaErrosJson($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }else if($tipoRequisicao == "application/xml"){
			$erros = Apoio\Erro::saidaErrosXml($erros);
			finaliza($conn, $tempo_execucao, $exec_id, $erros, $dados);
            return $erros;
        }
    }else{
        if(strlen($dados["placa"]) == 6){
            $placa = substr($dados["placa"], 0, 3)."-".substr($dados["placa"], 3, 3);
        }else{
            $placa = substr($dados["placa"], 0, 3)."-".substr($dados["placa"], 3, 4);
        }
        // BUSCA CHECK-LIST SISTEMICO
        $sistemico = $conn->query("SELECT
                                ckl.id,
                                ckl.criacao_datahora,
                                ckl.checklist_status,
                                ckl.tipo_checklist,
                                vei.placa as principal,
                                reb1.placa as reboque1,
                                reb2.placa as reboque2,
                                reb3.placa as reboque3,
                                (ADDDATE(ckl.criacao_datahora,INTERVAL ope.checklist_validade DAY)) as vigencia
                                FROM check_lists ckl
                                JOIN veiculos vei ON vei.id = ckl.veiculo_id
                                LEFT JOIN veiculos reb1 ON reb1.id = ckl.reboque1_id
                                LEFT JOIN veiculos reb2 ON reb2.id = ckl.reboque2_id
                                LEFT JOIN veiculos reb3 ON reb3.id = ckl.reboque3_id
                                JOIN operacoes ope ON ope.id = ckl.operacao_id
                                WHERE
                                ckl.tipo_checklist = 'SISTEMICO'
                                AND
                                vei.placa = '{$placa}' ORDER BY ckl.id DESC LIMIT 1")->fetch();
        
        // BUSCA CHECK-LIST VEICULAR
        $veicular = $conn->query("SELECT
                                ckl.id,
                                ckl.criacao_datahora,
                                ckl.checklist_status,
                                ckl.tipo_checklist,
                                vei.placa as principal,
                                reb1.placa as reboque1,
                                reb2.placa as reboque2,
                                reb3.placa as reboque3,
                                (ADDDATE(ckl.criacao_datahora,INTERVAL ope.checklist_validade DAY)) as vigencia
                                FROM check_lists ckl
                                JOIN veiculos vei ON vei.id = ckl.veiculo_id
                                LEFT JOIN veiculos reb1 ON reb1.id = ckl.reboque1_id
                                LEFT JOIN veiculos reb2 ON reb2.id = ckl.reboque2_id
                                LEFT JOIN veiculos reb3 ON reb3.id = ckl.reboque3_id
                                JOIN operacoes ope ON ope.id = ckl.operacao_id
                                WHERE
                                ckl.tipo_checklist = 'VEICULAR'
                                AND
                                vei.placa = '{$placa}' ORDER BY ckl.id DESC LIMIT 1")->fetch();

        if(is_null($sistemico) || is_null($veicular)){
            if($tipoRequisicao == "application/json"){
                $json['Resposta']['mensagem'] = "Checklist não encontrado";
				finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
                return json_encode($json);
            } else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
                $xml->addChild("mensagem", "Checklist não encontrado");
				finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
                return $xml->asXML();
            }
        } else {
            if($tipoRequisicao == "application/json"){
                // RETORNA CHECK-LIST VEICULAR
                if(!empty($sistemico)){
                    $json['Resposta']['sistemico']['idChecklist'] = $sistemico["id"];
                    $json['Resposta']['sistemico']['status'] = $sistemico["checklist_status"];
                    $json['Resposta']['sistemico']['tipoChecklist'] = $sistemico["tipo_checklist"];
                    $json['Resposta']['sistemico']['dataCriacao'] = $sistemico["criacao_datahora"];
                    $json['Resposta']['sistemico']['placaVeiculo'] = $sistemico["principal"];
                    $json['Resposta']['sistemico']['placaReboque1'] = ($sistemico["reboque1"] != null ? $sistemico["reboque1"] : null);
                    $json['Resposta']['sistemico']['placaReboque2'] = ($sistemico["reboque2"] != null ? $sistemic["reboque2"] : null);
                    $json['Resposta']['sistemico']['placaReboque3'] = ($sistemico["reboque3"] != null ? $sistemico["reboque3"] : null);
                    $json['Resposta']['sistemico']['dataVigencia'] = $sistemico["vigencia"];
                } else {
                    $json['Resposta']['sistemico']['Erro'] = "Check-list não encontrado";
                }

                // RETORNA CHECK-LIST VEICULAR
                if(!empty($veicular)){
                    $json['Resposta']['veicular']['idChecklist'] = $veicular["id"];
                    $json['Resposta']['veicular']['status'] = $veicular["checklist_status"];
                    $json['Resposta']['veicular']['tipoChecklist'] = $veicular["tipo_checklist"];
                    $json['Resposta']['veicular']['dataCriacao'] = $veicular["criacao_datahora"];
                    $json['Resposta']['veicular']['placaVeiculo'] = $veicular["principal"];
                    $json['Resposta']['veicular']['placaReboque1'] = ($veicular["reboque1"] != null ? $veicular["reboque1"] : null);
                    $json['Resposta']['veicular']['placaReboque2'] = ($veicular["reboque2"] != null ? $veicular["reboque2"] : null);
                    $json['Resposta']['veicular']['placaReboque3'] = ($veicular["reboque3"] != null ? $veicular["reboque3"] : null);
                    $json['Resposta']['veicular']['dataVigencia'] = $veicular["vigencia"];
                } else {
                    $json['Resposta']['veicular']['Erro'] = "Check-list não encontrado";
                }
				finaliza($conn, $tempo_execucao, $exec_id, $json, $dados);
                return json_encode($json);
            } else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
                // RETORNA CHECK-LIST SISTEMICO
                if(!empty($sistemico)){
                    $xml_sistemico = $xml->addChild('sistemico');
                    $xml_sistemico->addChild("idChecklist", $sistemico["id"]);
                    $xml_sistemico->addChild("status", $sistemico["checklist_status"]);
                    $xml_sistemico->addChild("tipoChecklist", $sistemico["tipo_checklist"]);
                    $xml_sistemico->addChild("dataCriacao", $sistemico["criacao_datahora"]);
                    $xml_sistemico->addChild("placaVeiculo", $sistemico["principal"]);
                    $xml_sistemico->addChild("placaReboque1", ($sistemico["reboque1"] != null ? $sistemico["reboque1"] : null));
                    $xml_sistemico->addChild("placaReboque2", ($sistemico["reboque2"] != null ? $sistemico["reboque2"] : null));
                    $xml_sistemico->addChild("placaReboque3", ($sistemico["reboque3"] != null ? $sistemico["reboque3"] : null));
                    $xml_sistemico->addChild("dataVigencia", $sistemico["vigencia"]);
                } else {
                    $xml->addChild("sistemico", "Check-list não encontrado");
                }
                // RETORNA CHECK-LIST VEICULAR
                if(!empty($veicular)){
                    $xml_veicular = $xml->addChild('veicular');
                    $xml_veicular->addChild("idChecklist", $veicular["id"]);
                    $xml_veicular->addChild("status", $veicular["checklist_status"]);
                    $xml_veicular->addChild("tipoChecklist", $veicular["tipo_checklist"]);
                    $xml_veicular->addChild("dataCriacao", $veicular["criacao_datahora"]);
                    $xml_veicular->addChild("placaVeiculo", $veicular["principal"]);
                    $xml_veicular->addChild("placaReboque1", ($veicular["reboque1"] != null ? $veicular["reboque1"] : null));
                    $xml_veicular->addChild("placaReboque2", ($veicular["reboque2"] != null ? $veicular["reboque2"] : null));
                    $xml_veicular->addChild("placaReboque3", ($veicular["reboque3"] != null ? $veicular["reboque3"] : null));
                    $xml_veicular->addChild("dataVigencia", $veicular["vigencia"]);
                } else {
                    $xml->addChild("veicular", "Check-list não encontrado");
                }
				finaliza($conn, $tempo_execucao, $exec_id, $xml->asXML(), $dados);
                return $xml->asXML();
            }
        }
    }
    
    
});

$app->get('/teste', function(\Slim\Http\Request $req){
    //return "ok";
    
    $cabecalho = array(
					"Content-Type: application/json", //'Content-type: application/x-www-form-urlencoded\r\n'.
					"usuario: 007 - OPERAÇÃO TI ( I )",
					"senha: 123456");
		//echo json_encode($cabecalho);
	
	$corpo = '{"nome":"Fulano da Silva"
		,"cpf":"30499856804"
		,"rg":"245878963X"
		,"orgao_emissor":"SSP/SP"
		,"data_nascimento":"13/11/1982"
		,"nome_mae":"Fulaninha da Silva"
		,"estado_civil":"Casado"
		,"escolaridade":"2º Grau"
		,"cnh_numero":"25458965236"
		,"cnh_categoria":"D"
		,"cnh_vencimento":"23/12/2019"
		,"end_rua":"Rua do limoeiros"
		,"end_numero":"125"
		,"end_complemento":"Casa 4"
		,"end_bairro":"Vila Madalena"
		,"end_cidade":"São Paulo"
		,"end_uf":"SP"
		,"end_cep":"08220830"
		,"tel_fixo":"1123562456"
		,"tel_celular":"11956895623"
		,"nextel":"12*01254"
		,"mopp":"10/05/2018"
		,"aso":"10/05/2018"
		,"cdd":"12/05/2018"
		,"capacitacao":"12/09/2018"
		,"vinculo":"Agregado"
		,"empresa_id":"232"}';

		$url = 'http://grupokrona.dyndns.org/KronaApi/CadastroMotorista';
		$data = array('Content-Type' => 'application/json', 'usuario' => 'value2', '007 - OPERAÇÃO TI ( I )' => '123456');

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'=> $cabecalho,
				'method'  => 'POST',
				'content' => $corpo //http_build_query($corpo)
			)
		);
		
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ 
			$erro = new Erro("Requisitor", "fazerRequisicao", "Erro ao fazer requisicao no web service", date("Y-m-d H:i:s"));
			$erro->registrarErro(); 
		}

		return $result;

	// $curl = curl_init("http://grupokrona.dyndns.org/KronaApi/CadastroMotorista");
	// curl_setopt($curl, CURLOPT_HTTPHEADER, $cabecalho);
	// curl_setopt($curl, CURLOPT_HEADER, TRUE);
	// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	// curl_setopt($curl, CURLOPT_POSTFIELDS, corpo);
	// curl_setopt($curl, CURLOPT_POST, TRUE);
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	// $result	= curl_exec($curl);
	// $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	// echo "1";
	// echo $result; //json_encode(curl_getinfo($curl));
	// echo "2";
	// if ($status != 200) {
		// $erro = new Erro("Requisitor", "fazerRequisicao", "Erro ao fazer requisicao no web service", date("Y-m-d H:i:s"));
		// $erro->registrarErro(); 
		// return 1;
	// }
		
	// curl_close($curl);
	// return $result;
});



$app->run();


