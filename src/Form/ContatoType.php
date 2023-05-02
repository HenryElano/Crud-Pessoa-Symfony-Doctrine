<?php

namespace App\Form;

use App\Entity\Pessoa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContatoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipo',ChoiceType::class, [ 'choices' => ['Telefone'  => '0', 'Email' => '1'],
                                                  'label'   => 'Tipo de contato: '
                                                ])
                ->add('descricao', TextType::class    , ['label' => 'Descrição do Contato: '])
                ->add('Pessoa', EntityType::class  , ['label' => 'Pessoa: ', 'class' => Pessoa::class, 'choice_label' => 'nome'])
                ->add('Salvar', SubmitType::class);
    }

}