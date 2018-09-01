<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $short_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Groups", mappedBy="department")
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Subjects", mappedBy="departments")
     */
    private $subjects;

    /**
     * @ORM\ManyToMany(targetEntity="Lecturers", mappedBy="departments")
     */
    private $lecturers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="department")
     */
    private $schedules;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institute", inversedBy="departments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $institute;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->lecturers = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    /**
     * @return Collection|Groups[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return Collection|Subjects[]
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @return Collection|Lecturers[]
     */
    public function getLecturers()
    {
        return $this->lecturers;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(?string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInstitute(): ?Institute
    {
        return $this->institute;
    }

    public function setInstitute(Institute $institute): self
    {
        $this->institute = $institute;

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setDepartment($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getDepartment() === $this) {
                $schedule->setDepartment(null);
            }
        }

        return $this;
    }
}
