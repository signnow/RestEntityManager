# SignNow REST API entity manager

This project helps to communicate with REST API using DTO objects

## Requirements

PHP 7.1 or newer

## Installation

The library can be installed using Composer.


```bash
composer require signnow/rest-entity-manager
```

### Usage

```php
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Annotation as Serializer;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManagerFactory;

/**
 * Class User
 *
 * @HttpEntity("users/{user}")
 */
class User extends Entity
{
    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $name;
    
    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

$entityManager = (new EntityManagerFactory())
    ->createEntityManager(['base_uri' => 'https://api.github.com']);

/** @var User $user */
$user = $entityManager->get(User::class, ['user' => 'codeception']);

echo sprintf('Id: %s; Name: %s.', $user->getId(), $user->getName());

```