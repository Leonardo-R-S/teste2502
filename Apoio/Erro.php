<?php
namespace Apoio;

/**
 * classe que padroniza retoro de erros.
 *
 * @author Anderson.Moreira
 * @since 15/11/2017
 */
class Erro {
    private $objeto = null;
    private $mensagem = null;
    
    public function getObjeto() {
        return $this->objeto;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    public static function geraErro($objeto, $mensagem){
        $erro = new \Apoio\Erro();
        $erro->objeto = $objeto;
        $erro->mensagem = $mensagem;
        return $erro;
    }
    
    public static function saidaErrosJson($erros){
        $json['resposta'] = array();
        foreach ($erros as $erro){
            $jsonErro['erro']['objeto'] = $erro->getObjeto();
            $jsonErro['erro']['mensagem'] = $erro->getMensagem();
            array_push($json['resposta'], $jsonErro);
        }
        return json_encode($json, JSON_UNESCAPED_UNICODE);
    }
    
    public static function saidaErrosXml($erros){
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><resposta/>");
        foreach ($erros as $erro){
            $noErro = $xml->addChild("erro");
            $noErro->addChild("Objeto", $erro->getObjeto());
            $noErro->addChild("Mensagem", $erro->getMensagem());
        }
        return $xml->asXML();
    }
}
