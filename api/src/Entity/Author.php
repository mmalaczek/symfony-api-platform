<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={"groups"={"author:read"}},
 *     denormalizationContext={"groups"={"author:write"}}
 * )
 */
class Author
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"author:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     * @Assert\NotBlank
     * @Assert\Length(min=5, minMessage = "Nick must be at min 5 characters long.")
     * @ORM\Column(type="string", length=30, unique=true)
     * @Groups({"comment:read", "author:read", "author:write"})
     */
    private $nick;

    /**
     * @var string
     *
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @ORM\Column(type="string", length=80)
     * @Groups({"author:read", "author:write"})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", cascade={"persist", "remove"})
     * @Groups({"author:read"})
     * @ApiSubresource
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param Comment $comment
     * @param bool $updateRelation
     */
    public function addComment(Comment $comment, bool $updateRelation = true): void
    {
        if ($this->comments->contains($comment)) {
            return;
        }

        $this->comments->add($comment);
        if ($updateRelation) {
            $comment->setAuthor($this, false);
        }
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): iterable
    {
        return $this->comments;
    }

}
