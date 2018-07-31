<?php

namespace Padam87\BillingoBundle\Form\Type;

use Padam87\BillingoBundle\Service\Helper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends ChoiceType
{
    private $billingo;
    private $requestStack;

    public function __construct(Helper $billingo, RequestStack $requestStack)
    {
        $this->billingo = $billingo;
        $this->requestStack = $requestStack;

        parent::__construct();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $request = $this->requestStack->getCurrentRequest();
        $locale = $request->getLocale();

        if (!in_array($locale, ['hu', 'en', 'de'])) {
            $locale = 'en';
        }

        $paymentMethods = $this->billingo->getPaymentMethods($locale);

        $choices = [];
        foreach ($paymentMethods as $paymentMethod) {
            $choices[$paymentMethod['attributes']['name']] = $paymentMethod['id'];
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
