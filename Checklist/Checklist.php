<?php

namespace Checklist;
/**
 * Description of Checklist
 *
 * @author Anderson
 * @since 27/11/2017
 */
class Checklist {
    private $id = null; 
    private $veiculo_id = null; 
    private $reboque1_id = null; 
    private $reboque2_id = null; 
    private $reboque3_id = null; 
    private $aprovado = null; 
    private $operacao_id = null; 
    private $transportador_id = null; 
    private $motorista_id = null; 
    private $cod_liberacao = null; 
    private $tipo_operacao = null; 
    private $cond_abastecimento = null; 
    private $checklist_status = null; 
    private $criacao_datahora = null; 
    private $criacao_usuario = null; 
    private $dt_checklist_inicio = null; 
    private $dt_checklist_fim = null; 
    private $dt_checklist_total = null; 
    private $acesso_ip = null; 
    private $observacao = null; 
    private $cancelamento_motivo = null; 
    private $alteracao_datahora = null; 
    private $dt_checklist_tempo = null; 
    
    private $veiculos_frente = array("AUTOMÓVEL", "CAVALO MECÂNICO", "TOCO", "TRUCK", "UTILITÁRIO LEVE", "VAN", "VUC");
    private $veiculos_carreta = array("CARRETA ABERTA", "CARRETA BASCULANTE", "CARRETA BAU", "CARRETA BAU FRIGORIFICA", "CARRETA BITREM", "CARRETA ESPECIAL", "CARRETA GRANELEIRA", "CARRETA SIDER", "CARRETA SILO", "CARRETA TANQUE", "CARRETA TRITREM", "CONTAINER");


    public function getId() {
        return $this->id;
    }

    public function getVeiculo_id() {
        return $this->veiculo_id;
    }

    public function getReboque1_id() {
        return $this->reboque1_id;
    }

    public function getReboque2_id() {
        return $this->reboque2_id;
    }

    public function getReboque3_id() {
        return $this->reboque3_id;
    }

    public function getAprovado() {
        return $this->aprovado;
    }

    public function getOperacao_id() {
        return $this->operacao_id;
    }

    public function getTransportador_id() {
        return $this->transportador_id;
    }

    public function getMotorista_id() {
        return $this->motorista_id;
    }

    public function getCod_liberacao() {
        return $this->cod_liberacao;
    }

    public function getTipo_operacao() {
        return $this->tipo_operacao;
    }

    public function getCond_abastecimento() {
        return $this->cond_abastecimento;
    }

    public function getChecklist_status() {
        return $this->checklist_status;
    }

    public function getCriacao_datahora() {
        return $this->criacao_datahora;
    }

    public function getCriacao_usuario() {
        return $this->criacao_usuario;
    }

    public function getDt_checklist_inicio() {
        return $this->dt_checklist_inicio;
    }

    public function getDt_checklist_fim() {
        return $this->dt_checklist_fim;
    }

    public function getDt_checklist_total() {
        return $this->dt_checklist_total;
    }

