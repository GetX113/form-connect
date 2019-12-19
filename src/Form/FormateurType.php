<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite')
            ->add('nom')
            ->add('prenom')
            ->add('adresse1')
            ->add('adresse2')
            ->add('code_postal')
            ->add('ville')
            ->add('email')
            ->add('password')
            ->add('photo')
            ->add('rank')
            ->add('vote')
            ->add('last_login')
            ->add('active_admin')
            ->add('active_email')
            ->add('date_disponibilite')
            ->add('cv')
            ->add('fix')
            ->add('mobile')
            ->add('frais_repas')
            ->add('frais_hotel')
            ->add('frais_deplacement')
            ->add('information')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formateur::class,
        ]);
    }
}
