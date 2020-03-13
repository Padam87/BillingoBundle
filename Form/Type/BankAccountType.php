<?php

namespace Padam87\BillingoBundle\Form\Type;

use Padam87\BillingoBundle\Service\Helper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountType extends ChoiceType
{
    private $billingo;

    public function __construct(Helper $billingo)
    {
        $this->billingo = $billingo;

        parent::__construct();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $accounts = (array) $this->billingo->getBankAccounts();

        $choices = [];
        foreach ($accounts as $id => $account) {
            $name = sprintf('[%s] %s', $account['attributes']['bank_name'], $account['attributes']['account_no']);

            $choices[$name] = $account['id'];
        }

        $resolver
            ->setDefaults(
                [
                    'choice_translation_domain' => false,
                    'choices' => $choices,
                ]
            )
        ;
    }
}
