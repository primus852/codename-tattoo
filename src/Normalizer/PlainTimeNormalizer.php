<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as BaseDateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlainTimeNormalizer implements NormalizerInterface, NormalizableInterface, DenormalizerInterface, DenormalizableInterface
{
    private BaseDateTimeNormalizer $dateTimeNormalizer;

    private array $keysToApplyNormalizer = [
        'timeFrom',
        'timeTo',
    ];

    public function __construct(BaseDateTimeNormalizer $dateTimeNormalizer)
    {
        $this->dateTimeNormalizer = $dateTimeNormalizer;
    }

    public function normalize($object, string $format = null, array $context = []): float|array|bool|int|string
    {
        $attribute = $context['api_attribute'] ?? null;

        if ($object instanceof \DateTimeInterface && in_array($attribute, $this->keysToApplyNormalizer, true) && isset($context['groups']) && in_array('timeonly', $context['groups'], true)) {
            return $object->format('H:i:s');
        }

        return $this->dateTimeNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \DateTimeInterface;
    }

    public function denormalize($data, string|int|bool|array|float $type, string $format = null, array $context = [])
    {
        return $this->dateTimeNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $this->dateTimeNormalizer->supportsDenormalization($data, $type, $format, $context);
    }
}
