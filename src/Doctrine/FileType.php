<?php
declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileType extends StringType
{
    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|File
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            return new File($value);
        } else {
            return null;
        }
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|null|string
     * @throws RuntimeException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof UploadedFile) {
            throw new RuntimeException("UploadedFile objects cannot be persisted.");
        }

        if ($value instanceof File) {
            return $value->getRealPath();
        }

        return null;
    }
}
