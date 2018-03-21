<?php
declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    private $uploadDir;

    /**
     * ImageSubscriber constructor.
     * @param string $uploadDir
     */
    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
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

    public function postRemove($args)
    {
        dump($args);
        die;
    }

    private function upload(UploadedFile $uploadedFile): File
    {
        $targetDir = $this->uploadDir;
        $targetFile = sprintf('%s.%s', uniqid('', true), $uploadedFile->guessExtension());

        return $uploadedFile->move($targetDir, $targetFile);
    }

}
