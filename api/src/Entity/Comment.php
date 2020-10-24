<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/Review",
 *     denormalizationContext={"groups": {"comment:write"}},
 *     normalizationContext={"groups": {"comment:read"}}
 * )
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     * @Groups({"comment:read", "comment:write"})
     */
    private $message;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     * @Groups({"comment:read"})
     */
    private $createdAt;

    /**
     * @var Author
     *
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="comments")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Groups({"comment:read", "comment:write"})
     */
    private $author;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param Author|null $author
     * @param bool $updateRelation
     */
    public function setAuthor(?Author $author, bool $updateRelation = true): void
    {
        $this->author = $author;
        if ($updateRelation) {
            $author->addComment($this, false);
        }
    }

    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

}
