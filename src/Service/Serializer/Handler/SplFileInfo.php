<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Handler;

use SignNow\Serializer\GraphNavigatorInterface;
use SignNow\Serializer\Handler\SubscribingHandlerInterface;
use SignNow\Serializer\JsonSerializationVisitor;
use SignNow\Serializer\SerializationContext;
use SplFileInfo as SplFileInfoType;

/**
 * Class SplFileInfo
 *
 * @package SignNow\Rest\Service\Serializer\Handler
 */
class SplFileInfo implements SubscribingHandlerInterface
{
    /**
     * The direction and method keys can be omitted.
     *
     * @return array
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => SplFileInfoType::class,
                'method' => 'serializeFile',
            ],
        ];
    }
    
    /**
     * @param JsonSerializationVisitor $visitor
     * @param SplFileInfoType          $file
     * @param array                    $type
     * @param SerializationContext     $context
     *
     * @return SplFileInfoType
     */
    public function serializeFile(
        JsonSerializationVisitor $visitor,
        SplFileInfoType $file,
        array $type,
        SerializationContext $context
    ) {
        return $file;
    }
}
