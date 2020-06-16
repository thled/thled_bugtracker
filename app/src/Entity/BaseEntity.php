<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class BaseEntity
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    protected UuidInterface $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    final public function getId(): UuidInterface
    {
        return $this->id;
    }
}
