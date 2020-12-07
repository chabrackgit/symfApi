<?php

namespace App\Form;

use App\Entity\Catalog;
use App\Entity\ArticleSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('info', TextType::class, [
                'label'=> false,
                'required'=> false,
                'attr' => [
                    'placeholder' => 'saisir votre recherche'
                ]
            ])
            ->add('infoCatalog', EntityType::class, [
                'class' => Catalog::class,
                'required' => false,
                'label'=> false,
                'choice_label' => 'reference'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
