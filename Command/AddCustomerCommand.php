<?php

namespace MobileCart\MailChimpBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MobileCart\CoreBundle\Constants\EntityConstants;

class AddCustomerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cart:mailchimp:add:customer')
            ->setDescription('Add Customer to MailChimp list')
            ->addArgument('email', InputArgument::REQUIRED, 'Email Address')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'First Name')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Last Name')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command adds a Customer to a MailChimp list:

<info>php %command.full_name%</info>

EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $firstname = '';
        $lastname = '';
        if ($input->getArgument('firstname')) {
            $firstname = $input->getArgument('firstname');
            $lastname = $input->getArgument('lastname');
        }

        $response = $this->getContainer()->get('cart.mailchimp')->addMember($email, $firstname, $lastname);
        $output->writeln(print_r($response, 1));
    }
}
