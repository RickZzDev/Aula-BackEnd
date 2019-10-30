function validarEntrada(caracter,origem)
{
    //charCode converte o caracter digitado em seu numero corresponde da tabela ascii
    
    
    //Serve para padronizar a vconversaõ em ascii em todas as versoes de navegadores.
    //Os que sao baseados em janela(window) e os que nao sao.
    
    var origemV = origem;
    
    if(window.event)
        var asc = caracter.charCode;
    else
        var asc = caracter.which;
    


    
    if(origemV==1 ){
            if(asc >=48 && asc <=57)
        return false;//cancela o evento da tecla digitada
          //valida apenas a digitação de letras, 
    }if(origemV==2 || origem==3){
            console.log(asc)
           if(asc<48 || asc>57){
          console.log("entrou")
            return false;
           }

    }
  

    
    
    
 
}


function mascaraNumero(obj,caracter,origem)
{
    if(validarEntrada(caracter, origem) == false){
        return false;
    }else{
    
        var input = obj.value;
        var id = obj.id;
        var resultado = input;

        if(input.length == 0)
            resultado = "(";
        else if(input.length == 4)
            resultado += ") ";    
        else if(input.length == 10)
            resultado += "-";
        else if (input.length == 15)
            return false;
       document.getElementById(id).value = resultado;
    }
}