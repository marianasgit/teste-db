<?php

    require_once(SRC.'modulo/config.php');

    function listarRegistros()
    {
        require_once(SRC.'model/bd/registros.php');

        $dados = selectAllRegistros();

        if (!empty($dados))
        {
            return $dados;

        } else {
            return false;
        }
    }

    function inserirRegistro($dadosRegistro)
    {
        if (!empty($dadosRegistro))
        {
            if (!empty($dadosRegistro[0]['nome_cliente']) && !empty($dadosRegistro[0]['placa_veiculo']) && !empty($dadosRegistro[0]['modelo_veiculo']))
            {
                $arrayDados = array(
                    "nome_cliente"      => $dadosRegistro[0]['nome_cliente'],
                    "placa_veiculo"     => $dadosRegistro[0]['placa_veiculo'],
                    "modelo_veiculo"    => $dadosRegistro[0]['modelo_veiculo'],

                );

                require_once(SRC.'model/bd/registros.php');

                if (insertRegistro($arrayDados))
                {
                    return true;
                } else 
                {
                    return array ('idErro' => 1, 'message' =>'Não foi possível inserir os dados');
                }

            } else 
            {
                return array ('idErro' => 2, 'message' => 'Existem campos obrigatórios que não foram preenchidos');
            }
        }
    }

    function buscarRegistro($id)
    {
        if ($id != 0 && !empty($id) && is_numeric($id))
        {
            require_once(SRC.'model/bd/registros.php');

            $dados = selectByIdRegistro($id);

            if (!empty($dados))
            {
                return $dados;
            } else 
            {
                return false;
            }
        } else 
        {
            return array ('idErro' => 4, 'message' => 'Não é possível buscar um registro sem informar um Id válido');
        }
    }

    function atualizarRegistro($dadosRegistro)
    {
        $id = $dadosRegistro['id'];

        if (!empty($dadosRegistro))
        {
            if (!empty($dadosRegistro['nome_cliente']) && !empty($dadosRegistro['placa_veiculo']) && !empty($dadosRegistro['modelo_veiculo']))
            {
                if (is_numeric($id) && $id != 0 )
                {
                    $arrayDados = array (
                        "id"        => $id,
                        "nome_cliente"    => $dadosRegistro['nome_cliente'],
                        "placa_veiculo"   => $dadosRegistro['placa_veiculo'],
                        "modelo_veiculo"  => $dadosRegistro['modelo_veiculo'],
                        "entrada"         => $dadosRegistro['entrada'],
                        "saida"           => $dadosRegistro['saida'],    
                    );


                    require_once(SRC.'model/bd/registros.php');

                    if (updateRegistro($arrayDados))
                    {
                        return true;
                    } else
                    {
                        return array ('idErro' => 1, '');
                    }
                } else
                {
                    return array ('idErro' => 1, 'message' => 'Não é possível editar um registro sem informar um Id válido');
                }
            } else
            {
                return array ('idErro' => 2, 'message' => 'Existem campos obrigatórios que não foram preenchidos');
            }
        }
    }

    function excluirRegistro($arrayDados)
    {
        $id = $arrayDados['id'];

        if ($id != 0 && is_numeric($id))
        {
            require_once(SRC.'model/bd/registros.php');

            if (deleteRegistro($id))
            {
                return true;

            } else
            {
                return array ('idErro' => 3, 'message' => 'O banco de dados não pode excluit o registro.');
            }
        } else
        {
            return array( 'idErro' => 3, 'message' => 'Não é possível excluir um registro sem informar um Id válido');
        }
    }