<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use App\Entity\OrderAction;
use App\Entity\OrderMethod;
use App\Entity\OrderStatus;

class InitFillParamsTableCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('init:fill-params-table')
            ->setDescription('Launch first time when installing project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $this->fillOrderAction($em);
        $this->fillOrderMethod($em);
        $this->fillOrderStatus($em);

        $output->writeln('Params tables fill');
    }

    private function fillOrderAction($em)
    {
        $orders = $em->getRepository('App:OrderAction')->findAll();
        if (!count($orders)) {
            $order_one = new OrderAction;
            $this->fillEntity($em, $order_one, 'Achat');
            $order_two = new OrderAction;
            $this->fillEntity($em, $order_two, 'Vente');
        }
    }

    private function fillOrderMethod($em)
    {
        $orders = $em->getRepository('App:OrderMethod')->findAll();
        if (!count($orders)) {
            $order_one = new OrderMethod;
            $this->fillEntity($em, $order_one, 'Market');
            $order_two = new OrderMethod;
            $this->fillEntity($em, $order_two, 'Limit');
        }
    }

    private function fillOrderStatus($em)
    {
        $orders = $em->getRepository('App:OrderStatus')->findAll();
        if (!count($orders)) {
            $order_one = new OrderStatus;
            $this->fillEntity($em, $order_one, 'Validé');
            $order_two = new OrderStatus;
            $this->fillEntity($em, $order_two, 'En cours');
            $order_tree = new OrderStatus;
            $this->fillEntity($em, $order_tree, 'Annulé');
        }
    }

    private function fillEntity($em, $entity, $name)
    {
        $entity->setName($name);
        $em->persist($entity);
        $em->flush();
    }
}
