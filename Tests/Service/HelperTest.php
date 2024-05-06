<?php

namespace Padam87\BillingoBundle\Tests\Service;

use Padam87\BillingoBundle\Exception\DocumentNotAvailableException;
use Padam87\BillingoBundle\Service\Api;
use Padam87\BillingoBundle\Service\Helper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HelperTest extends TestCase
{
    protected function defaults(array $data = []): array
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->expects($this->once())->method('toArray')->willReturn($data);

        $api = $this->getMockBuilder(Api::class)->disableOriginalConstructor()->getMock();
        $api->expects($this->once())->method('request')->willReturn($response);

        $helper = new Helper($api);

        return [$data, $response, $api, $helper];
    }

    /**
     * @test
     */
    public function createInvoice(): void
    {
        [$data, $response, $api, $helper] = $this->defaults();

        $this->assertEquals($data, $helper->createInvoice([]));
    }

    /**
     * @test
     */
    public function cancelInvoice(): void
    {
        [$data, $response, $api, $helper] = $this->defaults();

        $this->assertEquals($data, $helper->cancelInvoice(1));
    }

    /**
     * @test
     */
    public function payInvoice(): void
    {
        [$data, $response, $api, $helper] = $this->defaults();

        $this->assertEquals($data, $helper->payInvoice(1, 100, 1));
    }

    /**
     * @test
     */
    public function downloadInvoiceException(): void
    {
        $this->expectException(DocumentNotAvailableException::class);

        [$data, $response, $api, $helper] = $this->defaults(['error' => 'Document not ready.']);

        $response->expects($this->once())->method('getHeaders')->willReturn([]);

        $helper->downloadInvoice(1);
    }

    /**
     * @test
     */
    public function downloadInvoice(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->expects($this->once())->method('getHeaders')->willReturn(['content-type' => ['application/pdf']]);

        $api = $this->getMockBuilder(Api::class)->disableOriginalConstructor()->getMock();
        $api->expects($this->once())->method('request')->willReturn($response);

        $helper = new Helper($api);
        $file = $helper->downloadInvoice(1);

        $this->assertInstanceOf(UploadedFile::class, $file);
        $this->assertEquals('1.pdf', $file->getClientOriginalName());
    }

    /**
     * @test
     */
    public function createClient(): void
    {
        [$data, $response, $api, $helper] = $this->defaults();

        $this->assertEquals($data, $helper->createClient([]));
    }

    /**
     * @test
     */
    public function getBankAccounts(): void
    {
        [$data, $response, $api, $helper] = $this->defaults(['data' => []]);

        $this->assertEquals([], $helper->getBankAccounts());
    }
}
