<?php
    
    if(!isset($_SESSION))
        session_start();

    if(isset($_POST['btnSalvar']))
    {
        //importa o aqrquivo de conexao 
        require_once('conexao.php');
        //chama a função e envia os parametros
        $conexao = conexaoMySql();
        //verifica se houve o clique do botao
        //resgata os dados digitados 
        $nome = $_POST['txtNome'];    
        $telefone = $_POST['txtTelefone'];    
        $celular = $_POST['txtCelular'];    
        $email = $_POST['txtEmail'];

        $codiEstado= $_POST['sltEstados'];
        //O EXPLODE PERCORRE UMA STRING LOCALIZA UM CARACTER E A QUEBRA EM UM ARRAY
        $data = explode("/",$_POST['txtData']);
        $data = $data[2]. "-" . $data[1] . "-" . $data[0];
        $rdoSexo = $_POST['rdoSexo'];    
        $obs = $_POST['txtObs'];
        
//        var_dump($_FILES['fleFoto']);
        //Confere se há um retorno da imagem e se tem tipo, caso a imagem ultrapasse o tamanho do permitido no servidor, este sera o erro apresentado
        if($_FILES['fleFoto']['size']==0 && $_FILES['fleFotos']['type']== "")
        {
            echo("<script>alert('Arquivo não selecionada conforme tamanho ou tipo de aquivo') </script>");
        }else{
            //Guarda o tamanho do arquivo a ser upado para o servidor, o file vem como array
            $arquivo_size = $_FILES['fleFoto']['size'];
            //Converte o tamanho do arquivo para kbytes e define como padrao 2 casas decimais
            $tamanho_arquivo = round($arquivo_size/1024);
            //Definindo quais extensões serao permitidas
            $arquivos_permitidos = array("image/jpeg","image/jpg","image/png");
            //Guarda o tipo de extens]ao do arquivo
            $ext_arquivo = $_FILES['fleFoto']['type'];
            //in_array()- parametro 1, o que procurar, params2 onde procurar

            var_dump($ext_arquivo);
            if(in_array($ext_arquivo, $arquivos_permitidos)){

                echo("entrou");
                echo($tamanho_arquivo);
                //Valida o tamanho do arquivo que esta sendo upado
                if($tamanho_arquivo < 2000){    
                //Permite retornar apenas o nome do arquivo pathinfo('',pathinfo_filename); 
                    $nome_arquivo = pathinfo($_FILES['fleFoto']['name'], PATHINFO_FILENAME);
                //Permite retornar apenas a extensão do arquivo com o PATHINFO_EXTENSION    
                    $ext = pathinfo($_FILES['fleFoto']['name'],PATHINFO_EXTENSION);
                    //O md5 faz uma criptografia do dado, ou sha1(), ou hash('tipo de cripty',variavel);
                    //EX: hash(tipo,var)
                    //Gerando uma criptografia com md5 + uniqid() numero aleatorio com base em uma hh,mm,ss
                    $nome_arquivo_cripty = md5(uniqid(time()).$nome_arquivo);
                    //juntando o nome criptografado com a extensao 
                    $foto = $nome_arquivo_cripty. ".". $ext;
                    //Guardando o caminho da imagem no servidor
                    $arquivo_temp = $_FILES['fleFoto']['tmp_name'];
                    //esta variavel guarda o caminho no servidor
                    $diretorio = "arquivos/";
                    
                    //o move ira jogar no servidor o nome da foto criptografado mais a extensão e joga para o diretorio com o devido nome
                    if(move_uploaded_file($arquivo_temp,$diretorio.$foto)){
                            echo($nome_arquivo_cripty);

                            echo($ext);
                            echo($nome_arquivo);
                        //Verifica se o que vem do botao é a palavra inserir
                        if(strtoupper($_POST['btnSalvar'])=="INSERIR")
                        {
                            //se vier, o sql ira receber o script para inserir, colocamos todos os campos e depois todos os valores
                            $sql= "
                                insert into tblcontatos(nome, telefone, celular, email,
                                                       dt_nasc,sexo, obs, codiestado)

                                        values('".$nome."','".$telefone."','".$celular."',
                                                '".$email."','".$data."','".$rdoSexo."','".$obs."', ".$codiEstado.")";            
                        }
                        //Verifica se o valor é editar
                        elseif(strtoupper($_POST['btnSalvar'])=="EDITAR")
                        {
                            //no update colocamos os campos ja com os devidos valores
                            $sql = "update tblcontatos set 
                            nome = '".$nome."', telefone = '".$telefone."', celular = '".$celular."', email = '".$email."', sexo = '".$rdoSexo."', dt_nasc = '".$data."', obs = '".$obs."' where codigo =  ".$_SESSION['codigo'];
                        }




                        //Executa um script no banco de dados, a variavel conexao
                        //chama a função que ja faz a conexao com o banco
                        if(mysqli_query($conexao, $sql)){

                                        //Redireciona para uma determinda página
                //            header('location:../Cadastro.php' , $sql);

                        }

                        else
                            echo ("Erro ao enviar ao banco");

                    }else{
                        echo("<script> alert('Não foi')</script>");
                    }    
                }
                else{
                    echo("<script> alert('Tamanho de Arquivo , deve ser maior que 2Mb')</script>");
                }
             }
                else
                {
                    echo("<script> alert ('Favor escolher somente .jpg, .png, .jpeg')</script>");
                }
            
          }    
    }
?>