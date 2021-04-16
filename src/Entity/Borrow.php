<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Borrow
 *
 * @ORM\Table(name="borrow")
 * @ORM\Entity
 */
class Borrow
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
     * @ORM\Column(name="date_borrow", type="date", nullable=false)
     */
    private $dateBorrow;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';

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

    public function getDateBorrow(): ?\DateTimeInterface
    {
        return $this->dateBorrow;
    }

    public function setDateBorrow(\DateTimeInterface $dateBorrow): self
    {
        $this->dateBorrow = $dateBorrow;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }


}
