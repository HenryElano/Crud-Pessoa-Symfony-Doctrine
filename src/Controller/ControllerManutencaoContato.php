<?php
namespace App\Controller;

use App\Entity\Contato;
use App\Form\ContatoType;
use App\Repository\ContatoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerManutencaoContato extends AbstractController {

    /** @var FormInterface $Form */
    private $Form;
    
    /**
     * Método da Rota de Inserir nova pessoa.
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     * @return Response;
     */
    public function inserir(Request $oRequest, EntityManagerInterface $oEntity) : Response {
        $oContato = new Contato();
        $this->executaInclusao($oContato, $oRequest, $oEntity);
        return $this->render('Contato/form.html.twig', ['titulo' => 'Adicionar um novo contato', 'form' => $this->getForm($oContato, $oRequest)]);
    }

    /**
     * Rota para Editar os dados da pessoa.
     * @param Integer $id
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     * @param ContatoRepository $oRepositorioContato
     * @return Response
     */
    public function editar($id, Request $oRequest, EntityManagerInterface $oEntity, ContatoRepository $oRepositorioContato) { 
        $oContato = $oRepositorioContato->find($id);
        $this->executaAlteracao($oContato, $oRequest, $oEntity);
        return $this->render('Contato/form.html.twig', ['titulo' => 'Alterar contato', 'form' => $this->getForm($oContato, $oRequest)]);
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
     * @param Contato $oContato
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     */
    private function executaInclusao(Contato $oContato, Request $oRequest, EntityManagerInterface $oEntity) {
        if($this->getForm($oContato, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->persist($oContato);
            $oEntity->flush();
            $this->addFlash('sucesso', 'Registro inserido com sucesso!');
        }
    }

    /**
     * Executa a alteração do contato.
     * @param Contato $oContato
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     */
    private function executaAlteracao(Contato $oContato, Request $oRequest, EntityManagerInterface $oEntity) {
        if($this->getForm($oContato, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            $oEntity->flush();
            $this->addFlash('sucesso', 'Registro alterado com sucesso!');
        }
    }

    /**
     * Retorna o formulário.
     * @param Contato $oContato
     * @param Request $oRequest
     * @return FormInterface
     */
    private function getForm(Contato $oContato, Request $oRequest) : FormInterface  {
        return $this->Form ?: $this->createFormPessoa($oContato, $oRequest);
    }

    /**
     * Cria o formulário de Contato.
     * @param Contato $oContato
     * @param Request $oRequest
     * @return FormInterface
     */
    private function createFormPessoa(Contato $oContato, Request $oRequest) {
        $this->Form = $this->createForm(ContatoType::class, $oContato);
        $this->Form->handleRequest($oRequest);
        return $this->Form;
    }


}