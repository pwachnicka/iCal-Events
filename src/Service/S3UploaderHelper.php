<?php

namespace App\Service;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class S3UploaderHelper
{

    public function __construct(
        private S3Client $s3client,
        private LoggerInterface $logger,
        private ContainerBagInterface $params,
    ) {
    }

    public function uploadFile(string $fileName): void
    {
        $this->logger->info("Start uploading to S3 Bucket.");
        try {
            $this->s3client->putObject([
                'Bucket' => $this->params->get('app.bucket_name'),
                'Key'    => basename($fileName),
                'SourceFile' => $fileName
            ]);
            $this->logger->info("File uploaded successfully. File name {fileName}.", [
                'fileName' => $fileName
            ]);
        } catch (AwsException $e) {
            $this->logger->error('An error occurred during uploading to S3: {error}', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
