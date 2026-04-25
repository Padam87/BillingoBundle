<?php

namespace Padam87\BillingoBundle\Form\Type;

use Padam87\BillingoBundle\Service\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountType extends AbstractType
{
    public function __construct(private Helper $billingo)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $accounts = (array) $this->billingo->getBankAccounts();

        $choices = [];
        foreach ($accounts as $account) {
            $name = sprintf('[%s] %s', $account['name'], $account['account_number']);

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

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
