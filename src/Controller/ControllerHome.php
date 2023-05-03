<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Response;

class ControllerHome extends AbstractController {

    /**
     * Método que Renderiza a Home Page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() : Response {
        return $this->render('home/home.html.twig');
    }



}