    public function getAcesso_ip() {
        return $this->acesso_ip;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function getCancelamento_motivo() {
        return $this->cancelamento_motivo;
    }

    public function getAlteracao_datahora() {
        return $this->alteracao_datahora;
    }

    public function getDt_checklist_tempo() {
        return $this->dt_checklist_tempo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setVeiculo_id($veiculo_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        
        
        if($veiculo_id == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "O id do veículo é obrigatório");
            array_push($erros, $erro);
        }
        else if(is_numeric($veiculo_id) == FALSE){
            $erro = \Apoio\Erro::geraErro("Checklist", "Somente é aceito números no id de veiculo");
            array_push($erros, $erro);
        }
        
        $veiculo_exite = $conn->query("select vei.id, vei.tipo from kronaone.veiculos vei where vei.id = '{$veiculo_id}'")->fetchAll();
        if(count($veiculo_exite) < 1){
            $erro = \Apoio\Erro::geraErro("Checklist", "O ID deste veículo não existe na base de dados");
            array_push($erros, $erro);
        }else{
            if(in_array($veiculo_exite[0]["tipo"], $this->veiculos_carreta)){
                $erro = \Apoio\Erro::geraErro("Checklist", "Este campo não pode aceitar o tipo 'carreta'");
                array_push($erros, $erro);
            }
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->veiculo_id = $veiculo_id;
            return $erros;
        }
    }

    public function setReboque1_id($reboque1_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        if($reboque1_id != ""){
            $veiculo_exite = $conn->query("select vei.id, vei.tipo from kronaone.veiculos vei where vei.id = '{$reboque1_id}'")->fetchAll();
            $veiculo_frente = $conn->query("select vei.id, vei.tipo from kronaone.veiculos vei where vei.id = '".$this->getVeiculo_id()."'")->fetchAll();
            if(count($veiculo_exite) < 1){
                $erro = \Apoio\Erro::geraErro("Checklist", "O ID deste reboque 1 não existe na base de dados");
                array_push($erros, $erro);
            }else{
                if($this->getVeiculo_id() == "" || $veiculo_frente[0]["tipo"] != 'CAVALO MECÂNICO'){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Não é permitido adicionar Reboque 1 sem o veículo principal ou se o veículo principal não for do tipo 'CAVALO MECÂNICO'");
                    array_push($erros, $erro);
                }
                if(in_array($veiculo_exite[0]["tipo"], $this->veiculos_frente)){
                    $erro = \Apoio\Erro::geraErro("Checklist", "O campo reboque 1 só pode ser adicionado carretas e containers");
                    array_push($erros, $erro);
                }
                if(is_numeric($reboque1_id) == FALSE){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Somente são aceitos números no reboque1");
                    array_push($erros, $erro);
                }
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->reboque1_id = $reboque1_id;
            return $erros;
        }
    }

    public function setReboque2_id($reboque2_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        if($reboque2_id != ""){
            $veiculo_exite = $conn->query("select vei.id, vei.tipo from kronaone.veiculos vei where vei.id = '{$reboque2_id}'")->fetchAll();
            if(count($veiculo_exite) < 1){
                $erro = \Apoio\Erro::geraErro("Checklist", "O ID deste reboque 2 não existe na base de dados");
                array_push($erros, $erro);
            }else{
                if(($this->getVeiculo_id() == "" || $this->getReboque1_id() == "")){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Não é permitido adicionar Reboque 2 sem o veículo principal e reboque 1");
                    array_push($erros, $erro);
                }
                if(in_array($veiculo_exite[0]["tipo"], $this->veiculos_frente)){
                    $erro = \Apoio\Erro::geraErro("Checklist", "O campo reboque 2 só pode ser adicionado carretas e containers");
                    array_push($erros, $erro);
                }
                if(is_numeric($reboque2_id) == FALSE){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Somente são aceitos números no reboque2");
                    array_push($erros, $erro);
                }
            }
        }
        
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->reboque2_id = $reboque2_id;
            return $erros;
        }
    }

    public function setReboque3_id($reboque3_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        if($reboque3_id != ""){
            $veiculo_exite = $conn->query("select vei.id, vei.tipo from kronaone.veiculos vei where vei.id = '{$reboque3_id}'")->fetchAll();
            if(count($veiculo_exite) < 1){
                $erro = \Apoio\Erro::geraErro("Checklist", "O ID deste reboque 3 não existe na base de dados");
                array_push($erros, $erro);
            }else{
                if($this->getVeiculo_id() == "" || $this->getReboque1_id() == "" || $this->getReboque2_id() == ""){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Não é permitido adicionar Reboque 3 sem o veículo principal, reboque 1 e reboque 2");
                    array_push($erros, $erro);
                }
                if(in_array($veiculo_exite[0]["tipo"], $this->veiculos_frente)){
                    $erro = \Apoio\Erro::geraErro("Checklist", "O campo reboque 3 só pode ser adicionado carretas e containers");
                    array_push($erros, $erro);
                }
                if(is_numeric($reboque3_id) == FALSE){
                    $erro = \Apoio\Erro::geraErro("Checklist", "Somente são aceitos números no reboque3");
                    array_push($erros, $erro);
                }
            }
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->reboque3_id = $reboque3_id;
            return $erros;
        }
    }

    public function setOperacao_id($operacao_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        $operacao_exite = $conn->query("select ope.id from kronaone.operacoes ope where ope.id = '{$operacao_id}'")->rowCount();
        if($operacao_exite < 1){
            $erro = \Apoio\Erro::geraErro("Checklist", "O ID desta operação não existe na base de dados");
            array_push($erros, $erro);
        }
        if($operacao_id == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "Número da operação é obrigatório");
            array_push($erros, $erro);
        }
        if(is_numeric($operacao_id) == FALSE){
            $erro = \Apoio\Erro::geraErro("Checklist", "Somente serão aceitos número para operações");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->operacao_id = $operacao_id;
            return $erros;
        }
    }

