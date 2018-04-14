<?php
// Retornar em json
header('Content-Type: application/json');

// Verifica funcao
if(isset($_GET['f']) && $_GET['f'] != ""){
	$f = $_GET['f'];
}else{
	echo '[{"name":"ERRO","value":"400"}]';
	exit;
}
// Função 1 é Novo Pedido
if(f == "1"){
		// Verifica usuario
		if(isset($_GET['geoPosicionamento']) && $_GET['geoPosicionamento'] != ""){
			$geoPosicionamento = explode(',',$_GET['geoPosicionamento']);
		}else{
			echo '[{"name":"ERRO","value":"400"}]';
			exit;
		}
		//Variáveis de utilização para calcular distancia entre motoqueiros
		$idPedido=0;
		$menorPosicao;
		// Sql de requisição
		$sql = "SELECT id,positionPedido FROM `pedidos` where bloqueiaPedido = 0 ";
		$result = $conn->query($sql);
		if($result->num_rows > '0'){
			while ($row = $result->fetch_row()){
					$final = explode(',',$row[1]);
					$fazerTeste = calcDistancia($geoPosicionamento[0], $geoPosicionamento[1], $final[0] , $final[1]);
					if($idPedido == 0){
						$menorPosicao = $fazerTeste;
						$idPedido = $row[0];
					}else{
						if($fazerTeste < $menorPosicao){
							$menorPosicao = $fazerTeste;
							$idPedido = $row[0];
						}
					}
			}
			$sql1 = "UPDATE `pedidos` SET `bloqueiaPedido` = '1' WHERE `pedidos`.`id` =".$idPedido;
			if($conn->query($sql1) === TRUE){
				echo '[{"name":"SUCESSO","value":"'.$idPedido.'"}]';
			}else{echo '[{"name":"ERRO","value":"402"}]';}
		}else{
			echo '[{"name":"ERRO","value":"401"}]';
			exit;
		}
}else if(f == "2")/* */{
	// Verifica usuario
	if(isset($_GET['id']) && $_GET['id'] != ""){
		$idPedido = $_GET['id'];
	}else{
		echo '[{"name":"ERRO","value":"400"}]';
		exit;
	}
	// Sql para modificar no banco de dados
	$sql1 = "UPDATE `pedidos` SET `bloqueiaPedido` = '0' WHERE `pedidos`.`id` =".$idPedido;
	if($conn->query($sql1) === TRUE){
		echo '[{"name":"SUCESSO","value":"0"}]';
	}else{
		echo '[{"name":"ERRO","value":"403"}]';
	}
	
}
function calcDistancia($lat_inicial, $long_inicial, $lat_final, $long_final)	{
    $d2r = 0.017453292519943295769236;

    $dlong = ($long_final - $long_inicial) * $d2r;
    $dlat = ($lat_final - $lat_inicial) * $d2r;

    $temp_sin = sin($dlat/2.0);
    $temp_cos = cos($lat_inicial * $d2r);
    $temp_sin2 = sin($dlong/2.0);

    $a = ($temp_sin * $temp_sin) + ($temp_cos * $temp_cos) * ($temp_sin2 * $temp_sin2);
    $c = 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));

    return 6368.1 * $c;
}

$conn->close();
?>
