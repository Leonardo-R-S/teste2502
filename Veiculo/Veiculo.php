<?php
namespace Veiculo;

/**
 * Description of Veiculo
 *
 * @author Anderson
 * @since 22/11/2017
 */
class Veiculo {
    private $id = null;
    private $placa = null;
    private $renavam = null;
    private $marca = null;
    private $modelo = null;
    private $cor = null;
    private $ano = null;
    private $tipo = null;
    private $capacidade = null;
    private $numero_antt = null;
    private $validade_antt = null;
    private $proprietario = null;
    private $proprietario_cpfcnpj = null;
    private $end_rua = null;
    private $end_numero = null;
    private $end_complemento = null;
    private $end_bairro = null;
    private $end_cidade = null;
    private $end_uf = null;
    private $end_cep = null;
    private $tecnologia = null;
    private $id_rastreador = null;
    private $comunicacao = null;
    private $tecnologia_sec = null;
    private $id_rastreador_sec = null;
    private $comunicacao_sec = null;
    private $criacao_datahora = null;
    private $criacao_usuario = null;
    private $fixo = null;
    
    
    public function getId() {
        return $this->id;
    }

    public function getPlaca() {
        return $this->placa;
    }

    public function getRenavam() {
        return $this->renavam;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function getCor() {
        return $this->cor;
    }

    public function getAno() {
        return $this->ano;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getCapacidade() {
        return $this->capacidade;
    }

    public function getNumero_antt() {
        return $this->numero_antt;
    }

    public function getValidade_antt() {
        return $this->validade_antt;
    }

    public function getProprietario() {
        return $this->proprietario;
    }

    public function getProprietario_cpfcnpj() {
        return $this->proprietario_cpfcnpj;
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

    public function getTecnologia() {
        return $this->tecnologia;
    }

    public function getId_rastreador() {
        return $this->id_rastreador;
    }

    public function getComunicacao() {
        return $this->comunicacao;
    }

    public function getTecnologia_sec() {
        return $this->tecnologia_sec;
    }

    public function getId_rastreador_sec() {
        return $this->id_rastreador_sec;
    }

    public function getComunicacao_sec() {
        return $this->comunicacao_sec;
    }

    public function getCriacao_datahora() {
        return $this->criacao_datahora;
    }

    public function getCriacao_usuario() {
        return $this->criacao_usuario;
    }

    public function getFixo() {
        return $this->fixo;
    }

    public function setPlaca($placa) {
        $erros = array();
        if($placa == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A placa do veículo é obrigatória");
            array_push($erros, $erro);
        }
        if(strlen($placa) > 7 || strlen($placa) < 6 || strpos($placa, "-") != FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A placa do veículo deve conter 6 ou 7 caracteres e não deve ser usado traço");
            array_push($erros, $erro);
        }
        switch (strlen($placa)){
            case 6:
                if(ctype_alpha(substr($placa, 0, 3)) == FALSE || ctype_digit(substr($placa, 3, 3)) == FALSE){
                    $erro = \Apoio\Erro::geraErro("Veiculo", "Formato de placa invalido, somente são permitidos AAA0000 ou AAA000");
                }
                break;
            case 7:
                if(ctype_alpha(substr($placa, 0, 3)) == FALSE || ctype_digit(substr($placa, 3, 4)) == FALSE){
                    $erro = \Apoio\Erro::geraErro("Veiculo", "Formato de placa invalido, somente são permitidos AAA0000 ou AAA000");
                }
                break;
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            switch (strlen($placa)){
                case 6:
                    $placaFormatada = substr($placa, 0, 3)."-".substr($placa, 3, 3);
                    break;
                case 7:
                    $placaFormatada = substr($placa, 0, 3)."-".substr($placa, 3, 4);
                    break;
            }
            $this->placa = mb_strtoupper($placaFormatada, mb_internal_encoding());
            return $erros;
        }
    }

    public function setRenavam($renavam) {
        $erros= array();
        if($renavam == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Número do RENAVAM é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($renavam) > 11 || strlen($renavam) < 11 || is_numeric($renavam) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O RENAVAM deve conter apenas 11 caracteres e deve ser somente números");
            array_push($erros, $erro);
        }
        if(\Apoio\Helpers::validaRenavam($renavam) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "RENAVAM invalido");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->renavam = $renavam;
            return $erros;
        }
    }

    public function setMarca($marca) {
        $erros = array();
        $marcas = array();
        $conn = \Apoio\Conexoes::conectar170();
        $marcasBruto = $conn->query("select * from kronaone.veiculos_marcas")->fetchAll();
        foreach ($marcasBruto as $m){
            array_push($marcas, $m["descricao"]);
        }
        
        if($marca == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A marca é obrigatória");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($marca, mb_internal_encoding()), $marcas) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Esta marca não consta em nossa lista de marcas, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->marca = mb_strtoupper($marca, mb_internal_encoding());
            return $erros;
        }
    }

    public function setModelo($modelo) {
        $erros = array();
        if($modelo == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O modelo é obrigatório");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->modelo = mb_strtoupper($modelo, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCor($cor) {
        $erros = array();
        $cores = array();
        $conn = \Apoio\Conexoes::conectar170();
        $corBruto = $conn->query("select * from kronaone.veiculos_cores")->fetchAll();
        foreach ($corBruto as $c){
            array_push($cores, $c["descricao"]);
        }
        if($cor == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A cor é obrigatória");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($cor, mb_internal_encoding()), $cores) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Esta cor não consta em nossa lista de cores, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->cor = mb_strtoupper($cor, mb_internal_encoding());
            return $erros;
        }
    }

    public function setAno($ano) {
        $erros = array();
        if($ano == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O ano é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($ano) > 4 || strlen($ano) < 4 || is_numeric($ano) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O ano deve conter 4 dígitos e deve ser somente números");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->ano = $ano;
            return $erros;
        }
        
    }

    public function setTipo($tipo) {
        $erros = array();
        $tipos = array();
        $conn = \Apoio\Conexoes::conectar170();
        $tipoBruto = $conn->query("select * from kronaone.veiculos_tipos")->fetchAll();
        foreach ($tipoBruto as $t){
            array_push($tipos, $t["descricao"]);
        }
        if($tipo == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O tipo é obrigatório");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($tipo, mb_internal_encoding()), $tipos) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Este tipo não consta em nossa lista de tipos, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tipo = mb_strtoupper($tipo, mb_internal_encoding());
            return $erros;
        }
    }

    public function setCapacidade($capacidade) {
        $erros = array();
        $this->capacidade = $capacidade;
        return $erros;
    }

    public function setNumero_antt($numero_antt) {
        $erros = array();
        $this->numero_antt = $numero_antt;
        return $erros;
    }

    public function setValidade_antt($validade_antt) {
        $erros = array();
        if($validade_antt != ""){
            if(strlen($validade_antt) > 10 || strlen($validade_antt) < 10 || strpos($validade_antt, "/") === FALSE || strpos($validade_antt, "/") !== 2){
                $erro = \Apoio\Erro::geraErro("Veiculo", "O formato de data de validade da ANTT deve seguir o modelo DD/MM/AAAA");
                array_push($erros, $erro);
            }
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $data = implode("-", array_reverse(explode("/", $validade_antt)));
            $this->validade_antt = $data;
            return $erros;
        }
    }

    public function setProprietario($proprietario) {
        $erros = array();
        if($proprietario == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O nome do proprietário é obrigatório");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->proprietario = mb_strtoupper($proprietario, mb_internal_encoding());
            return $erros;
        }
    }

    public function setProprietario_cpfcnpj($proprietario_cpfcnpj) {
        $erros = array();
        if($proprietario_cpfcnpj == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "CPF ou CNPJ do proprietário é obrigatório");
            array_push($erros, $erro);
        }
        
		if((strlen($proprietario_cpfcnpj) != 11 && strlen($proprietario_cpfcnpj) != 14) || is_numeric($proprietario_cpfcnpj) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A quantidade de caracteres é invalida, somente será aceito 11 caracteres para CPF e 14 caracteres para CNPJ e somente números");
            array_push($erros, $erro);
        }
        
		if(strlen($proprietario_cpfcnpj) == 11){
            if(\Apoio\Helpers::validaCPF($proprietario_cpfcnpj) == FALSE){
                $erro = \Apoio\Erro::geraErro("Veiculo", "CPF invalido");
                array_push($erros, $erro);
            }
        }
		
        if(strlen($proprietario_cpfcnpj) == 14){
            if(\Apoio\Helpers::valida_cnpj($proprietario_cpfcnpj) == FALSE){
                $erro = \Apoio\Erro::geraErro("Veiculo", "CNPJ invalido");
                array_push($erros, $erro);
            }
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            if(strlen($proprietario_cpfcnpj) == 11){
                $cpfFormatado = substr($proprietario_cpfcnpj, 0, 3).".".substr($proprietario_cpfcnpj, 3, 3).".".substr($proprietario_cpfcnpj, 6, 3)."-".substr($proprietario_cpfcnpj, 9, 2);
                $this->proprietario_cpfcnpj = $cpfFormatado;
                return $erros;
            }
            if(strlen($proprietario_cpfcnpj) == 14){
                $cnpjformatado = "0".substr($proprietario_cpfcnpj, 0, 2).".".substr($proprietario_cpfcnpj, 2, 3).".".substr($proprietario_cpfcnpj, 5,3)."/".substr($proprietario_cpfcnpj, 8, 4)."-".substr($proprietario_cpfcnpj, 12, 2);
                $this->proprietario_cpfcnpj = $cnpjformatado;
                return $erros;
            }
        }
    }

    public function setEnd_rua($end_rua) {
        $erros = array();
        if($end_rua == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Rua do Proprietário é obrigatória");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->end_rua = mb_strtoupper($end_rua. mb_internal_encoding());
            return $erros;
        }
    }

    public function setEnd_numero($end_numero) {
        $erros = array();
        if($end_numero == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O número do endereço do proprietário é obrigatório");
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
            $erro = \Apoio\Erro::geraErro("Veiculo", "O bairro do proprietario é obrigatório");
            array_push($erros, $erro);
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
            $erro = \Apoio\Erro::geraErro("Veiculo", "A cidade do proprietário é obrigatória");
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
            $erro = \Apoio\Erro::geraErro("Veiculo", "A UF é obrigatória");
            array_push($erros, $erro);
        }
        if(strlen($end_uf) > 2 || strlen($end_uf) < 2){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Unidades federativas só serão aceitas com dois caracteres");
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
            $erro = \Apoio\Erro::geraErro("Veiculo", "O CEP é obrigatório");
            array_push($erros, $erro);
        }
        if(strlen($end_cep)< 8 || strlen($end_cep)>8){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Quantidade de caracteres inválidos, CEP possui 8 caracteres");
            array_push($erros, $erro);
        }
        if(!is_numeric($end_cep)){
            $erro = \Apoio\Erro::geraErro("Veiculo", "No CEP não são permitidos outros caracteres além de números");
            array_push($erros, $erro);
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $cepFormatado = substr($end_cep, 0, 5)."-".substr($end_cep, 4, 3);
            $this->end_cep = mb_strtoupper($cepFormatado, mb_internal_encoding());
            return $erros;
        }
    }

    public function setTecnologia($tecnologia) {
        $erros = array();
        $tec = array();
        $conn = \Apoio\Conexoes::conectar170();
        $tecBruto = $conn->query("select * from kronaone.tecnologias")->fetchAll();
        foreach ($tecBruto as $t){
            array_push($tec, $t["descricao"]);
        }
        if($tecnologia == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "A tecnologia é obrigatória");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($tecnologia, mb_internal_encoding()), $tec) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Esta tecnologia não consta em nossa lista de tecnologias, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tecnologia = mb_strtoupper($tecnologia, mb_internal_encoding());
            return $erros;
        }
    }

