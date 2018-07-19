<?php

namespace App\Form;

use App\Entity\Ads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' ,TextType::class)
            ->add('description',TextareaType::class)
            ->add('creationDate',DateType::class,[
                "widget" => "single_text"
            ])
            ->add('region',TextType::class)
            ->add('category',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
