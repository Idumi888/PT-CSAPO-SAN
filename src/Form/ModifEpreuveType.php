<?php

namespace App\Form;

use App\Entity\Epreuve;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ModifEpreuveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('codeModule', IntegerType::class)
            ->add('nomModule', TextType::class)
            ->add('classe', TextType::class)
            ->add('nombreEleve', IntegerType::class)
            ->add('sujet', FileType::class, array('data_class' => null))
            ->add('duree', IntegerType::class)
            ->add('dateEpreuve', DateTimeType::class)
            ->add('utilisateurs', EntityType::class,[
                'class'=>Utilisateur::class,
                'choice_label'=>
                function($utilisateurs) {
                    $role =$utilisateurs->getRoles();
                    return $utilisateurs->getNom() . " - " . $utilisateurs->getPrenom() . " - " . $role[0]  ;}, 
            
                'multiple'=> true,])
            ->add('modifierEpreuve', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Epreuve::class,
        ]);
    }
}
