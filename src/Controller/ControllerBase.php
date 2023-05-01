<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ControllerBase extends AbstractController{
    

    public function index() {
        $aData = [];
        $aData['titulo'] = 'Página do Henry';
        return $this->render('base/base.html.twig', $aData);
    }

}   