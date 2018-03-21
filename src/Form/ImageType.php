<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    /**
     * @var ImageDataMapper
     */
    private $dataMapper;

    /**
     * ImageType constructor.
     * @param ImageDataMapper $dataMapper
     */
    public function __construct(ImageDataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, [
            'mapped' => false,
            'required' => false,
        ]);

        $builder->setDataMapper($this->dataMapper);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'empty_data' => null,
        ]);
    }
}
