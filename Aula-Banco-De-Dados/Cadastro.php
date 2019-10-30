<?php
    //Ativa o recurso de variaveis da sessao no servidor, se ele exister, nao sera criado dnv
    if(!isset($_SESSION))
        session_start();


    /*
        Criar variavel de sessão
            $_SESSION['NOME'];
        
        Para apagar uma variavel session do servidor
            unset($_SESSION['NOME'])
            
        Para eliminar todas as variaveis de sessao dos sistema automaticamente 
            session_destroy();
    */
    
    $chkFeminino = (string) "";
    $chkMasculino = (string) "";
    $botao = (string) "inserir";
    
    $codEstado = (int) 0;
    $siglaEstado = (String) "";
    //Import the function file
    require_once("bd/conexao.php");

    //Call to make te conection with the database
    $conexao = conexaoMySql();
    
    //verifica se existe a variavel modo e se ela esta com o valor editar
    if(isset($_GET['modo'])){
        if($_GET['modo'] == 'editar'){
            //guarda o codigo enviado
            $codigo = $_GET['codigo'];
            //esta variavel fica viva até que o navegador seja fechado, dessa forma podemos enviar para outra pagina 
            $_SESSION['codigo'] = $codigo;
            //cria uma variavel para ser executada no banco
            $sql = "select tblcontatos.*,tblestados.sigla from tblcontatos inner join
            tblestados on tblestados.codiestado = tblcontatos.codiestado where tblcontatos.codigo =".$codigo;
            //executa o codigo no banco
            $select = mysqli_query($conexao,$sql);
            //transforma o que retorna no bacno em um array e guarda as variaveis
            if($rsConsulta = mysqli_fetch_array($select)){
                    $nome = $rsConsulta['nome'];
                    $telefone = $rsConsulta['telefone'];
                    $celular = $rsConsulta['celular'];
                    $email = $rsConsulta['email'];
                    $codEstado = $rsConsulta['codiestado'];
                    $siglaEstado = $rsConsulta['sigla'];
                    $data_nascimento = explode("-",$rsConsulta['dt_nasc']);
                    $data_nascimento = $data_nascimento[2]."/".$data_nascimento[1]."/".$data_nascimento[0];
                    $sexo = $rsConsulta['sexo'];
                    
                
                    if($sexo == "feminino")
                        $chkFeminino = "checked";  
                    elseif ($sexo == "masculino")
                        $chkMasculino = "checked";
                
                $obs = $rsConsulta['obs'];
                $botao = "editar";
            }
        }
    }


