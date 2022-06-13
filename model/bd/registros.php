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
                    "saida"             => $rsDados['saida']

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

    function selectByPlaca($placa_veiculo)
    {
        
        $conexao = conexaoMySql();

        $sql = "select hour(timediff(saida, entrada)) as tempoTotal from registro where placa_veiculo = '".$placa_veiculo."' ";
                
        $teste3 = mysqli_query($conexao, $sql);

        if ($teste3)
        {
            if ($rsDados = mysqli_fetch_assoc($teste3))
            {
                $tempoTotal = $rsDados['tempoTotal'];
            }
        }
        
        $teste =  mysqli_query($conexao, "select valor_primeira_hora from estacionamento where id = 1;");

        if ($teste)
        {
            if ($rsDados = mysqli_fetch_assoc($teste))
            {
                $valorPrimeiraHora = $rsDados['valor_primeira_hora'];
            }
        }

        
        $teste2 = mysqli_query($conexao, "select valor_demais_horas from estacionamento where id = 1;");
        
        if ($teste2)
        {
            if ($rsDados = mysqli_fetch_assoc($teste2))
            {
                $valorDemaisHoras = $rsDados['valor_demais_horas'];
            }
        }
        
        $valorTotal = (int) 0;   

        if ($tempoTotal <= 1)
        {
            $valorTotal = intval($tempoTotal) * intval($valorPrimeiraHora); 
            
        } else 
        {
            $valorTotal = intval($tempoTotal - 1) * (intval($valorDemaisHoras) + intval($valorPrimeiraHora));
           
        }
        

        $sql = "select * from registro where placa_veiculo = '".$placa_veiculo."' ;";

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
                    $tempoTotal,
                    $valorTotal

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

    function selectRelatorioDiario()
    {
        $conexao = conexaoMySql();

        $sql = "(select sum(valor_total) as valor_total from registro where saida = sysdate()";

        $result = mysqli_query($conexao, $sql);

        if ($result)
        {
            $cont = 0;

            while ($rsDados = mysqli_fetch_assoc($result))
            {
                $arrayDados[$cont] = array(
                    "valor_total"
                );
            }
        }
    }

?>