<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupsRepository")
 */
class Groups
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="groups")
     * @ORM\JoinColumn(nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year_of_admission;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="groups")
     */
    private $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getYearOfAdmission(): ?int
    {
        return $this->year_of_admission;
    }

    public function setYearOfAdmission(?int $year_of_admission): self
    {
        $this->year_of_admission = $year_of_admission;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

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
            $schedule->addGroup($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            $schedule->removeGroup($this);
        }

        return $this;
    }

    public function getCourse(){
        return (date("y")+1)-$this->getYearOfAdmission();
    }

    public function hasSchedule(){
        if(count($this->schedules)==0)
            return false;
        else return true;
    }

    public function hasScheduleExams(){
        return false;
    }

    public function getFullName(){
        return $this->department->getShortName().$this->getYearOfAdmission().$this->getNumber();
    }
}
