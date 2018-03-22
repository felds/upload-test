<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Traversable;

class ImageDataMapper implements DataMapperInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ImageDataMapper constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $data
     * @param FormInterface[]|Traversable $forms
     */
    public function mapDataToForms($data, $forms)
    {
        // noop
    }

    /**
     * @param FormInterface[]|Traversable $forms
     * @param mixed $data
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        $file = $forms['file']->getData();

        // delete previous image if the file has changed
        if ($data) {
            $this->em->remove($data);
        }

        // create a new entity if a file was uploaded
        if ($file instanceof File) {
            $data = new Image($file);
        }
    }
}
