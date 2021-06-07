<?php

namespace App\Form;

use App\Entity\Epreuve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AjoutEpreuveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeModule', IntegerType::class)
            ->add('nomModule', TextType::class)
            ->add('classe', TextType::class)
            ->add('nombreEleve', IntegerType::class)
            ->add('sujet', FileType::class)
            ->add('duree', TimeType::class, [
                'html5' => false,
            ])
            ->add('dateEpreuve', DateType::class,[
                'html5' => false,
            ])
            

            ->add('ajoutEpreuve', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Epreuve::class,
        ]);
    }
}
