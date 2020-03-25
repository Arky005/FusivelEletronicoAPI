<?php
    date_default_timezone_set('America/Sao_Paulo');
    include "../../phps/omdb.php";

    function enviarNotificacao($titulo, $mensagem, $id) {
        
        $chave = "...";
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        //cria o conteudo da notificacao
        $conteudo = array (
                'notification' =>
                    array('title'=>$titulo, 'text'=>$mensagem),
                'to' => $id,
                'priority' => "high"
                );
        //formata para json
        $conteudo = json_encode ( $conteudo );
    
        //headers do post
        $headers = array (
                'Authorization: key=' . $chave,
                'Content-Type: application/json'
        );
    
        //cria sessao clienturl
        $ch = curl_init ();
        
        //seta opcoes da sessao
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $conteudo );
    
        //executa o post na api do firebase
        $result = curl_exec ( $ch );
        
        //mostra o resultado
        echo $result;
        
        //fecha a conexao
        curl_close ( $ch );
    }
    
    $valordetectado=( isset($_GET['valor']) && 
        is_numeric($_GET['valor']) ? $_GET['valor'] : 0 );
    $conStr = "mysql:host=" . $host. ";dbname=" . $db . 
        ";charset=utf8mb4";
    $conexao = new PDO($conStr, $dbuser, $dbpass);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$sql = "INSERT INTO 
	    `APPFUSIVEL` (`id`, `dia`, `local`, `valor`)
	    VALUES (NULL, '" . date("Y-m-d H:i:s") ."', 
	    'Local de demonstração', 
	    '$valordetectado');";
    
    if($conexao->exec($sql)){
    //envia notificacao para o celular
        enviarNotificacao("Alerta!","Novo alerta detectado!",
            "/topics/all");
    } else {
        echo 'erro';
    }
    
    echo '<br>Valor detectado:'.$valordetectado;
?>