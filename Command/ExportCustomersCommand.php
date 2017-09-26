<?php

namespace MobileCart\MailChimpBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MobileCart\CoreBundle\Constants\EntityConstants;

class ExportCustomersCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cart:mailchimp:export:customers')
            ->setDescription('Export Customers for MailChimp')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command exports Customers for MailChimp:

<info>php %command.full_name%</info>

EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("email,firstname,lastname");
        $offset = 0;
        $limit = 100;
        $customers = $this->getContainer()->get('cart.entity')->findBy(EntityConstants::CUSTOMER, [], null, $limit, $offset);
        while($customers) {
            foreach($customers as $customer) {
                if (!is_int(strpos($customer->getEmail(), '@'))) {
                    continue;
                }
                $output->writeln("{$customer->getEmail()},{$customer->getFirstName()},{$customer->getLastName()}");
            }
            $offset += $limit;
            $customers = $this->getContainer()->get('cart.entity')->findBy(EntityConstants::CUSTOMER, [], null, $limit, $offset);
        }
    }
}
