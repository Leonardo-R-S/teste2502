<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Apoio;

/**
 * classe com funções estaticas para uso rapido no codigo
 *
 * @author Dinho
 * @since 20/11/2017
 */
class Helpers {
    public static function isMail($email){
        $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
        if (preg_match($er, $email)){
            return true;
        } else {
            return false;
        }
    }
    
    public static function validaCPF($cpf) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }
        
        // Elimina possivel mascara
        //$cpf = ereg_replace('[^0-9]', '', $cpf);
        //$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {   

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }
    
    public static function valida_cnpj($cnpj){
        // Deixa o CNPJ com apenas números
        $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );

        // Garante que o CNPJ é uma string
        $cnpj = (string)$cnpj;

        // O valor original
        $cnpj_original = $cnpj;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );

        /**
         * Multiplicação do CNPJ
         *
         * @param string $cnpj Os digitos do CNPJ
         * @param int $posicoes A posição que vai iniciar a regressão
         * @return int O
         *
        if ( ! function_exists('multiplica_cnpj') ) {
        }
         */

        // Faz o primeiro cálculo
        $primeiro_calculo = Helpers::multiplica_cnpj( $primeiros_numeros_cnpj );

        // Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
        // Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
        $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );

        // Concatena o primeiro dígito nos 12 primeiros números do CNPJ
        // Agora temos 13 números aqui
        $primeiros_numeros_cnpj .= $primeiro_digito;

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = Helpers::multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
        $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $primeiros_numeros_cnpj . $segundo_digito;

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ( $cnpj === $cnpj_original ) {
            return true;
        }
    }

	static function multiplica_cnpj( $cnpj, $posicao = 5 ) {
		// Variável para o cálculo
		$calculo = 0;

		// Laço para percorrer os item do cnpj
		for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
			// Cálculo mais posição do CNPJ * a posição
			$calculo = $calculo + ( $cnpj[$i] * $posicao );

			// Decrementa a posição a cada volta do laço
			$posicao--;

			// Se a posição for menor que 2, ela se torna 9
			if ( $posicao < 2 ) {
				$posicao = 9;
			}
		}
		// Retorna o cálculo
		return $calculo;
	}
    
    public static function validaRenavam ($renavam) {
        $soma = 0;
        // Cria array com as posições da string
        $d = str_split($renavam);
        $x = str_split("3298765432");
        $digito = 0;

        // Calcula os 4 primeiros digitos do renavam fazendo o calculo da primeira posição do array * 5 e vai diminuindo até chegar a 2
        for ($i=0; $i<10; $i++) { 
                $soma += $d[$i] * $x[$i];
        }

        // Faz o calculo de 11
        $soma = $soma * 10;
        $valor = $soma % 11;

        // Busca digito verificador
        if ($valor == 11 || $valor == 0 || $valor >= 10) {	
                $digito = 0;
        } else {
                $digito = $valor;
        }

        // Verifica digito com a 5 posição do array
        if ($digito == $d[10]) {
                return TRUE;
        } else {
                return FALSE;
        }
    }
    
    public static function requisitor($urlRequisicao, $cabecalho, $corpo){
        $curl = curl_init($urlRequisicao);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $cabecalho);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $corpo);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result	= curl_exec($curl);
	
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            $erro = new Erro("Requisitor", "fazerRequisicao", "Erro ao fazer requisicao no web service", date("Y-m-d H:i:s"));
            $erro->registrarErro();
            return;
	}
		
	curl_close($curl);
        return json_decode($result, true);
    }
}
