<?php
declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\File;

class ImageSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::postRemove];
    }

    /**
     * @param LifecycleEventArgs $args
     * @todo validate?
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Image) {
        }

//        dump*$
    }

    public function postRemove($args)
    {
        dump($args);
        die;
    }

    private function upload(File $file): File
    {
        // noop
    }

}
