<?php
    require_once "../../phps/omdb.php";
    //importa biblioteca do phplot
    require_once 'phplot.php';
    
    $conStr = "mysql:host=" . $host. ";dbname=" . $db;
    $conexao = new PDO($conStr, $dbuser, $dbpass);
	$sql = "SELECT * FROM APPFUSIVEL ORDER BY dia ASC";
	
	$quantidade=[];
	foreach ($conexao->query($sql) as $row) {
        $mes = (int)date("m",strtotime($row['dia']));
        $ano = (int)date("Y",strtotime($row['dia']));
        if(date("Y") == $ano){
            //armazena as quantidades de cada mes
            $quantidade[$mes]++;
        }
    }
   
    
    //cria a imagem de resolucao 800x1300
    $plot = new PHPlot(800, 1300);
    
    //altera fontes
    $plot->SetFontTTF('title', 'Aller.ttf', 40);
    $plot->SetFontTTF('y_label', 'Aller.ttf', 25);
    $plot->SetFontTTF('x_label', 'Aller.ttf', 20);
    $plot->SetFontTTF('x_title', 'Aller.ttf', 25);
    $plot->SetFontTTF('y_title', 'Aller.ttf', 25);
    
    //muda titulo do grafico para ano atual
    $plot->setTitle(date("Y"));
    
    //mostra somente numeros inteiros
    $plot->SetYTickIncrement(1);
    
    //muda labels
    $plot->SetXLabel('mês');
    $plot->SetYLabel('alertas');
    
    //tipo do grafico
    $plot->SetPlotType("bars");
    
    //nao mostra o grafico quando criado
    $plot->SetPrintImage(false);

    $dados = [];
    for($i=1; $i<=12; $i++){
        //cria o array de informacoes de cada mes
        array_push($dados, array($i, $quantidade[$i]) );
    }
    
    //seta os dados do grafico
    $plot->SetDataValues($dados);
    
    //cria o grafico
    $plot->DrawGraph();
    
    //exibe em pagina html formatada
    echo "<img style='width:100%;height:100%;object-fit:contain;'
        src=\"" . $plot->EncodeImage() . "\">";
?>
