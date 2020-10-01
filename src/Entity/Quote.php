<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuoteRepository")
 * @ApiResource(
 *     collectionOperations={"get","post"={"security"="is_granted('ROLE_USER')"}},
 *     normalizationContext={"groups"={"quote:read"}},
 *     denormalizationContext={"groups"={"quote:write"}},
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_USER')",},
 *           "put"={"security"="is_granted('edit', object)"},
 *          "patch"={"security"="is_granted('edit', object)"},
 *          "delete"={"security"="is_granted('delete', object)"},
 *         "get"={
                "method"="GET",
 *              "normalization_context"={"groups"={"quote:read"}}
 *     },
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"content": "partial", "meta": "partial", "Author": "exact", "Author.name": "partial"})
 */
class Quote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=11)
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"quote:read", "quote:write"})
     */
    private $content;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"quote:read", "quote:write"})
     */
    private $meta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="quotes")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Groups({"quote:read", "quote:write"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"quote:read"})
     */
    private $Author;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     * @Groups({"quote:read"})
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
