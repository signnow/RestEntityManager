<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use InvalidArgumentException;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatInterface;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory;

/**
 * Class GuzzleRequestBody
 *
 * @package SignNow\Rest\EntityManager\Annotation
 *
 * @Annotation
 */
class GuzzleRequestBody extends Annotation
{
    public const DEFAULT_FORMAT = FormatInterface::FORMAT_JSON;
    
    /**
     * @var FormatInterface
     */
    protected $format;
    
    /**
     * @var FormatterFactory
     */
    private $factory;
    
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->factory = $this->getFormatterFactory();
        
        if (isset($options['value'])) {
            $this->format = $this->factory->create($options['value']);
            unset($options['value']);
        } else {
            throw new InvalidArgumentException('You must define request type');
        }
    }
    
    /**
     * @return string
     */
    public function getGuzzleRequestOption(): string
    {
        return $this->format->getGuzzleOptionKey();
    }
    
    /**
     * @param array $content
     *
     * @return array
     */
    public function prepare(array $content = [])
    {
        return $this->format->prepareBody($content);
    }
    
    /**
     * @return FormatterFactory
     */
    protected function getFormatterFactory(): FormatterFactory
    {
        return new FormatterFactory();
    }
}
