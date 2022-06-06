<?php
    require_once('model/config.php');

    function listarRegistros()
    {
        require_once('model/bd/registros.php');

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
            if (!empty($dadosRegistro[0]['nome']) && !empty($dadosRegistro[0]['veiculo']) && !empty($dadosRegistro[0]['placa']))
            {
                $arrayDados = array(
                    "nome"      => $dadosRegistro[0]['nome'],
                    "veiculo"   => $dadosRegistro[0]['veiculo'],
                    "placa"     => $dadosRegistro[0]['placa'],

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

    function atualizarRegistro($dadosRegistro, $arrayDados)
    {
        $id = $arrayDados['id'];

        if (!empty($dadosRegistro))
        {
            if (!empty($dadosRegistro['nome']) && !empty($dadosRegistro['veiculo']) && !empty($dadosRegistro['placa']))
            {
                if (is_numeric($id) && $id != 0 )
                {
                    $arrayDados = array (
                        "id"        => $id,
                        "nome"      => $dadosRegistro['nome'],
                        "veiculo"   => $dadosRegistro['veiculo'],
                        "placa"     => $dadosRegistro['placa']
                    );

                    require_once('model/bd/registros.php');

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

            if (deleteContato($id))
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

