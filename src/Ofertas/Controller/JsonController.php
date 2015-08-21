<?php

namespace Ofertas\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JsonController extends Controller
{
    public function listaCategorias()
    {
    
	$pdo = $this->container->get('db1')->getPDO();
	
	
	$data = $pdo->query("SELECT id as id_categoria, nome as label_categoria FROM ofertas_categorias")->fetchAll();
			  
			  
	
	$response = new Response(json_encode(array("res"=>$data)));
	$response->headers->set('Content-Type', 'application/json');
	
	return $response;
    }
	
	public function listaOfertas()
    {
    
	$request = Request::createFromGlobals();
	$path = $request->getPathInfo(); // the URI path being requested
	
	$_query = $request->query->get('q');
	$_category = $request->query->get('cat');
	$_lat = $request->query->get('lat');
	$_lng = $request->query->get('lng');
	
	/*Paginação*/
	$_pag = $request->query->get('pagina');
	$_tot = $request->query->get('total');
	
	$_pag = (($_pag*$_tot) - $_tot);
	
	if($request->query->get('kms')){
	$_kms_query = "having oferta_km <= ".$request->query->get('kms');
	}else{
	$_kms_query = "";	
	}
	
	$pdo = $this->container->get('db1')->getPDO();
	
	$total_ofertas = count($pdo->query("SELECT titulo as oferta_nome, imagem as oferta_img, desconto as oferta_desconto, CONVERT((((acos(sin(($_lat*pi()/180)) * sin((ofer.lat*pi()/180))+cos(($_lat*pi()/180)) * cos((ofer.lat*pi()/180)) * cos((($_lng- ofer.lng)*pi()/180))))*180/pi())*60*1.1515),SIGNED) as oferta_km FROM ofertas as ofer where ofer.titulo LIKE '%$_query%' and ofertas_categorias LIKE '%$_category%' $_kms_query")->fetchAll());
	
	$ofertas = $pdo->query("SELECT titulo as oferta_nome, imagem as oferta_img, desconto as oferta_desconto, CONVERT((((acos(sin(($_lat*pi()/180)) * sin((ofer.lat*pi()/180))+cos(($_lat*pi()/180)) * cos((ofer.lat*pi()/180)) * cos((($_lng- ofer.lng)*pi()/180))))*180/pi())*60*1.1515),SIGNED) as oferta_km FROM ofertas as ofer where ofer.titulo LIKE '%$_query%' and ofertas_categorias LIKE '%$_category%' $_kms_query order by oferta_km LIMIT $_tot OFFSET $_pag")->fetchAll();
			  
	
	$response = new Response(json_encode(array("res"=>$ofertas,"total"=>$total_ofertas)));
	$response->headers->set('Content-Type', 'application/json');
	
	return $response;
    }
	
	public function get_filters()
    {
    
	$request = Request::createFromGlobals();
	$path = $request->getPathInfo(); // the URI path being requested
	
	$_filters_options = array();
	
	$_query = $request->query->get('q');
	$_category = $request->query->get('cat');
	$_lat = $request->query->get('lat');
	$_lng = $request->query->get('lng');
	
	if($request->query->get('kms')){
	$_kms_query = "having oferta_km <= ".$request->query->get('kms');
	}else{
	$_kms_query = "";	
	}
	
	$pdo = $this->container->get('db1')->getPDO();
	
	$data_options = $pdo->query("SELECT titulo as oferta_nome, imagem as oferta_img, desconto as oferta_desconto, CONVERT((((acos(sin(($_lat*pi()/180)) * sin((ofer.lat*pi()/180))+cos(($_lat*pi()/180)) * cos((ofer.lat*pi()/180)) * cos((($_lng- ofer.lng)*pi()/180))))*180/pi())*60*1.1515),SIGNED) as oferta_km FROM ofertas as ofer where ofer.titulo LIKE '%$_query%' and ofertas_categorias LIKE '%$_category%' $_kms_query")->fetchAll();
		
	
	foreach($data_options as $data_option){
	$_filters_options["max_kms"] = $data_option['oferta_km'];
	$_filters_options["max_desconto"] = $data_option['oferta_desconto'];
	}
			  
	$response = new Response(json_encode($_filters_options));
	$response->headers->set('Content-Type', 'application/json');
	
	return $response;
    }
	
}