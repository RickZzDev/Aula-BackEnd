<?php
//verifica se existe a variavel modo
    if(isset($_GET['modo'])){
        //valida se a variavel modo tem a ação de excluir
        if($_GET['modo']=='excluir'){
           
            //importa o arquivo de conexao
            require_once('conexao.php');
            //Abre a conexao com o BD
            $conexao = conexaoMysql();
            
            $codigo = $_GET['codigo'];
            $sql = "delete from tblcontatos
                    where codigo =".$codigo;
            
            if(mysqli_query($conexao, $sql))
                header('location:../cadastro.php');
            else
                echo("Erro ao deletar o registro");
        }
    }


?>