    public function setId_rastreador($id_rastreador) {
        $erros = array();
        if($this->getTecnologia() != "NÃO TEM" && $id_rastreador == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Para o tipo de Tecnologia escolhida o número do rastreador é obrigatório");
            array_push($erros, $erro);
        }
        if($this->getTecnologia() != "NÃO TEM" && $id_rastreador == "0"){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Para o tipo de Tecnologia escolhida o número do rastreador é obrigatório, não será aceito zero");
        }
        if($this->getTecnologia() != "NÃO TEM"){
            $conn = \Apoio\Conexoes::conectar170();
            $sql = "select vei.id, vei.placa from kronaone.veiculos vei
            where
            (vei.tecnologia = '". strtoupper($this->getTecnologia())."' and vei.id_rastreador = '{$id_rastreador}')
            or
            (vei.tecnologia_sec = '". strtoupper($this->getTecnologia())."'  and vei.id_rastreador_sec = '{$id_rastreador}')";
		
            $veiculo = $conn->query($sql)->fetchAll();
            if(count($veiculo) > 0 && $veiculo[0]["placa"] != $this->getPlaca()){
                $erro = \Apoio\Erro::geraErro("Veiculo", "Este rastreador já existe cadastrado em outro veículo no sistema");
                array_push($erros, $erro);
            }
        }
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->id_rastreador = $id_rastreador;
            return $erros;
        }
    }

    public function setComunicacao($comunicacao) {
        $erros = array();
        $com = array();
        $conn = \Apoio\Conexoes::conectar170();
        $comBruto = $conn->query("select * from kronaone.tecnologias_comunicacoes")->fetchAll();
        foreach ($comBruto as $c){
            array_push($com, $c["descricao"]);
        }
        if($comunicacao == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O tipo de comunicação é obrigatório");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($comunicacao, mb_internal_encoding()), $com) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Este tipo de comunicação não consta em nossa lista de comunicações, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->comunicacao = mb_strtoupper($comunicacao, mb_internal_encoding());
            return $erros;
        }
    }

    public function setTecnologia_sec($tecnologia_sec) {
        $erros = array();
        $tec = array();
        $conn = \Apoio\Conexoes::conectar170();
        $tecBruto = $conn->query("select * from kronaone.tecnologias")->fetchAll();
        foreach ($tecBruto as $t){
            array_push($tec, $t["descricao"]);
        }
        if($tecnologia_sec == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Tecnologia secundaria é obrigatório");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($tecnologia_sec, mb_internal_encoding()), $tec) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Esta tecnologia secundaria não consta em nossa lista de tecnologias, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->tecnologia_sec = mb_strtoupper($tecnologia_sec, mb_internal_encoding());
            return $erros;
        }
    }

    public function setId_rastreador_sec($id_rastreador_sec) {
        $erros = array();
        if($this->getTecnologia_sec() != "NÃO TEM" && $id_rastreador_sec == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Para o tipo de Tecnologia secundaria escolhida o número do rastreador é obrigatório");
            array_push($erros, $erro);
        }
        if($this->getTecnologia_sec() != "NÃO TEM" && $id_rastreador_sec == "0"){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Para o tipo de Tecnologia secundaria escolhida o número do rastreador é obrigatório, não será aceito zero");
        }
        if($this->getTecnologia_sec() != "NÃO TEM"){
            $conn = \Apoio\Conexoes::conectar170();
            $sql = "select vei.id, vei.placa from kronaone.veiculos vei
            where
            (vei.tecnologia = '". strtoupper($this->getTecnologia_sec())."' and vei.id_rastreador = '{$id_rastreador_sec}')
            or
            (vei.tecnologia_sec = '". strtoupper($this->getTecnologia_sec())."'  and vei.id_rastreador_sec = '{$id_rastreador_sec}')";
            $veiculo = $conn->query($sql)->fetchAll();
            if(count($veiculo) > 0 && $veiculo[0]["placa"] != $this->getPlaca()) {
                $erro = \Apoio\Erro::geraErro("Veiculo", "Este rastreador secundário já existe cadastrado em outro veículo no sistema");
                array_push($erros, $erro);
            }
        }
        
        
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->id_rastreador_sec = $id_rastreador_sec;
            return $erros;
        }
    }

    public function setComunicacao_sec($comunicacao_sec) {
        $erros = array();
        $com = array();
        $conn = \Apoio\Conexoes::conectar170();
        $comBruto = $conn->query("select * from kronaone.tecnologias_comunicacoes")->fetchAll();
        foreach ($comBruto as $c){
            array_push($com, $c["descricao"]);
        }
        if($comunicacao_sec == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "O tipo de comunicação é obrigatório");
            array_push($erros, $erro);
        }
        if(in_array(mb_strtoupper($comunicacao_sec, mb_internal_encoding()), $com) == FALSE){
            $erro = \Apoio\Erro::geraErro("Veiculo", "Este tipo de comunicação secundaria não consta em nossa lista de comunicações, verifique a lista no manual da KronaApi");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->comunicacao_sec = mb_strtoupper($comunicacao_sec, mb_internal_encoding());
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

    public function setFixo($fixo) {
        $erros = array();
        if($fixo == ""){
            $erro = \Apoio\Erro::geraErro("Veiculo", "É obrigatório informa se este veículo é fixo ou não");
            array_push($erros, $erro);
        }
        if($fixo != "SIM" && $fixo != "NÃO"){
            $erro = \Apoio\Erro::geraErro("Veiculos", "Para esta informação será aceito somente 'SIM' ou 'NÃO'");
            array_push($erros, $erro);
        }
        if(count($erros) > 0){
            return $erros;
        }else{
            $this->fixo = mb_strtoupper($fixo, mb_internal_encoding());
            return $erros;
        }
    }

    
    
    public function cadastrarVeiculo($usuario){
        $conn = \Apoio\Conexoes::conectar170();
        //Verificar se o veiculo ja existe e retornar o id do mesmo--------------------
        $sql = "select vei.id, vei.criacao_usuario, vei.criacao_datahora from kronaone.veiculos vei where vei.placa = :placa";
        $stm = $conn->prepare($sql);
        $placa = $this->getPlaca();
        $stm->bindParam(":placa", $placa);
        $stm->execute();
        $veiculoBanco = $stm->fetchAll();
        //Verificar se o veiculo ja existe e retornar o id do mesmo--------------------
        if(count($veiculoBanco) > 0 && !$usuario[0]['ws_atualiza_veiculo']) {
			return $veiculoBanco[0]["id"];			
		}	
		
		$sql = (count($veiculoBanco) > 0 ? "update" : "insert into")." 
			kronaone.veiculos set
            veiculos.placa = :placa,
            veiculos.renavan = :renavam,
            veiculos.marca = :marca,
            veiculos.modelo = :modelo,
            veiculos.cor = :cor,
            veiculos.ano = :ano,
            veiculos.tipo = :tipo,
            veiculos.capacidade = :capacidade,
            veiculos.numero_antt = :numero_antt,
            veiculos.validade_antt = :validade_antt,
            veiculos.proprietario = :proprietario,
            veiculos.proprietario_cpfcnpj = :proprietario_cpfcnpj,
            veiculos.end_rua = :end_rua,
            veiculos.end_numero = :end_numero,
            veiculos.end_complemento = :end_complemento,
            veiculos.end_bairro = :end_bairro,
            veiculos.end_cidade = :end_cidade,
            veiculos.end_uf = :end_uf,
            veiculos.end_cep = :end_cep,
            veiculos.tecnologia = :tecnologia,
            veiculos.id_rastreador = :id_rastreador,
            veiculos.comunicacao = :comunicacao,
            veiculos.tecnologia_sec = :tecnologia_sec,
            veiculos.id_rastreador_sec = :id_rastreador_sec,
            veiculos.comunicacao_sec = :comunicacao_sec,
            veiculos.fixo = :fixo,
			".(count($veiculoBanco) > 0 ? 
				
			"veiculos.alteracao_datahora = :alteracao_datahora,
			veiculos.alteracao_usuario = :alteracao_usuario
			where veiculos.id=:id" : 
				
			"veiculos.criacao_datahora = :criacao_datahora,
			veiculos.criacao_usuario = :criacao_usuario
			
			");
			
		$stm2 = $conn->prepare($sql);
		
		$placa = $this->getPlaca();
		$renavam = $this->getRenavam();
		$marca = $this->getMarca();
		$modelo = $this->getModelo();
		$cor = $this->getCor();
		$ano = $this->getAno();
		$tipo = $this->getTipo();
		$capacidade = $this->getCapacidade();
		$numero_antt = $this->getNumero_antt();
		$validade_antt = $this->getValidade_antt();
		$proprietario = $this->getProprietario();
		$proprietario_cpfcnpj = $this->getProprietario_cpfcnpj();
		$end_rua = $this->getEnd_rua();
		$end_numero = $this->getEnd_numero();
		$end_complemento = $this->getEnd_complemento();
		$end_bairro = $this->getEnd_bairro();
		$end_cidade = $this->getEnd_cidade();
		$end_uf = $this->getEnd_uf();
		$end_cep = $this->getEnd_cep();
		$tecnologia = $this->getTecnologia();
		$id_rastreador = $this->getId_rastreador();
		$comunicacao = $this->getComunicacao();
		$tecnologia_sec = $this->getTecnologia_sec();
		$id_rastreador_sec = $this->getId_rastreador_sec();
		$comunicacao_sec = $this->getComunicacao_sec();
		$criacao_datahora = date("Y-m-d H:i:s");
		$criacao_usuario = $usuario[0]['descricao'];
		$fixo = $this->getFixo();
		
		$stm2->bindParam(":placa" , $placa);
		$stm2->bindParam(":renavam" , $renavam);
		$stm2->bindParam(":marca" , $marca);
		$stm2->bindParam(":modelo" , $modelo);
		$stm2->bindParam(":cor" , $cor);
		$stm2->bindParam(":ano" , $ano);
		$stm2->bindParam(":tipo" , $tipo);
		$stm2->bindParam(":capacidade" , $capacidade);
		$stm2->bindParam(":numero_antt" , $numero_antt);
		$stm2->bindParam(":validade_antt" , $validade_antt);
		$stm2->bindParam(":proprietario" , $proprietario);
		$stm2->bindParam(":proprietario_cpfcnpj" , $proprietario_cpfcnpj);
		$stm2->bindParam(":end_rua" , $end_rua);
		$stm2->bindParam(":end_numero" , $end_numero);
		$stm2->bindParam(":end_complemento" , $end_complemento);
		$stm2->bindParam(":end_bairro" , $end_bairro);
		$stm2->bindParam(":end_cidade" , $end_cidade);
		$stm2->bindParam(":end_uf" , $end_uf);
		$stm2->bindParam(":end_cep" , $end_cep);
		$stm2->bindParam(":tecnologia" , $tecnologia);
		$stm2->bindParam(":id_rastreador" , $id_rastreador);
		$stm2->bindParam(":comunicacao" , $comunicacao);
		$stm2->bindParam(":tecnologia_sec" , $tecnologia_sec);
		$stm2->bindParam(":id_rastreador_sec" , $id_rastreador_sec);
		$stm2->bindParam(":comunicacao_sec" , $comunicacao_sec);
		$stm2->bindParam(":fixo" , $fixo);
			
		if(count($veiculoBanco) > 0){
			$stm2->bindParam(":alteracao_datahora" , $criacao_datahora);
			$stm2->bindParam(":alteracao_usuario" , $criacao_usuario);
			$stm2->bindParam(":id", $veiculoBanco[0]["id"]);
		}
		else {
			$stm2->bindParam(":criacao_datahora" , $criacao_datahora);
			$stm2->bindParam(":criacao_usuario" , $criacao_usuario);
		}
		
		if($stm2->execute()){
			$id_veiculo = count($veiculoBanco) > 0 ? $veiculoBanco[0]["id"] : $conn->lastInsertId();
			return $id_veiculo;
		}else{
			return False;
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
//                $erro = Apoio\Erro::geraErro("Veiculo", "Formato de json invalidos");
//            }    
//        }else if($tipoRequisicao == "application/xml"){
//            try{
//                $xml = simplexml_load_string($dadosBrutos);
//            } catch (Exception $ex) {
//                $erro = Apoio\Erro::geraErro("Veiculo", "Formato de XML invalido");
//            }
//            $json = json_encode($xml);
//            $dados = json_decode($json,TRUE);
//        }else{
//            $erro = Apoio\Erro::geraErro("Veiculo", "Tipo de requisicao invalida, apenas são aceitos 'application/json' ou 'application/xml'");
//            array_push($erros, $erro);
//            return var_dump($erros);
//        }
        //de acordo com o tipo de requisicao ajustase e monta-se o array de dados para proceguir copm o processo----


        //Seta-se todos o campo ja validando pelo pelo objeto veiculo um array de erros-------------------
        $veiculo = new Veiculo();
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
                return \Apoio\Erro::saidaErrosJson($erros);
            }else if($tipoRequisicao == "application/xml"){
                return \Apoio\Erro::saidaErrosXml($erros);
            }
            //Havendo erros retorna os dados no mesmo formato (JSON ou XML) que foi solicitado------
        }else{
            $idVeiculo = $veiculo->cadastrarVeiculo($usu);
            if($tipoRequisicao == "application/json"){
                $json['Resposta']['mensagem'] = "Veiculo Cadastrado com sucesso";
                $json['Resposta']['idVeiculo'] = $idVeiculo;
                return json_encode($json);
            }else if($tipoRequisicao == "application/xml"){
                $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
                $xml->addChild("mensagem", "Veiculo Cadastrado com sucesso");
                $xml->addChild("idVeiculo", $idVeiculo);
                return $xml->asXML();
            }
        }
    }
}
