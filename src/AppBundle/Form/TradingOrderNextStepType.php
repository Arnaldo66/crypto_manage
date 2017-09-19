<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\TradingOrder;

class TradingOrderNextStepType extends AbstractType
{

    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        $builder
          ->add('orderMethod', EntityType::class, array(
            'class' => 'AppBundle:OrderMethod',
            'choice_label' => 'name',
            'expanded' => true
          ))
          ->add('amount',TextType::class)
          ->add('price',TextType::class)
          ->add('total',TextType::class)
          ->add('tradingWallet', EntityType::class, array(
            'class' => 'AppBundle:TradingWallet',
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('u')
                  ->where('u.user = :current_user')
                  ->setParameter('current_user', $this->user)
                  ->orderBy('u.name', 'ASC');
            }
          ))
          ->add('orderAction', EntityType::class, array(
            'class' => 'AppBundle:OrderAction',
            'choice_label' => 'name',
            'expanded' => true
          ))
          ->add('currency', EntityType::class, array(
            'class' => 'AppBundle:Currency',
            'choice_label' => 'name',
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TradingOrder::class,
            'user' => null
        ));
    }
}
