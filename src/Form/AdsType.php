<?php

namespace App\Form;

use App\Entity\Ads;
use App\Entity\Category;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Select;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdsType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $response = json_decode(file_get_contents('https://geo.api.gouv.fr/regions?fields=nom'),true);

        $mySelect = array_map( function ($element) {

            return $element['nom'];

        } , $response );



        $builder
            ->add('title' ,TextType::class)
            ->add('description',TextareaType::class)
            ->add('creationDate',DateType::class,[
                "widget" => "single_text"
            ])
            ->add('region',ChoiceType::class , [
                'choices' => $mySelect,
                'choice_label' => function ($choiceValue, $key, $value) {
                    if ($value == $choiceValue) {
                        return $value ;
                    }
                    return strtoupper($key);
                }])
            ->add('category',EntityType::class , [
                'class'=> Category::class,
                'choice_label' => 'name',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
