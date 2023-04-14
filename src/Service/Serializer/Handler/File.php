<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Handler;

use SignNow\Serializer\GraphNavigatorInterface;
use SignNow\Serializer\Handler\SubscribingHandlerInterface;
use SignNow\Serializer\JsonSerializationVisitor;
use SignNow\Serializer\SerializationContext;
use SignNow\Rest\Service\Serializer\Type\File as FileType;

/**
 * Class File
 *
 * @package SignNow\Rest\Service\Serializer\Handler
 */
class File implements SubscribingHandlerInterface
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
                'type' => FileType::class,
                'method' => 'serializeFile',
            ],
        ];
    }
    
    /**
     * @param JsonSerializationVisitor $visitor
     * @param FileType                 $file
     * @param array                    $type
     * @param SerializationContext     $context
     *
     * @return FileType
     */
    public function serializeFile(
        JsonSerializationVisitor $visitor,
        FileType $file,
        array $type,
        SerializationContext $context
    ) {
        return $file;
    }
}
