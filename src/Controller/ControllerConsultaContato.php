<?php
namespace App\Controller;

use App\Repository\ContatoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Response;

class ControllerConsultaContato extends AbstractController {

    /**
     * Inicializa a consulta de Contatos
     * @param \App\Repository\ContatoRepository $oRepositorioContato
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function consultarContatos(ContatoRepository $oRepositorioContato) : Response {
        return $this->render('contato/consulta.html.twig', ['contatos' => $oRepositorioContato->findAll(), 'titulo' => 'Consultar Contatos']);
    }



}