<?php

use Slim\Http\Request;

require_once('vendor/autoload.php');

$app = new \Slim\app();

// Endpoint para LISTAR todos os registros
$app->get('/registros', function($request, $response, $args)
{
    require_once('../modulo/config.php');
    require_once('../controller/registrosController.php');
    
    
    if ($dados = listarRegistros())
    {
        if ($dadosJSON = createJSON($dados))
        {
            return $response    -> withStatus(200)
                                -> withHeader('Content-Type', 'application/json')
                                -> write($dadosJSON);
        }
        
    } else
    {
        var_dump($dados);
        die; 
        return $response    -> withStatus(404)
                            -> withHeader('Content-Type', 'application/json')
                            -> write('{"message" : "Item não encontrado"}');

                            
        
    }
});

// Endpoint para listar apenas um registro
$app->get('/registros/{id}', function($request, $response, $args)
{
    $id = $args['id'];

    require_once('../modulo/config.php');
    require_once('../controller/registrosController.php');
 

    if ($dados = buscarRegistro($id))
    {
        if (!isset($dados['idErro']))
        {
            if ($dadosJSON = createJSON($dados))
            {
                return $response    -> withStatus(200)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write($dadosJSON);
            }
        } else
        {
            $dadosJSON = createJSON($dados);
            
            return $response    -> withStatus(404) 
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Dados Inválidos", "Erro" : '.$dadosJSON.'}');

        } 
    } else
        {
            return $response    -> withStatus(404)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Item não encontrado"}');
        }
});

// Endpoint para inserir um registro
$app->post('/registros', function($request, $response, $args)
{
    $contentTypeHeader = $request -> getHeaderLine('Content-Type');

    $contentType = explode(";", $contentTypeHeader);

    switch ($contentType[0])
    {
        case 'multipart/form-data' :

            $dadosBody = $request -> getParsedBody();

            $arrayDados = array ($dadosBody);

            require_once('../modulo/config.php');
            require_once('../controller/registrosController.php');

            
            $resposta = inserirRegistro($arrayDados);
            
            
            if (is_bool($resposta) && $resposta == true)
            {
                return $response -> withStatus(201)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write('{"message" : "Registro inserido com sucesso"}');


            } elseif (is_array($resposta) && $resposta['idErro'])
            {
                $dadosJSON = createJSON($resposta);

                return $response    -> withStatus(404)
                                    -> withHeader('Content-Type', 'application/josn')
                                    -> write('{"message" : "Houve um problema no processo de inserção", "Erro" : '.$dadosJSON.'}');
            }

            break;

        case 'application/json' :
            
            return $response    -> withStatus(200)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Formato selecionado foi JSON"}');

            break;
            
        default :
            return $response    -> withStatus(400)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Formato selecionado inválido"}');

            break;                        


    }
});

// Endpoint para deletar registro
$app->delete('/registros/{id}', function($request, $response, $args)
{
    if (is_numeric($args['id']))
    {
        $id = $args['id'];

        require_once('../modulo/config.php');
        require_once('../controller/registrosController.php');

        if ($dados = buscarRegistro($id))
        {
            $arrayDados = array("id" => $id);

            $resposta = excluirRegistro($arrayDados);

            if (is_bool($resposta) && $resposta == true)
            {
                return $response    -> withStatus(200)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write('{"message" : "Registro excluído com sucesso"}');

            } else 
            {
                return $response    -> withStatus(404)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write('{"message" : "O Id informado não foi encontrado"}');
            }
        }
    }
});

$app->post('/registros/{id}', function ($request, $response, $args){

    if (is_numeric($args['id']))
    {
        $id = $args['id'];

        $contentTypeHeader = $request->getHeaderLine('Content-Type');

        $contentType = explode(";", $contentTypeHeader);

        switch ($contentType[0]){

            case 'multipart/form-data':
                
                require_once('../modulo/config.php');
                require_once('../controller/registrosController.php');

                if ($dadosRegistro = buscarRegistro($id))
                {
                    $dadosBody = $request->getParsedBody(); 

                    $arrayDados = array(
                        $dadosBody,
                        "id" => $id
                    );

                    $resposta = atualizarRegistro($arrayDados);

                    if (is_bool($resposta) && $resposta == true)
                    {
                        return $response->withStatus(201)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Registro atualizado com sucesso"}');

                    } elseif (is_array($resposta) && $resposta['idErro'])
                    {
                        $dadosJSON = createJSON($resposta);
                        return $response->withStatus(404)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Houve um problema no processo de atualização",
                                                    "Erro": ' . $dadosJSON . '} ');
                    }

                } else 
                {
                    return $response->withStatus(404)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "O ID informado não existe na base de dados."} ');
                }
                break;

            case 'application/json':
                $dadosBody = $request->getParsedBody();

                return $response->withStatus(200)
                                ->withHeader('Content-Type', 'application/json')
                                ->write('{"message": "Formato selecionado foi JSON"}');
        
                break;
        
              default:
                return $response->withStatus(400)
                                ->withHeader('Content-Type', 'application/json')
                                ->write('{"message":  "Formato do Content-Type não é válido para esta requisição"}');
                break;
        }
    } else 
    {
        return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->writewrite('{"message": "É obrigatório informar um ID com formato válido (número)"}');
    }
});

$app->get('/registros/placa/{placa_veiculo}', function ($request, $response, $args)
{
    
    $placa_veiculo = $args['placa_veiculo'];

    require_once('../modulo/config.php');
    require_once('../controller/registrosController.php');

    if ($dados = buscarPlaca($placa_veiculo))
    {
        if (!isset($dados['idErro']))
        {
            if ($dadosJSON = createJSON($dados))
            {
                return $response    -> withStatus(200)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write($dadosJSON);
            }
        } else
        {
            $dadosJSON = createJSON($dados);
            
            return $response    -> withStatus(404) 
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Dados Inválidos", "Erro" : '.$dadosJSON.'}');

        } 
    } else
        {
            return $response    -> withStatus(404)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('{"message" : "Item não encontrado"}');
        }    
});

$app->run();

?>