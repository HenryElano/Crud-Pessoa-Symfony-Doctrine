<?php
namespace App\Controller;

use App\Repository\PessoaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerConsultaPessoa extends AbstractController {

    /**
     * Rota para consultar todas as pessoas cadastradas.
     * @param  \App\Repository\PessoaRepository $oRepositorioPessoa
     * @param  \Symfony\Component\HttpFoundation\Request $oRequest
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function consultarPessoas(PessoaRepository $oRespositorioPessoa, Request $oRequest) : Response {
        $xNomePessoa = $oRequest->query->get('nome');
        $aDados = !is_null($xNomePessoa) ? $oRespositorioPessoa->findByNome($xNomePessoa) : $oRespositorioPessoa->findAll();
        return $this->render('Pessoa/consulta.html.twig', ['pessoas' => $aDados, 'titulo' => 'Consulta de Pessoas', 'nomePessoa' => $xNomePessoa]);
    }

}