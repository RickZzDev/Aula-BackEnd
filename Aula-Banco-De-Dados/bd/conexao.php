<?php 
    
    function conexaoMySql (){
        /*Conexão com banco de dados mySQL
    PRIMEIRA FORMA DE SE CONECTAR AO DB
      mysql_connect() - função para realizar a conexao com BD(Esta desatualizado nas atuais versoes do PHP)
      EX: mysql_connect(host,user,password)
      
      mysql_select_db("colocar o nome aqui") - permite escolher qual database a ser utilizado no projeto
      
    SEGUNDA FORMA DE SE CONECTAR AO DB
        mysqli_connect() - função (biblioteca) para realizar a conexão com o banco de dados (é a biblioteca mais atual para se utilizar)
        
        EX: mysqli_connect(host,user,password, database)
        
     TERCEIRA FORMA DE SE CONECTAR AO DB
        PDO() - classe para realizar a conexão com o banco de dados (100% orientada a objetos)
        
        EX: $conexao = new PDO('mysql:host=localhost;dbname',$username,$password); ********** ("nomeDoBanco=lugarDoBanco;", usuario,senha)

*/

        $host = (String) "localhost";//onde esta o banco
        $user = (String) "root";//usuario para entrar no banco 
        $password = (String) "bcd127";//senha para entrar no banco
        $database = (String) "dbcontatos20192tb";//nome do banco


        $conexao = mysqli_connect($host,$user,$password,$database);
        
        return $conexao;

    }

?>