<?php

namespace App\Service\Serializer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class TaskSerializer implements SerializerInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct()
    {
        $normalizers = [$this->getNormalizer()];
        $encoders = [new JsonEncoder()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param object $data
     * @param string $format
     * @param array $context
     *
     * @return string
     */
    public function serialize($data, string $format = 'json', array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * @param object $data
     * @param string $format
     * @param array $context
     *
     * @return mixed
     */
    public function normalize($data, string $format = 'json', array $context = [])
    {
        return $this->serializer->normalize($data, $format, $context);
    }

    /**
     * @return array
     */
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

    /**
     * @return NormalizerInterface
     */
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