?>
<html>
    <head>
        <title>
            Cadastro
        </title>
        <link href="Cadastro.css" rel="stylesheet" type="text/css">
        <script src="JS/jquery.js"></script>
        <script src="JS/modulo.js"></script>
        
        <script>
            //Chamando o jquery assim que a pagina for lida
            $(document).ready(function(){
                //function que abre a modal
               $('.visualizar').click(function(){
                    $('#modal_caixa').fadeIn(1000);
               });
               
               $('#fechar').click(function(){
                $('#modal_caixa').fadeOut(1000)
            });

            });
            //esta função ira enviar via post, para uma pagina, e envia pela url o que eh necessario
            function visualizarDados(idItem)
            {
               $.ajax({
                 type:"POST",
                 url: "modalContatos.php",
                 data:{modo:'visualizar', codigo:idItem},
                 success : function(dados)
                   {
                    $('#modalDados').html(dados);
                   }   
               });
            }
        </script>
    </head>
    
    <body>
        <!-- Construir a modal que ira receber os dados
             de outro arquivo, através do javascript
        -->
        <div id="modal_caixa">
            <div id="modal">
                <div id="fechar">Fechar</div>
                <div id="modalDados"></div>
            </div>
        </div>
        
        <div id="barra_sup"></div>
        <div id="conteudo">
            
            <!--
                required - torna o preenchimento obrigatorio da caixa        
            
                type="text"
                     "email"
                     "tel"
                     "number" Caixinha com setas para selecionar o numero, podendo usar min e max
                     "range"
                     "url"
                     "password"
                     "date"
                     "month"
                     "week"
                     "color"
                        
                 
                pattern - permite criar uma mascara para a entrada de dados em um formulario

                exemplo de expressão regular - [A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ] para nomes.
            -->
            <!--PARA O UPLOAD DE ARQUIVO FUNCIONAR O FORM PRECISA DO ENCTYPE, E O METODO PRECISA SER POST -->
            <div id="titulo_cadastro">
                Cadastro de Contatos
            </div>
            <form name="frmCadastro" method="post" action="bd/salvar.php" enctype="multipart/form-data">
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Nome:
                </div>
                <div class="campo_formulario">
                    <input value="<?=@$nome?>" name="txtNome" type="text" placeholder="Digite seu nome" required onkeypress="return validarEntrada(event,1);">
                </div>
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Telefone:
                </div>
                <div class="campo_formulario">
                    <input value="<?= @$telefone?>" id="telefone" placeholder="Ex:999 9999-9999" name="txtTelefone" type="text" required onkeypress="return mascaraNumero(this,event,2);">
                </div>
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Celular:
                </div>
                <div class="campo_formulario">
                    <input value="<?=@$celular?>"placeholder="Digite seu celular" name="txtCelular" type="text" required onkeypress="return validarEntrada(event,3)">
                </div>                
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Email:
                </div>
                <div class="campo_formulario">
                    <input value="<?=@$email?>" placeholder="Digite seu email" name="txtEmail" type="email" required>
                </div>                
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                    foto
                </div>
                <div class="campo_formulario"><!--Restringir extensão do arquivo -->
                    <input name="fleFoto" type="file" required>
                </div>                
            </div>    
                
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Estado:
                </div>
                <div class="campo_formulario">
                    <select name="sltEstados">
                        <?php if($_GET['modo']=="editar"){

                         ?>
                        <option value="<?=$codEstado?>"><?=$siglaEstado?></option>
                            
                        <?php 
                            }else{
                        ?>
                        <option value=""> Selecione um estado</option>
                            <?php }?>
                        <?php 
                            $sql = "select * from tblestados where codiestado <> ".$codEstado;

                            $select = mysqli_query($conexao,$sql);


                            while($rsEstados = mysqli_fetch_array($select)){
                            ?>

                                <option value="<?=$rsEstados['codiestado']?>">
                                    <?=$rsEstados['sigla']?>
                                </option>
                           <?php } ?>
                        
                       
                    </select>
                </div>                
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                    Data Nascimento:
                </div>
                <div class="campo_formulario">
                    <input value="<?=@$data_nascimento?>"placeholder="00/00/0000" name="txtData" type="text" required>
                </div>                
            </div>
            <div class="campos_cadastro">
                <div class="nome_campo">
                   Sexo:
                </div>
                <div class="campo_formulario">
                    <input name="rdoSexo" type="radio" value="feminino" <?=@$chkFeminino?> required>
                            Feminino
                    <input name="rdoSexo" type="radio" value="masculino" <?=@$chkMasculino?> required>
                            Masculino
                </div>                
            </div>                
            <div class="campos_cadastro">
                <div class="nome_campo">
                    OBS:
                </div>
                <textarea name="txtObs"><?=@$obs?></textarea>
                
            </div>
            
            <div id="botoes">
                <input name="btnSalvar" type="submit" value="<?=$botao?>">
                    
            </div>    
             </form>   
        </div>
        

        
        <div id="consultas">
            <div id="titulo_consulta_caixa">
                Consulta de Contatos
            </div>
            <div id="titulos">
                <div class="titulosNew">Nome</div>
                <div class="titulosNew">Telefone</div>
                <div class="titulosNew">Celular</div>
                <div class="titulosNew">Email</div>
                <div class="titulosNew">Estado</div>
                <div class="titulosNew">opções</div>
                
            </div>
                    <?php 
            $sql = "select tblcontatos.*,tblestados.sigla,
            tblestados.descricao
            from tblcontatos inner join tblestados on tblestados.codiestado= tblcontatos.codiestado";
            $select = mysqli_query($conexao,$sql);
        
        
        //TRANSFORMA O RETORNO DO BANCO EM UM ARRAY, OU UM DADO ASSOCIATIVO, OU UM OBJETO
            //mysqli_fetch_array() 
            //mysqli_fetch_assoc() 
            //mysqli_fetch_object() 
        
        
            //gera um loop que guarda os dados enquanto retornarem
            while ($rsContatos = mysqli_fetch_array($select)){
                
            
            ?>
            
            <!-- Div criada da forma certa para não bugar-->
            <div class="segura_tudo">
                <!--Abrindo php e mandando a variavel que recebe o dado do banco de dados a partir do seu respectivo nome-->
                <div class="camposVazios"><?=$rsContatos['nome'];?></div>
                <div class="camposVazios"><?=$rsContatos['telefone'];?></div>
                <div class="camposVazios"><?=$rsContatos['celular'];?></div>
                <div class="camposVazios"><?=$rsContatos['email'];?></div>
                <div class="camposVazios"><?=$rsContatos['sigla'].$rsContatos['descricao'];?></div>
                <div class="opcoes">
                    
                        <div class="iconesNew">
                            <a href="Cadastro.php?modo=editar&codigo=<?=$rsContatos['codigo']?>"><img src="imagens/iconfinder_pencil_285638%20(1).png"></a> 
                        </div>
                           
                    <div class="iconesNew"><!--Linkando para o arquivo delete, criando a variavel modo, e resgatandoo indice -->
                        <a  onclick="return confirm('Deseja realmente exluir esse registro?');" href="bd/deletar.php?modo=excluir&codigo=<?=$rsContatos['codigo']?>"><img src="imagens/iconfinder_button_close_352915%20(1).png">
                        </a>
                        </div>
                    
                        <div class="iconesNew">
                            <a href="#" class="visualizar" onclick="visualizarDados(<?=$rsContatos['codigo']?>);">
                                <img src="imagens/iconfinder_013_MagnifyingGlass_183520.png"></a>
                        </div>
                   
                </div>
            </div>
            <!--Fechando o loop no lugar certo-->
            <?php } ?>
        </div>        

        
    </body>
</html>
