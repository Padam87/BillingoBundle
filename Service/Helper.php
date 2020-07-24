<?php

namespace Padam87\BillingoBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Helper
{
    const TYPE_DRAFT = 0;
    const TYPE_PROFORMA = 1;
    const TYPE_NORMAL = 3;

    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function createClient(array $data): array
    {
        return $this->api->request('POST', 'clients', $data)['data'];
    }

    public function createInvoice(array $data): array
    {
        return $this->api->request('POST', 'invoices', $data)['data'];
    }

    public function downloadInvoice($id, ?string $filename = null): UploadedFile
    {
        if ($filename === null) {
            $filename = (string) $id;
        }

        $name = sprintf('%s.pdf', $filename);
        $path = sprintf('%s%s%s', sys_get_temp_dir(), DIRECTORY_SEPARATOR, $name);

        $file = (string) $this->api->request('GET', 'invoices/' . $id . '/download', [], true);

        file_put_contents($path, $file);

        return new UploadedFile($path, $name, null, null, true);
    }

    public function cancelInvoice($id): string
    {
        return $this->api->request('GET', "invoices/$id/cancel")['data']['id'];
    }

    public function payInvoice($id, float $amount, int $paymentMethod, \DateTime $date = null): bool
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        return $this->api->request(
            'POST',
            "invoices/$id/pay",
            [
                'date' => $date->format('Y-m-d'),
                'amount' => $amount,
                'payment_method' => $paymentMethod,
            ]
        )['success'];
    }

    public function getPaymentMethods(): array
    {
        return $this->api->request('GET', 'payment_methods/hu')['data'];
    }

    public function getBankAccounts(): ?array
    {
        return $this->api->request('GET', 'bank_accounts')['data'];
    }
}
