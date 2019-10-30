<?php 
        
        require_once('bd/conexao.php');

        $conexao = conexaoMySql();
//verifica se existe a variavel modo
    if(isset($_POST['modo']))
    {
        //valida se o conteudo da variavle modo é visualizar
        if(strtoupper( $_POST['modo']) == 'VISUALIZAR'){
            //recebe o id do registro enviado pelo ajax
                $codigo = $_POST['codigo'];

                $sql = "select  * from tblcontatos
                            where codigo = ".$codigo;
                //executa o script no bacno de dados
                $select = mysqli_query($conexao,$sql);
                //Verifica se existem dados e converte em um array
                if($rsVisualizar = mysqli_fetch_array($select)){

                        $nome = $rsVisualizar['nome'];
                        $telefone = $rsVisualizar['telefone'];
                        $celular = $rsVisualizar['celular'];
                        $email = $rsVisualizar['email'];
                        $dt_nasc = $rsVisualizar['dt_nasc'];
                        $sexo = $rsVisualizar['sexo'];
                        $obs = $rsVisualizar['obs'];
                    

                }
                
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
        <table border="1">
            <tr>
                <td>Nome:</td>
                <td><?=$nome?></td>
            </tr>
            <tr>
                <td>Telefone:</td>
            </tr>
                <td><?$telefone?></td>
            <tr>
                <td>Celular:</td>
                <td><?=$celular?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?=$email?></td>
            </tr>
            <tr>
                <td>Sexo:</td>
                <td><?=$sexo?></td>
            </tr>
            <tr>
                <td>data de nascimento</td>
                <td><?=$dt_nasc?></td>
            </tr>
            <tr>
                <td>Obs:</td>
                <td><?=$obs?></td>
            </tr>

        </table>
</body>
</html>