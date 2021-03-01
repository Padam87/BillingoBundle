<?php

namespace Padam87\BillingoBundle\Service;

use Padam87\BillingoBundle\Exception\BillingoException;
use Padam87\BillingoBundle\Exception\DocumentNotAvailableException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Helper
{
    const TYPE_DRAFT = 0;
    const TYPE_PROFORMA = 1;
    const TYPE_NORMAL = 3;

    private Api $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function responseToArray(ResponseInterface $response): array
    {
        try {
            $array = $response->toArray();
            $exceptionMessage = null;
        } catch (\Exception $exception) {
            $array = $response->toArray(false);
            $exceptionMessage = $exception->getMessage();
        }

        if (array_key_exists('error', $array)) {
            if ('' === $message = $array['error']['message']) {
                $message = $exceptionMessage;
            }

            throw new BillingoException($message, $response->getStatusCode());
        }

        if (array_key_exists('errors', $array)) {
            if ('' === $message = $array['message']) {
                $message = $exceptionMessage;
            }

            throw new BillingoException($message, $response->getStatusCode(), null, $array['errors']);
        }

        return $array;
    }

    public function createClient(array $data): array
    {
        $response = $this->api->request('POST', 'partners', $data);

        return $this->responseToArray($response);
    }

    public function createInvoice(array $data): array
    {
        $response = $this->api->request('POST', 'documents', $data);

        return $this->responseToArray($response);
    }

    public function downloadInvoice($id, ?string $filename = null): UploadedFile
    {
        if ($filename === null) {
            $filename = (string) $id;
        }

        $response = $this->api->request('GET', 'documents/' . $id . '/download');
        $headers = $response->getHeaders();

        if (!array_key_exists('content-type', $headers) || !in_array('application/pdf', $headers['content-type'])) {
            $data = $response->toArray(false);

            throw new DocumentNotAvailableException($data['error']);
        }

        $name = sprintf('%s.pdf', $filename);
        $path = sprintf('%s%s%s', sys_get_temp_dir(), DIRECTORY_SEPARATOR, $name);

        file_put_contents($path, $response->getContent());

        return new UploadedFile($path, $name, null, null, true);
    }

    public function cancelInvoice($id): array
    {
        $response = $this->api->request('POST', "documents/$id/cancel");

        return $this->responseToArray($response);
    }

    public function payInvoice($id, float $amount, int $paymentMethod, \DateTime $date = null): array
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        $response = $this->api->request(
            'POST',
            "invoices/$id/pay",
            [
                'date' => $date->format('Y-m-d'),
                'amount' => $amount,
                'payment_method' => $paymentMethod,
            ]
        );

        return $this->responseToArray($response);
    }

    public function getBankAccounts(): ?array
    {
        $response = $this->api->request('GET', 'bank-accounts');

        return $this->responseToArray($response)['data'];
    }

    public function getDocumentBlocks(): ?array
    {
        $response = $this->api->request('GET', 'document-blocks');

        return $this->responseToArray($response)['data'];
    }
}
