<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LecturersRepository")
 */
class Lecturers
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
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $photo;
    /**
     * @ORM\ManyToMany(targetEntity="Department", inversedBy="lecturers")
     * @ORM\JoinTable(name="lecturers_department")
     */
    private $departments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="lecturer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $schedules;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->schedules = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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
            $schedule->setLecturer($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getLecturer() === $this) {
                $schedule->setLecturer(null);
            }
        }

        return $this;
    }

    public function getNameInfo(){
        return $this->getLastName().' '
            .strtoupper(mb_substr($this->getFirstName(),0,1,"UTF-8")).'. '
            . strtoupper(mb_substr($this->getPatronymic(),0,1,"UTF-8")).'.';
    }
}
