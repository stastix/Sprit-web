<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class InvoiceGeneratorService
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new \GuzzleHttp\Client();
    }

    public function generateInvoice(array $invoiceData): ?File
    {
        $apiUrl = 'https://invoice-generator.com';

        $response = $this->httpClient->post($apiUrl, [
            'json' => $invoiceData,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $pdfContent = $response->getBody()->getContents();
            $pdfFilePath = $this->savePdfToFile($pdfContent);

            return new File($pdfFilePath);
        }

        return null;
    }

    private function savePdfToFile(string $pdfContent): string
    {
        $fileName = 'invoice_' . uniqid() . '.pdf';
        $pdfFilePath = 'path/to/your/pdf/directory/' . $fileName;

        file_put_contents($pdfFilePath, $pdfContent);

        return $pdfFilePath;
    }
}
