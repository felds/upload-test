<?php
declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ImageSubscriber constructor.
     * @param string $uploadDir
     * @param LoggerInterface $logger
     */
    public function __construct(string $uploadDir, LoggerInterface $logger)
    {
        $this->uploadDir = $uploadDir;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::postRemove];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Image) {
            $file = $entity->getFile();

            if ($file instanceof UploadedFile) {
                $entity->setFile($this->upload($file));
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Image) {
            $path = $entity->getFile()->getRealPath();

            $success = @unlink($path);

            if (!$success) {
                $this->logger->warning("The file {$path} couldn't be deleted.");
            }
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return File
     */
    private function upload(UploadedFile $uploadedFile): File
    {
        $targetDir = $this->uploadDir;
        $targetFile = sprintf('%s.%s', uniqid('', true), $uploadedFile->guessExtension());

        return $uploadedFile->move($targetDir, $targetFile);
    }

}
