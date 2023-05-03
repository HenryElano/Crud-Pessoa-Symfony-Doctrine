<?php
namespace App\Controller;

use App\Entity\Pessoa;
use App\Form\PessoaType;
use App\Repository\PessoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerManutencaoPessoa extends AbstractController {

    /** @var FormInterface $Form */
    private $Form;

    /**
     * Rota para Inserir novas pessoas.
     * @param Request $oRequest
     * @param EntityManagerInterface      $oEntity
     * @return Response
     */
    public function inserir(Request $oRequest, EntityManagerInterface $oEntity) : Response {
        $oPessoa = new Pessoa();
        $this->executaInclusao($oPessoa, $oRequest, $oEntity);
        return $this->render('Pessoa/form.html.twig', ['titulo' => 'Adicionar Nova Pessoa', 'form' => $this->getForm($oPessoa, $oRequest)]);
    }

    /**
     * Rota para Editar os dados da pessoa.
     * @param Integer $id
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     * @param PessoaRepository $oRepositorioPessoa
     * @return Response
     */
    public function editar($id, Request $oRequest, EntityManagerInterface $oEntity, PessoaRepository $oRepositorioPessoa) { 
        $oPessoa = $oRepositorioPessoa->find($id);
        $this->executaAlteracao($oPessoa, $oRequest, $oEntity);
        return $this->render('Pessoa/form.html.twig', ['titulo' =>'Editar dados da pessoa', 'form' => $this->getForm($oPessoa, $oRequest)]);
    }

    /**
     * Rota para exclusão da pessoa.
     * @param Integer $id
     * @param PessoaRepository $oRepositorioPessoa
     * @return RedirectResponse
     */
    public function excluir($id, EntityManagerInterface $oEntity, PessoaRepository $oRepositorioPessoa) :RedirectResponse {
        $oEntity->remove($oRepositorioPessoa->find($id));
        $oEntity->flush();
        return $this->redirectToRoute('consultaPessoas');
    }

    /**
     * Executa a inclusão da pessoa.
     * @param Pessoa $oPessoa
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     * @return boolean
     */
    private function executaInclusao(Pessoa $oPessoa, Request $oRequest, EntityManagerInterface $oEntity) {
        if($this->getForm($oPessoa, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            if($this->doValidaCpfValidoInclusao($oPessoa, $oEntity)) {
                $oEntity->persist($oPessoa);
                $oEntity->flush();
                $this->addFlash('sucesso', 'Registro incluído com sucesso!');
            }else{
                $this->addFlash('alerta', "Já existe uma pessoa cadastrada com este CPF: {$oPessoa->getCpf()}. Caso desejar continuar a inclusão, informe um novo CPF.");
            }
        }
    }

    /**
     * Executa a alteração da pessoa.
     * @param Pessoa $oPessoa
     * @param Request $oRequest
     * @param EntityManagerInterface $oEntity
     */
    private function executaAlteracao(Pessoa $oPessoa, Request $oRequest, EntityManagerInterface $oEntity) {
        if($this->getForm($oPessoa, $oRequest)->isSubmitted() && $this->Form->isValid()) {
            if($this->doValidaCpfValidoAlteracao($oPessoa, $oEntity)) {
                $oEntity->flush();
                $this->addFlash('sucesso', 'Registro alterado com sucesso!');
            }else{
                $this->addFlash('alerta', "Já existe uma pessoa cadastrada com este CPF: {$oPessoa->getCpf()}. Caso desejar continuar a alterção, informe um novo CPF.");
            }
        }
    }

    /**
     * Verifica se o CPF informado não está cadastrado
     * @param Pessoa $oPessoa
     * @param EntityManagerInterface $oEntity
     * @return boolean
     */
    private function doValidaCpfValidoInclusao(Pessoa $oPessoa, EntityManagerInterface $oEntity) {
        $xRetorno = $oEntity->getRepository(Pessoa::class)->findOneBy(['cpf' => $oPessoa->getCpf()]);
        return $xRetorno == null;
    }

    /**
     * Verifica se o CPF informado não está cadastrado
     * @param Pessoa $oPessoa
     * @param EntityManagerInterface $oEntity
     * @return boolean
     */
    private function doValidaCpfValidoAlteracao(Pessoa $oPessoa, EntityManagerInterface $oEntity) {
        $bValido = false;
        $xRetorno = $oEntity->getRepository(Pessoa::class)->findBy(['cpf' => $oPessoa->getCpf()]);
        if(is_array($xRetorno)) {
            foreach($xRetorno as $oRetorno) {
                if($oPessoa->getId() == $oRetorno->getId()) {
                    $bValido = true;
                }
            }
        }
        return $bValido;
    }
    
    /**
     * Retorna o formulário.
     * @param Pessoa $oPessoa
     * @param Request $oRequest
     * @return FormInterface
     */
    private function getForm(Pessoa $oPessoa, Request $oRequest) :FormInterface {
        return $this->Form ?: $this->createFormPessoa($oPessoa, $oRequest);
    }

    /**
     * Cria o formulário de Pessoa.
     * @param Pessoa $oPessoa
     * @param Request $oRequest
     * @return FormInterface
     */
    private function createFormPessoa($oPessoa, $oRequest) :FormInterface {
        $this->Form = $this->createForm(PessoaType::class, $oPessoa);
        $this->Form->handleRequest($oRequest);
        return $this->Form;
    }
}