<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Returns
 *
 * @ORM\Table(name="returns")
 * @ORM\Entity
 */
class Returns
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="student_id", type="integer", nullable=false)
     */
    private $studentId;

    /**
     * @var int
     *
     * @ORM\Column(name="book_id", type="integer", nullable=false)
     */
    private $bookId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_return", type="date", nullable=false)
     */
    private $dateReturn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    public function setBookId(int $bookId): self
    {
        $this->bookId = $bookId;

        return $this;
    }

    public function getDateReturn(): ?\DateTimeInterface
    {
        return $this->dateReturn;
    }

    public function setDateReturn(\DateTimeInterface $dateReturn): self
    {
        $this->dateReturn = $dateReturn;

        return $this;
    }


}
