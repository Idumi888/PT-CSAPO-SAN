<?php

namespace App\Form;

use App\Entity\Passe;
use App\Entity\Epreuve;
use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AjoutElevePasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note')
            
            ->add('epreuve', EntityType::class,[
                'class'=>Epreuve::class,
                'choice_label'=>'nom_module',
                
            ])
            ->add('eleve', EntityType::class,[
                'class'=>Eleve::class,
                'choice_label'=>'nom',
                
            ])
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Passe::class,
        ]);
    }
}
