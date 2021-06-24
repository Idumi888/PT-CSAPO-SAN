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
            
            
            ->add('epreuve', EntityType::class,[
                'class'=>Epreuve::class,
                'choice_label'=>function($epreuve) {
                    return $epreuve->getCodeModule() . " - " . $epreuve->getNomModule();},
            ])
            ->add('eleve', EntityType::class,[
                'class'=>Eleve::class,
                'choice_label'=>
                function($eleve) {
                    return $eleve->getNom() . " - " . $eleve->getPrenom();}, 
                
            ])
            ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Passe::class,
        ]);
    }
}
