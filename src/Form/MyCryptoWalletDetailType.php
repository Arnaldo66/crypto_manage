<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use App\Entity\MyCryptoWalletDetail;

class MyCryptoWalletDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', TextType::class)
            ->add('currency', EntityType::class, array(
              'class' => 'App:Currency',
              'choice_label' => 'nameValue',
              'query_builder' => function (EntityRepository $er) {
                  return $er->createQueryBuilder('c')
                        ->orderBy('c.rank', 'ASC');
              },
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MyCryptoWalletDetail::class,
        ));
    }
}
