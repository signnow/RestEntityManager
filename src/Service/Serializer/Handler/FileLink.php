<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Handler;

use SignNow\Serializer\GraphNavigatorInterface;
use SignNow\Serializer\Handler\SubscribingHandlerInterface;
use SignNow\Serializer\JsonSerializationVisitor;
use SignNow\Serializer\SerializationContext;
use SignNow\Rest\Service\Serializer\Type\FileLink as FileLinkType;

/**
 * Class FileLink
 *
 * @package SignNow\Rest\Service\Serializer\Handler
 */
class FileLink implements SubscribingHandlerInterface
{
    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => FileLinkType::class,
                'method' => 'serializeFile',
            ],
        ];
    }
    
    /**
     * @param JsonSerializationVisitor $visitor
     * @param FileLinkType             $link
     * @param array                    $type
     * @param SerializationContext     $context
     *
     * @return FileLinkType
     */
    public function serializeFile(
        JsonSerializationVisitor $visitor,
        FileLinkType $link,
        array $type,
        SerializationContext $context
    ) {
        return $link;
    }
}
