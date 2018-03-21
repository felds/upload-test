<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Traversable;

class ImageDataMapper implements DataMapperInterface
{
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

        if ($file instanceof File) {
            $data = new Image($file);
        }
    }
}
