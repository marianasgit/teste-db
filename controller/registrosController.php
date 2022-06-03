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
