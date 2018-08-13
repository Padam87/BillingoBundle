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

    public function downloadInvoice($id, string $prefix = 'invoice'): UploadedFile
    {
        $name = sprintf('%s-%d.pdf', $prefix, $id);
        $path = sprintf('%s/%s', sys_get_temp_dir(), $name);
        $code = $this->api->request('GET', 'invoices/' . $id. '/code')['data']['code'];

        $fh = fopen($path, 'w');
        $options = [
            CURLOPT_FILE => $fh,
            CURLOPT_URL => 'https://www.billingo.hu/access/c:' . $code . '/download',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
        fclose($fh);

        return new UploadedFile($path, $name, null, null, null, true);
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
}
