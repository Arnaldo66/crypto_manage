<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TradingOrder;

class TradingOrderNextStepType extends AbstractType
{

    private $wallet;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->wallet = $options['wallet'];
        $builder
          ->add('orderMethod', EntityType::class, array(
            'class' => 'App:OrderMethod',
            'choice_label' => 'name',
            'expanded' => true
          ))
          ->add('amount',TextType::class)
          ->add('price',TextType::class)
          ->add('total',TextType::class)
          ->add('tradingWallet', EntityType::class, array(
            'class' => 'App:TradingWallet',
            'attr' => array('readonly' => true),
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('w')
                  ->where('w.id = :current_wallet')
                  ->setParameter('current_wallet', $this->wallet);
            }
          ))
          ->add('orderAction', EntityType::class, array(
            'class' => 'App:OrderAction',
            'choice_label' => 'name',
            'expanded' => true
          ))
          ->add('currency', EntityType::class, array(
            'class' => 'App:Currency',
            'choice_label' => 'name',
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TradingOrder::class,
            'wallet' => null
        ));
    }
}
