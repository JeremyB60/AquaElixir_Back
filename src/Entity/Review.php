<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{

    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'productReview')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $productReview = null;

    #[ORM\ManyToOne(inversedBy: 'reviewUser')]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn(onDelete:'CASCADE')]
    private ?User $reviewUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getProductReview(): ?Product
    {
        return $this->productReview;
    }

    public function setProductReview(?Product $productReview): static
    {
        $this->productReview = $productReview;

        return $this;
    }

    public function getReviewUser(): ?User
    {
        return $this->reviewUser;
    }

    public function setReviewUser(?User $reviewUser): static
    {
        $this->reviewUser = $reviewUser;

        return $this;
    }
}