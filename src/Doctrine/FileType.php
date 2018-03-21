<?php
declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\HttpFoundation\File\File;

class FileType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new File($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof File) {
            return $value->getRealPath();
        }

        return null;
    }

    public function getName()
    {
        return "file";
    }
}
