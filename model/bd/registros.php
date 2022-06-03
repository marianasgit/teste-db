<?php

    require_once('conexaoMySql.php');

    $statusResposta = (bool) false;

    function selectAllRegistros()
    {
        $conexao = conexaoMySql();

        $sql = "select * from registros order by id";

        $result = mysqli_query($conexao, $sql);

        if (result)
        {
            $cont = 0;

            while ($rsDados = mysqli_fetch_assoc($result))
            {
                $arrayDados[$cont] = array(
                    "id"                => $rsDados['id'],            
                    "nome_cliente"      => $rsDados['nome_cliente'],
                    "placa_veiculo"     => $rsDados     
                    "modelo_veiculo"    
                    "entrada"
                    "saida"
                    "valor_total"
                )
            }
        }
    }
?>