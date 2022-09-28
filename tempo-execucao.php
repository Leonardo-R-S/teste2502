<?php
//session_start();		

$tempo_execucao = new tempo_execucao();
 
class tempo_execucao {
	
	
	function __construct($pdo=null){
		$this->pdo = $pdo;
	}
	public function gravar() {
	
		//require 'controllers/pdo.php';
		
		$num_args = func_num_args(); 
		
		if ($num_args == 1 && is_array(func_get_arg(0))) {
			$parametros = func_get_arg(0);
		
			$file = $parametros['file'];		
			if (!$file) {
				$parametros['server'] = $_SERVER['REQUEST_URI'];
				if (empty($parametros['server'])) {
					$parametros['server'] = $_SERVER['PHP_SELF'];
				}
				$parametros['file'] = str_replace('.php', '', basename($_SERVER['PHP_SELF']));
			}
			
			//echo $_SERVER['REMOTE_ADDR'];
			$usuario_sessao = $_SESSION['usuario'];
			$usuario = $parametros['usuario'];
			if (!empty($usuario) && !empty($usuario_sessao)){ 
					$parametros['usuario'] = $usuario.'|'.$usuario_sessao;		
			}
			$parametros['usuario'] = $parametros['usuario'].'|'.$_SERVER['REMOTE_ADDR'];
			
			$sta = $this->pdo->prepare("call sp_tempo_execucao_rotinas2(:id, :nome, :url, :tipo, :observacao, :usuario, :quantidade, null)");
			
			$sta = $this->pdo->prepare("call sp_tempo_execucao_rotinas2(:id, :nome, :url, :tipo, :observacao, :usuario, :quantidade, null)");
			$param_id = $this->getData($parametros, 'id', '');
			$param_nome = $this->getData($parametros, 'file', '');
			$param_url = $this->getData($parametros, 'server', '');
			$param_tipo = $this->getData($parametros, 'tipo', '');
			$param_observacao = $this->getData($parametros, 'observacao', '');
			$param_usuario = $this->getData($parametros, 'usuario', '');
			$param_quantidade = $this->getData($parametros, 'quantidade', '');
			
			$sta->bindParam(":id", $param_id);
			$sta->bindParam(":nome", $param_nome);
			$sta->bindParam(":url", $param_url); 
			$sta->bindParam(":tipo", $param_tipo);
			$sta->bindParam(":observacao", $param_observacao);
			$sta->bindParam(":usuario", $param_usuario);
			$sta->bindParam(":quantidade", $param_quantidade);
			
			$sta->execute();
			$return = $sta->fetch();
			//try {
			//	$pdo->exec('KILL CONNECTION_ID()');
			//} catch(Exception $ex) { }
			///unset($pdo);
			//$pdo = null;
			return $return[0];
		}
		// ajustar para sobrecarga acima e remover parte abaixo
		
		
		
		if ($num_args == 1) {
			$id = func_get_arg(0);
		}
		
		if ($num_args == 3 && is_numeric(func_get_arg(0))) {
			$id = func_get_arg(0);
			$tipo = func_get_arg(1);
			$observacao = func_get_arg(2);
		}
		
		$quantidade = 0;
		//$datahora_inicio = null; 
		
		$arg1 = func_get_arg(0);
		$arg2 = null;
		if ($num_args > 1) {
			$arg2 = func_get_arg(1);
		}
		
		// os parametros default é o que vem na declaração do metodo int, string e string($id, $tipo, $observacao)
		// abaixo as sobrecargas
		
		// somente um parametro string (tipo)
		if ($num_args=1 && !is_numeric($arg1) && !is_array($arg1)) {
			$tipo = $arg1;
			$id = 0; 
		// dois parametros integer (id e quantidade registros processados)
		} else if($num_args=2 && is_numeric($arg1) && is_numeric($arg2)) {
			$id = $arg1; 
			$quantidade = $arg2;
		// um parametros array(bidimensional)(id/datahora)
		} else if($num_args=1 && is_array($arg1)) {
			$id = $arg1[0]; 
			$datahora_inicio = $arg1[1];
		// dois parametros, um array(bidimensional) e um integer (id/datahora e quantidade registros processados)
		} else if($num_args=2 && is_array($arg1) && is_numeric($arg2)) {
			$id = $arg1[0]; 
			$datahora_inicio = $arg[1];
			$quantidade = $arg2;
		} else if($num_args=2) {
			$tipo = $arg1;
			$file = $arg2;
			$server = $file;
			$id = 0; 
		}
		
		if (!$file) {
			$server = $_SERVER['REQUEST_URI'];
			if (empty($server)) {
				$server = $_SERVER['PHP_SELF'];
			}
			$file = str_replace('.php', '', basename($_SERVER['PHP_SELF']));
		}
		//echo $_SERVER['REMOTE_ADDR'];
		$usuario = $_SESSION['usuario'];
		if (empty($usuario)) {
			$usuario = $_SERVER['REMOTE_ADDR'];
		}
		
		$sta = $pdo->prepare("call sp_tempo_execucao_rotinas2(:id, :nome, :url, :tipo, :observacao, :usuario, :quantidade, :datahora_inicio)");
		$sta->bindParam(":id", $id);
		$sta->bindParam(":nome", $file);
		$sta->bindParam(":url", $server); 
		$sta->bindParam(":observacao", $observacao);
		$sta->bindParam(":usuario", $usuario);
		$sta->bindParam(":tipo", $tipo);
		$sta->bindParam(":quantidade", $quantidade);
		$sta->bindParam(":datahora_inicio", $datahora_inicio);
		$sta->execute();
		$return = $sta->fetch();
		try {
			$pdo->exec('KILL CONNECTION_ID()');
		} catch(Exception $ex) { }
		$pdo = null;
		return $return;  
	}
	
	function getData($value, $key, $return = null) {
		if (!isset($value)) {
			return $return;
		} else if (!isset($value[$key])) {
			return $return;
		}
		else {
			return $value[$key];
		}
	}
}

?>