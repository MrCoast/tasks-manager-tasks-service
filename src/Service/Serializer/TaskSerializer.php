<?php

namespace App\Service\Serializer;

use App\Service\Serializer\SerializerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class TaskSerializer implements SerializerInterface
{
    public function __construct()
    {
        $normalizers = [$this->getNormalizer()];
        $encoders = [new JsonEncoder()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize($data, string $format = 'json', array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function normalize($data, string $format = 'json', array $context = [])
    {
        return $this->serializer->normalize($data, $format, $context);
    }

    private function getDefaultContent(): array
    {
        $circularReferenceCallback = function ($object) {
            return $object->getId();
        };

        $dateCallback = function ($innerObject) {
            return $innerObject instanceof \DateTime
                ? $innerObject->format(\DateTime::ISO8601)
                : '';
        };

        $innerObjectTitleCallback = function ($innerObject) use (&$innerObjectTitleCallback) {
            if ($innerObject instanceof Collection) {
                return $innerObject->map(function ($collectionItem) use ($innerObjectTitleCallback) {
                    return $innerObjectTitleCallback($collectionItem);
                });
            }

            return method_exists($innerObject, 'getTitle')
                ? $innerObject->getTitle()
                : '';
        };

        return [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => $circularReferenceCallback,
            AbstractNormalizer::CALLBACKS => [
                'tags' => $innerObjectTitleCallback,
                'priority' => $innerObjectTitleCallback,
                'state' => $innerObjectTitleCallback,
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
            ],
        ];
    }

    private function getNormalizer(): NormalizerInterface
    {
        $classMetadataFactory = null;
        $nameConverter = null;
        $propertyTypeExtractor = null;
        $classDiscriminatorResolver = null;
        $objectClassResolver = null;
        $defaultContext = $this->getDefaultContent();

        return new GetSetMethodNormalizer(
            $classMetadataFactory,
            $nameConverter,
            $propertyTypeExtractor,
            $classDiscriminatorResolver,
            $objectClassResolver,
            $defaultContext
        );
    }
}
