<?php

namespace App\Form;

use App\Entity\Mappool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MappoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('thumbnail')
            ->add('follow')
            ->add('updated_at')
            ->add('created_at')
            ->add('poolSet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mappool::class,
        ]);
    }
}
