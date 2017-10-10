<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Entity\TradingWallet;

class TradingWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('amount', ChoiceType::class, array(
              'mapped' => false,
              'choices'  => array(
                '100 €' => 1,
                '1000 €' => 2,
                '5000 €' => 3,
                '10000 €' => 4,
                '15000 €' => 5,
              )
            ))
            ->add('public', ChoiceType::class, array(
              'expanded' => true,
              'choices'  => array(
                'Public' => true,
                'Private' => false,
              )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TradingWallet::class,
        ));
    }
}
