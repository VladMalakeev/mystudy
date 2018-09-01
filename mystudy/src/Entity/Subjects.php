<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectsRepository")
 */
class Subjects
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Department", inversedBy="subjects")
     * @ORM\JoinTable(name="subjects_department")
     */
    private $departments;

    /**
     * @ORM\ManyToMany(targetEntity="Courses", inversedBy="subjects")
     * @ORM\JoinTable(name="subjects_courses")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="subject_name")
     */
    private $schedules;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    /**
     * @return Collection|Department[]
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    public function setDepartment(Department $department){
     $this->departments = array($department);
    }

    /**
     * @return Collection|Courses[]
     */
    public function getCourses()
    {
        return $this->courses;
    }

    public function setCourses(Courses $courses){
        $this->courses = array($courses);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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
            $schedule->setSubjectName($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getSubjectName() === $this) {
                $schedule->setSubjectName(null);
            }
        }

        return $this;
    }
}
