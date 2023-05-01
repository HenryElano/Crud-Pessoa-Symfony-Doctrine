<?php
namespace App\Controller;

use App\Entity\Pessoa;
use App\Form\PessoaType;
use App\Repository\PessoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerPessoa extends AbstractController {

    /** @var $Form \Symfony\Component\Form\FormInterface */
    private $Form;

    /**
     * Rota para consultar todas as pessoas cadastradas.
     * @param  \App\Repository\PessoaRepository $oRepositorioPessoa
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function consultaPessoas(PessoaRepository $oRespositorioPessoa) {
        $aDados['pessoas'] = $oRespositorioPessoa->findAll();
        $aDados['titulo']  = 'Consulta de Pessoas';
        return $this->render('Pessoa/consulta.html.twig', $aDados);
    }

    /**
     * Rota para Inserir novas pessoas.
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Doctrine\ORM\EntityManagerInterface      $oEntity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inserir(Request $oRequest, EntityManagerInterface $oEntity) : Response {
        $sMsg = '';
        $oPessoa = new Pessoa();
        if($this->executaInclusao($oPessoa, $oRequest, $oEntity)) {
            $sMsg = 'Pessoa Inserida com sucesso!';
        }
        $aDados['titulo'] = 'Adicionar Nova Pessoa';
        $aDados['form']   = $this->getForm($oPessoa, $oRequest);
        $aDados['msg']    = $sMsg;
        return $this->render('Pessoa/form.html.twig', $aDados);
    }

    /**
     * Rota para Editar os dados da pessoa.
     * @param Integer $id
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Doctrine\ORM\EntityManagerInterface      $oEntity
     * @param \App\Repository\PessoaRepository          $oRepositorioPessoa
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editar($id, Request $oRequest, EntityManagerInterface $oEntity, PessoaRepository $oRepositorioPessoa) { 
        $sMsg = '';
        $oPessoa = $oRepositorioPessoa->find($id);

        if($this->executaAlteracao($oPessoa, $oRequest, $oEntity)) {
            $sMsg = 'Pessoa editada com sucesso!';
        }

        $aDados['titulo'] = 'Adicionar Nova Pessoa';
        $aDados['form']   = $this->getForm($oPessoa, $oRequest);
        $aDados['msg']    = $sMsg;
        return $this->render('Pessoa/form.html.twig', $aDados);
    }

    /**
     * Rota para exclusão da pessoa.
     * @param Integer $id
     * @param \App\Repository\PessoaRepository $oRepositorioPessoa
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function excluir($id, EntityManagerInterface $oEntity, PessoaRepository $oRepositorioPessoa) {
        $oEntity->remove($oRepositorioPessoa->find($id));
        $oEntity->flush();
        return $this->redirectToRoute('consultaPessoas');
    }

    /**
     * Retorna o formulário.
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getForm($oPessoa, $oRequest) {
        return $this->Form ?: $this->createFormPessoa($oPessoa, $oRequest);
    }

    /**
     * Cria o formulário de Pessoa.
     */
    private function createFormPessoa($oPessoa, $oRequest) {
        $this->Form = $this->createForm(PessoaType::class, $oPessoa);
        $this->Form->handleRequest($oRequest);
        return $this->Form;
    }

    /**
     * Executa a inclusão da pessoa.
     */
    private function executaInclusao($oPessoa, $oRequest, $oEntity) {
        $bInseriu = false;
        if($this->getForm($oPessoa, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->persist($oPessoa);
            $oEntity->flush();
            $bInseriu = true;
        }
        return $bInseriu;
    }

    /**
     * Executa a alteração da pessoa.
     */
    private function executaAlteracao($oPessoa, $oRequest, $oEntity) {
        $bAlterou = false;
        if($this->getForm($oPessoa, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->flush();
            $bAlterou = true;
        }
        return $bAlterou;
    }

}