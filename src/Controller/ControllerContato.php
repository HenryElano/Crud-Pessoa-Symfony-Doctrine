<?php
namespace App\Controller;

use App\Entity\Contato;
use App\Form\ContatoType;
use App\Repository\PessoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ControllerContato extends AbstractController {

    public function index(EntityManagerInterface $oEntityManager, PessoaRepository $oRepositorioPessoa) {
        $oPessoa = $oRepositorioPessoa->find(1);
        $oContato = new Contato();
        $oContato->setTipo(1);
        $oContato->setDescricao('lucas.silva@gmail.com');
        $oContato->setPessoa($oPessoa);

        $sMsg = '';
        try{
            $oEntityManager->persist($oContato);
            $oEntityManager->flush(); 
            $sMsg = 'Contato inserido com sucesso!';
        }catch(Exception $e) {
            $sMsg = 'Erro ao Inserir o contato'. ' '. $e;
        }
        return new Response('<h1>'.$sMsg.'</h1>');
    }

    public function salvar() {
        $aDados['titulo'] = 'Adicionar um Novo Contato';
        $aDados['form']   = $this->createForm(ContatoType::class);
        return $this->render('Contato/form.html.twig', $aDados);
    }


}