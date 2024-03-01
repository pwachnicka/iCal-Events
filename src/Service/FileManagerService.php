<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileManagerService
{

    private const FILE_PATH = 'var';
    private const FILE_NAME = 'eventDetails.txt';

    public function __construct(
        private Filesystem $filesystem,
        private S3UploaderHelper $s3UploaderHelper,
        private LoggerInterface $logger,
        private ContainerBagInterface $params,
    ) {
    }

    public function save(string $eventDetails): void
    {
        $fileName = $this->getFileName();

        $this->logger->info("Start saving the file with event details content.");
        try {
            $this->filesystem->dumpFile($fileName, $eventDetails);
            $this->logger->info("File saved successfully. File name {fileName}.", [
                'fileName' => $fileName
            ]);
        } catch (Exception $e) {
            $this->logger->error('An error occurred during saving file with event details: {error}', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $this->s3UploaderHelper->uploadFile($fileName);
    }

    private function getFileName(): string
    {
        return $this->params->get('kernel.project_dir') . DIRECTORY_SEPARATOR . self::FILE_PATH . DIRECTORY_SEPARATOR . time() . '_' . self::FILE_NAME;
    }
}
