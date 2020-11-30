<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\AddressOrder;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressOrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('infoAddress', EntityType::class, [
                'class' => Address::class,
                'label'=> 'sélectionner une adresse de livraison',
                'query_builder' => function (AddressRepository $er) {
                    return $er->createQueryBuilder('u')
                            ->where('u.user = :user')
                            // ->setParameter('user', 2)  2 correspond a l'id utilisateur connecté
                            ->orderBy('u.titre', 'ASC');
                    },
                'choice_label' => 'titre'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddressOrder::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
