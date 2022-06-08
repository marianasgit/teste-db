<?php

    require_once('conexaoMySql.php');

    $statusResposta = (bool) false;

    function selectAllRegistros()
    {
        $conexao = conexaoMySql();

        $sql = "select * from registro order by id";

        $result = mysqli_query($conexao, $sql);

        if ($result)
        {
            $cont = 0;

            while ($rsDados = mysqli_fetch_assoc($result))
            {
                $arrayDados[$cont] = array(
                    "id"                => $rsDados['id'],            
                    "nome_cliente"      => $rsDados['nome_cliente'],
                    "placa_veiculo"     => $rsDados['placa_veiculo'],     
                    "modelo_veiculo"    => $rsDados['modelo_veiculo'],
                    "entrada"           => $rsDados['entrada'],
                    "saida"             => $rsDados['saida'],
                    "valor_total"       => $rsDados['valor_total']
                );

                $cont++;
            }

            fecharConexaoMySql($conexao);

            if (isset($arrayDados))
            {
                return $arrayDados;
            } else 
            {
                return false;
            }
        }
    }

    function insertRegistro($dadosRegistro)
    {


        $statusResposta = (bool) false;

        $conexao = conexaoMySql();

        $sql = "insert into registro
                            (nome_cliente,
                             placa_veiculo,
                             modelo_veiculo,
                             entrada
                             )
                    values
                        ('" . $dadosRegistro['nome_cliente'] ."',
                        '" . $dadosRegistro['placa_veiculo'] ."',
                        '" . $dadosRegistro['modelo_veiculo'] ."',
                        current_timestamp());";

        if (mysqli_query($conexao, $sql))
        {
            if (mysqli_affected_rows($conexao))
            {
                $statusResposta = true;
            }
        } else
        {
            fecharConexaoMySql($conexao);
        }                
        return $statusResposta;
    }

    function updateRegistro($dadosRegistro)
    {
        
        var_dump($dadosRegistro);
        die;

        $conexao = conexaoMySql();

        $sql = "update registro set
                        nome_cliente = '". $dadosRegistro['nome_cliente'] ."',
                        placa_veiculo = '". $dadosRegistro['placa_veiculo'] ."',
                        modelo_veiculo = '". $dadosRegistro['modelo_veiculo'] ."',
                        entrada = '". $dadosRegistro['entrada'] ."',
                        saida = '". $dadosRegistro['saida']."'
                    where id =" .$dadosRegistro['id'];

        if (mysqli_query($conexao, $sql))
        {
            if (mysqli_affected_rows($conexao))
            {
                $statusResposta = true;
            }
        } else 
        {
            fecharConexaoMySql($conexao);
        }
        
        return $statusResposta;
    }

    function deleteRegistro($id)
    {
        $conexao = conexaoMySql();

        $sql = "delete from registro where id = " .$id;

        if (mysqli_query($conexao, $sql))
        {
            if (mysqli_affected_rows($conexao))
            {
                $statusResposta = true;
            }
        }

        fecharConexaoMySql($conexao);

        return $statusResposta;
    }

    function selectByIdRegistro($id)
    {
        $conexao = conexaoMySql();

        $sql = "select * from registro where id = " .$id;

        $result = mysqli_query($conexao, $sql);

        if ($result)
        {
            if ($rsDados = mysqli_fetch_assoc($result))
            {
                $arrayDados = array(
                    "id"                => $rsDados['id'],
                    "nome_cliente"      => $rsDados['nome_cliente'],
                    "placa_veiculo"     => $rsDados['placa_veiculo'],
                    "modelo_veiculo"    => $rsDados['modelo_veiculo'],
                    "entrada"           => $rsDados['entrada'],
                    "saida"             => $rsDados['saida'],

                );
            }
        }

        fecharConexaoMySql($conexao);

        if(isset($arrayDados))
        {
            return $arrayDados;
        } else 
        {
            return false;
        }
    }
?>