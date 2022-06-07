<?php
 header('Access-Control-Allow-Origin: *');

 //Ativa os métodos do protocolo do HTTP que irão requisitar a API
 header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 

 //ativa o Content-Type das requisições (Formato de dados que será utilizado (JSON, XML, FORM/DATA, etc..))
 header('Access-Control-Allow-Header: Content-Type'); 

 //Permite liberar quais content-type serão utilizados na API
 header('Content-Type: application/json'); 

 //Recebe a URL digitada na requisição
 $urlHTTP = (string) $_GET['url'];
 
 //Converte a URL requisitada em um array para dividir as opções de buscas, que é separada pela barra
 $url = explode('/', $urlHTTP);

 //Verifica qual a API será encaminhada a requisição
 switch (strtoupper($url[0])){
     case 'REGISTROS':
          require_once('registrosApi/index.php');
     break;
   
 }

?>