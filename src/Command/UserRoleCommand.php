<?php

namespace App\Command;

use App\Entity\Currency;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserRoleCommand extends Command
{
    /**
     * @var ObjectManager
     */
    private $manager;
    protected static $defaultName = 'app:user-role-currency-image';

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Store role user for each user exept admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $users = $this->manager->getRepository(User::class)->findAll();
        foreach ($users as $user){
            if($user->getEmail() !== 'a.parseval@gmail.com') {
                $user->setRoles(['ROLE_USER']);
            }
        }
        $this->manager->flush();

        $currencies = $this->manager->getRepository(Currency::class)->findAll();
        foreach ($currencies as $currency) {
            $currency->setImage(null);
        }
        $this->manager->flush();
        $style->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
