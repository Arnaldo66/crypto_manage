<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\TradingOrder;

class TradingOrderFirstStepType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currency', EntityType::class, array(
              'class' => 'AppBundle:Currency',
              'choice_label' => 'name',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TradingOrder::class
        ));
    }
}
