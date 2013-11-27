<?php

namespace CatMS\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AdminsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('catms:admins')
            ->setDescription('Show admins list')
            ->addOption('roles', null, InputOption::VALUE_OPTIONAL, 'Roles that you want to list', 'all')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roles = $input->getOption('roles');

        switch ($roles) {
            case 'all':
                $admins = $this->getAdmins('all');
                break;
            case 'admin':
                $admins = $this->getAdmins('admin');
                break;
            case 'developer':
                $admins = $this->getAdmins('developer');
                break;
        }

        $output->writeln(' ');

        if (count($admins) > 0) {
            foreach ($admins as $i => $admin) {
                $output->writeln($i + 1 . ': ' .$admin->getUsername() . ' ' . $admin->getRoles()[0]);
            }
        } else {
            $output->writeln('No users with apropriate roles into database.');
        }

    }

    private function getAdmins($roles)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        return $em->getRepository('CatMSAuthBundle:User')->findAllAdmins($roles);
    }
}