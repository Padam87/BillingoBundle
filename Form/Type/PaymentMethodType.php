<?php

namespace Padam87\BillingoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $paymentMethods = [
            'aruhitel',
            'bankcard',
            'barion',
            'barter',
            'cash',
            'cash_on_delivery',
            'coupon',
            'elore_utalas',
            'ep_kartya',
            'kompenzacio',
            'levonas',
            'online_bankcard',
            'paylike',
            'payoneer',
            'paypal',
            'paypal_utolag',
            'payu',
            'pick_pack_pont',
            'postai_csekk',
            'postautalvany',
            'skrill',
            'szep_card',
            'transferwise',
            'upwork',
            'utalvany',
            'valto',
            'wire_transfer',
        ];

        $resolver
            ->setDefaults(
                [
                    'choice_translation_domain' => 'padam87_billingo_payment_method',
                    'choices' => array_combine($paymentMethods, $paymentMethods),
                ]
            )
        ;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
