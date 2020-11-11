<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Cocur\Slugify\Slugify;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @Vich\Uploadable()
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    
    /**
     * filename
     *
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * imageFile
     *
     * @var File|null
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="filename")
     * @Assert\Image(
     *      mimeTypes="image/jpeg" 
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt; 

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedUser;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class, inversedBy="articles")
     */
    private $catalog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSlug() : ?string
    {
        return (new Slugify())->slugify($this->reference);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedUser(): ?int
    {
        return $this->createdUser;
    }

    public function setCreatedUser(int $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    public function getUpdatedUser(): ?int
    {
        return $this->updatedUser;
    }

    public function setUpdatedUser(int $updatedUser): self
    {
        $this->updatedUser = $updatedUser;

        return $this;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get filename
     *
     * @return  string|null
     */ 
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param  string|null  $filename  filename
     *
     * @return  Article
     */ 
    public function setFilename(?string $filename): Article
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get imageFile
     *
     * @return  File|null
     */ 
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set imageFile
     *
     * @param  File|null  $imageFile  imageFile
     *
     * @return  Article
     */ 
    public function setImageFile(?File $imageFile): Article
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }
}
