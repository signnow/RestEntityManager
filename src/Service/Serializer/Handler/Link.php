<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use SignNow\Rest\Service\Serializer\Type\FileLink;

/**
 * Class Link
 *
 * @package SignNow\Rest\Service\Serializer\Handler
 */
class Link implements SubscribingHandlerInterface
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
                'type' => FileLink::class,
                'method' => 'serializeFile',
            ],
        ];
    }
    
    /**
     * @param JsonSerializationVisitor $visitor
     * @param FileLink                 $link
     * @param array                    $type
     * @param SerializationContext     $context
     *
     * @return FileLink
     */
    public function serializeFile(
        JsonSerializationVisitor $visitor,
        FileLink $link,
        array $type,
        SerializationContext $context
    )
    {
        return $link;
    }
}
