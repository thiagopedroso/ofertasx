<?php

namespace Ofertas\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListaController extends Controller
{
    public function listaAction()
    {
        return $this->render('list.html.twig');
    }
}