<?php

    const SERVER   = 'localhost';
    const USER     = 'root';
    const PASSWORD = 'bcd127';
    const DATABASE = 'dbteste';

    $resultado = conexaoMySql();

    function conexaoMySql()
    {
        $conexao = array();

        $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

        if ($conexao)
        {
            return $conexao;

        } else
        {
            return false;
        }
    }

    function fecharConexaoMySql($conexao)
    {
        mysqli_close($conexao);
    }

?>