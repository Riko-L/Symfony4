<?php

namespace App\Form;


use App\Entity\Category;

use App\Entity\Region;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('search' , TextType::class , [
                'required' => false
            ])
            ->add('region',EntityType::class , [
                'class'=> Region::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('category',EntityType::class , [
                'class'=> Category::class,
                'choice_label' => 'name',
                'required' => false
            ])

 ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>null,
        ]);
    }
}