    public function setTransportador_id($transportador_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        $operacao_exite = $conn->query("select emp.id from kronaone.empresas emp where emp.id = '{$transportador_id}'")->rowCount();
        if($operacao_exite < 1){
            $erro = \Apoio\Erro::geraErro("Checklist", "O ID desta empresa não existe na base de dados");
            array_push($erros, $erro);
        }
        if($transportador_id == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "O id do transportador é obrigatório");
            array_push($erros, $erro);
        }
        if(is_numeric($transportador_id) == FALSE){
            $erro = \Apoio\Erro::geraErro("Checklist", "Somente serão aceitos números pata o id de transportador");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->transportador_id = $transportador_id;
            return $erros;
        }
    }

    public function setMotorista_id($motorista_id) {
        $erros = array();
        $conn = \Apoio\Conexoes::conectar170();
        $operacao_exite = $conn->query("select mot.id from kronaone.motoristas mot where mot.id = '{$motorista_id}'")->rowCount();
        if($operacao_exite < 1){
            $erro = \Apoio\Erro::geraErro("Checklist", "O ID deste motorista não existe na base de dados");
            array_push($erros, $erro);
        }
        if($motorista_id == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "O id do motorista é obrigatório");
            array_push($erros, $erro);
        }
        if(is_numeric($motorista_id) == FALSE){
            $erro = \Apoio\Erro::geraErro("Checklist", "Somente serão aceitos números pata o id de motorista");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->motorista_id = $motorista_id;
            return $erros;
        }
    }

    public function setTipo_operacao($tipo_operacao) {
        $erros = array();
        $tipos = array();
        $conn = \Apoio\Conexoes::conectar170();
        $tipoBruto = $conn->query("select * from kronaone.viagens_tipos_viagem")->fetchAll();
        foreach ($tipoBruto as $t){
            array_push($tipos, $t["descricao"]);
        }
        
        if($tipo_operacao == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "O tipo de operação é obrigatório");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($tipo_operacao, mb_internal_encoding()), $tipos) == FALSE){
            $erro = \Apoio\Erro::geraErro("Checklist", "Este tipo não consta em nossa lista de tipos, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tipo_operacao = $tipo_operacao;
            return $erros;
        }
    }

    public function setCond_abastecimento($cond_abastecimento) {
        $erros = array();
        if($cond_abastecimento == ""){
            $erro = \Apoio\Erro::geraErro("Checklist", "A informação de abastecimento é obrigatória");
            array_push($erros, $erro);
        }
        if(mb_strtoupper($cond_abastecimento, mb_internal_encoding()) != "ABASTECIDO" && mb_strtoupper($cond_abastecimento, mb_internal_encoding()) != "NÃO ABASTECIDO"){
            $erro = \Apoio\Erro::geraErro("Checklist", "Somente é aceito as palavras 'ABASTECIDO' ou 'NÃO ABASTECIDO'");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->cond_abastecimento = mb_strtoupper($cond_abastecimento, mb_internal_encoding());
            return $erros;
        }
    }
    
    public function setChecklist_status($checklist_status){
        $erros = array();
        $this->checklist_status = $checklist_status;
        return $erros;
    }

    public function setCriacao_datahora($criacao_datahora) {
        $erros = array();
        $this->criacao_datahora = $criacao_datahora;
        return $erros;
    }

    public function setCriacao_usuario($criacao_usuario) {
        $erros = array();
        $this->criacao_usuario = $criacao_usuario;
        return $erros;
    }

    public function setAcesso_ip($acesso_ip) {
        $erros = array();
        $this->acesso_ip = $acesso_ip;
        return $erros;
    }

    public function setObservacao($observacao) {
        $erros = array();
        $this->observacao = $observacao;
        return $erros;
    }

    
    
    public function cadastraChecklist($usuario){
        $conn = \Apoio\Conexoes::conectar170();
        
        $sql = "insert into kronaone.check_lists set 
        veiculo_id = :veiculo_id, 
        reboque1_id = :reboque1_id, 
        reboque2_id = :reboque2_id, 
        reboque3_id = :reboque3_id, 
        operacao_id = :operacao_id, 
        transportador_id = :transportador_id, 
        motorista_id = :motorista_id, 
        tipo_operacao = :tipo_operacao, 
        cond_abastecimento = :cond_abastecimento, 
        checklist_status = :checklist_status, 
        criacao_datahora = :criacao_datahora, 
        criacao_usuario = :criacao_usuario, 
        acesso_ip = :acesso_ip, 
        observacao = :observacao";

        $stm2 = $conn->prepare($sql);

        $veiculo_id = $this->getVeiculo_id(); 
        $reboque1_id = $this->getReboque1_id(); 
        $reboque2_id = $this->getReboque2_id(); 
        $reboque3_id = $this->getReboque3_id(); 
        $operacao_id = $this->getOperacao_id(); 
        $transportador_id = $this->getTransportador_id(); 
        $motorista_id = $this->getMotorista_id(); 
        $tipo_operacao = $this->getTipo_operacao(); 
        $cond_abastecimento = $this->getCond_abastecimento(); 
        $checklist_status = "AG. CHECK-LIST"; 
        $criacao_datahora = date("Y-m-d H:i:s"); 
        $criacao_usuario = $usuario; 
        $acesso_ip = $_SERVER['REMOTE_ADDR']; 
        $observacao = $this->getObservacao();

        $stm2->bindParam(":veiculo_id", $veiculo_id);
        $stm2->bindParam(":reboque1_id", $reboque1_id);
        $stm2->bindParam(":reboque2_id", $reboque2_id);
        $stm2->bindParam(":reboque3_id", $reboque3_id);
        $stm2->bindParam(":operacao_id", $operacao_id);
        $stm2->bindParam(":transportador_id", $transportador_id);
        $stm2->bindParam(":motorista_id", $motorista_id);
        $stm2->bindParam(":tipo_operacao", $tipo_operacao);
        $stm2->bindParam(":cond_abastecimento", $cond_abastecimento);
        $stm2->bindParam(":checklist_status", $checklist_status);
        $stm2->bindParam(":criacao_datahora", $criacao_datahora);
        $stm2->bindParam(":criacao_usuario", $criacao_usuario);
        $stm2->bindParam(":acesso_ip", $acesso_ip);
        $stm2->bindParam(":observacao", $observacao);

        if($stm2->execute()){
            $id_cheklist = $conn->lastInsertId();
            return $id_cheklist;
        }else{
            return False;
        }
        
    }
    
    public function requisitaInterno($header, $dados2){
        //declara variavel erros e recupera o tipo de requisição e os dados da mesmo (JSON ou XML)----
        $erros = array();
        $tipoRequisicao = $header["CONTENT_TYPE"][0];
        $dadosBrutos = $dados2;
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
            return var_dump($erros);
        }
        //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----

        //Seta-se todos o campo ja validando pelo pelo objeto checklist um array de erros-------------------
        $checklist = new Checklist();
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
                return \Apoio\Erro::saidaErrosJson($erros);
            }else if($tipoRequisicao == "application/xml"){
                return \Apoio\Erro::saidaErrosXml($erros);
            }
            //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        }else{
            $idChecklist = $checklist->cadastraChecklist($usu);
            if($tipoRequisicao == "application/json"){
                $json['Resposta']['mensagem'] = "Checklist Cadastrado com sucesso";
                $json['Resposta']['idChecklist'] = $idChecklist;
                return json_encode($json);
            }else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
                $xml->addChild("mensagem", "Checklist Cadastrado com sucesso");
                $xml->addChild("idChecklist", $idChecklist);
                return $xml->asXML();
            }
        }
    }
}
