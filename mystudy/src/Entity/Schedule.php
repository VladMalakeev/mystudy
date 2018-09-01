<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subjects", inversedBy="schedules")
     */
    private $subject_name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lecturers", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lecturer;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $classroom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lesson_number;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $day_of_week;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $week;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groups", inversedBy="schedules")
     */
    private $groups;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="schedules")
     */
    private $department;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectName(): ?Subjects
    {
        return $this->subject_name;
    }

    public function setSubjectName( $subject_name): self
    {
        $this->subject_name = $subject_name;

        return $this;
    }

    public function getLecturer(): ?Lecturers
    {
        return $this->lecturer;
    }

    public function setLecturer( $lecturer): self
    {
        $this->lecturer = $lecturer;

        return $this;
    }

    public function getClassroom(): ?string
    {
        return $this->classroom;
    }

    public function setClassroom(?string $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getLessonNumber(): ?int
    {
        return $this->lesson_number;
    }

    public function setLessonNumber(?int $lesson_number): self
    {
        $this->lesson_number = $lesson_number;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(?string $day_of_week): self
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    public function getWeek(): ?string
    {
        return $this->week;
    }

    public function setWeek(?string $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function setGroup(Groups $group): self
    {
       $this->groups=$group;
        return $this;
    }


    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }
}
