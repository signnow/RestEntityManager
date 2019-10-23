<?php
declare(strict_types=1);

namespace SignNow\Rest\Service\Serializer\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use SplFileInfo;

/**
 * Class SplFileInfo
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
                'type' => SplFileInfo::class,
                'method' => 'serializeFile',
            ],
        ];
    }
    
    /**
     * @param JsonSerializationVisitor $visitor
     * @param SplFileInfo              $file
     * @param array                    $type
     * @param SerializationContext     $context
     *
     * @return SplFileInfo
     */
    public function serializeFile(
        JsonSerializationVisitor $visitor,
        SplFileInfo $file,
        array $type,
        SerializationContext $context
    )
    {
        return $file;
    }
}
