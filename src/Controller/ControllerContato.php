<?php
namespace App\Controller;

use App\Entity\Contato;
use App\Form\ContatoType;
use App\Repository\ContatoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerContato extends AbstractController {

    private $Form;

    public function consultaContatos(ContatoRepository $oRepositorioContato) {
        $aDados['contatos'] = $oRepositorioContato->findAll();
        $aDados['titulo']  = 'Gerenciar Contatos';
        
        return $this->render('contato/consulta.html.twig', $aDados);
    }

    public function inserir(Request $oRequest, EntityManagerInterface $oEntity) {
        $sMsg = '';
        $oContato = new Contato();
        if($this->executaInclusao($oContato, $oRequest, $oEntity)) {
            $sMsg = 'Contato inserido com sucesso!';
        }
        $aDados['titulo'] = 'Adicionar um novo contato';
        $aDados['form']   = $this->getForm($oContato, $oRequest);
        $aDados['msg']    = $sMsg;
        return $this->render('Pessoa/form.html.twig', $aDados);
    }

    /**
     * Rota para Editar os dados da pessoa.
     * @param Integer $id
     * @param \Symfony\Component\HttpFoundation\Request $oRequest
     * @param \Doctrine\ORM\EntityManagerInterface      $oEntity
     * @param \App\Repository\ContatoRepository         $oRepositorioContato
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editar($id, Request $oRequest, EntityManagerInterface $oEntity, ContatoRepository $oRepositorioContato) { 
        $sMsg = '';
        $oContato = $oRepositorioContato->find($id);

        if($this->executaAlteracao($oContato, $oRequest, $oEntity)) {
            $sMsg = 'Pessoa editada com sucesso!';
        }

        $aDados['titulo'] = 'Adicionar novo contato';
        $aDados['form']   = $this->getForm($oContato, $oRequest);
        $aDados['msg']    = $sMsg;
        return $this->render('Pessoa/form.html.twig', $aDados);
    }

    /**
     * Rota para exclusão da pessoa.
     * @param Integer $id
     * @param \App\Repository\ContatoRepository $oRepositorioContato
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function excluir($id, EntityManagerInterface $oEntity, ContatoRepository $oRepositorioContato) {
        $oEntity->remove($oRepositorioContato->find($id));
        $oEntity->flush();
        return $this->redirectToRoute('consultaContatos');
    }

    /**
     * Executa a inclusão de um novo contato.
     */
    private function executaInclusao($oContato, $oRequest, $oEntity) {
        $bInseriu = false;
        if($this->getForm($oContato, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->persist($oContato);
            $oEntity->flush();
            $bInseriu = true;
        }
        return $bInseriu;
    }

    /**
     * Executa a alteração do contato.
     */
    private function executaAlteracao($oContato, $oRequest, $oEntity) {
        $bAlterou = false;
        if($this->getForm($oContato, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->flush();
            $bAlterou = true;
        }
        return $bAlterou;
    }

    /**
     * Retorna o formulário.
     * @param Contato
     * @param Symfony\Component\HttpFoundation\Request
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getForm($oContato, $oRequest) {
        return $this->Form ?: $this->createFormPessoa($oContato, $oRequest);
    }

    /**
     * Cria o formulário de Contato.
     */
    private function createFormPessoa($oContato, $oRequest) {
        $this->Form = $this->createForm(ContatoType::class, $oContato);
        $this->Form->handleRequest($oRequest);
        return $this->Form;
    }


}