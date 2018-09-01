<?php
/**
 * Created by PhpStorm.
 * User: ASUS 553
 * Date: 01.09.2018
 * Time: 23:05
 */

namespace App\Service;

use App\Repository\CoursesRepository;
use App\Repository\DepartmentRepository;
use App\Repository\GroupsRepository;
use App\Repository\InstituteRepository;
use App\Repository\LecturersRepository;
use App\Repository\NewsRepository;
use App\Repository\ScheduleRepository;
use App\Repository\SubjectsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepositoryManager extends AbstractController
{
    private $instituteRepository;
    private $departmentRepository;
    private $newsRepository;
    private $lecturersRepository;
    private $subjectsRepository;
    private $scheduleRepository;
    private $usersRepository;
    private $groupsRepository;
    private $coursesRepository;



    public function __construct(InstituteRepository $instituteRepository,
                                DepartmentRepository $departmentRepository,
                                NewsRepository $newsRepository,
                                LecturersRepository $lecturersRepository,
                                SubjectsRepository $subjectsRepository,
                                ScheduleRepository $scheduleRepository,
                                UsersRepository $usersRepository,
                                GroupsRepository $groupsRepository,
                                CoursesRepository $coursesRepository)
    {
        $this->instituteRepository = $instituteRepository;
        $this->departmentRepository = $departmentRepository;
        $this->newsRepository = $newsRepository;
        $this->lecturersRepository = $lecturersRepository;
        $this->subjectsRepository = $subjectsRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->usersRepository = $usersRepository;
        $this->groupsRepository = $groupsRepository;
        $this->coursesRepository = $coursesRepository;
    }

    /**
     * @return CoursesRepository
     */
    public function getCoursesRepository(): CoursesRepository
    {
        return $this->coursesRepository;
    }

    /**
     * @return DepartmentRepository
     */
    public function getDepartmentRepository(): DepartmentRepository
    {
        return $this->departmentRepository;
    }

    /**
     * @return GroupsRepository
     */
    public function getGroupsRepository(): GroupsRepository
    {
        return $this->groupsRepository;
    }

    /**
     * @return InstituteRepository
     */
    public function getInstituteRepository(): InstituteRepository
    {
        return $this->instituteRepository;
    }

    /**
     * @return LecturersRepository
     */
    public function getLecturersRepository(): LecturersRepository
    {
        return $this->lecturersRepository;
    }

    /**
     * @return NewsRepository
     */
    public function getNewsRepository(): NewsRepository
    {
        return $this->newsRepository;
    }

    /**
     * @return ScheduleRepository
     */
    public function getScheduleRepository(): ScheduleRepository
    {
        return $this->scheduleRepository;
    }

    /**
     * @return SubjectsRepository
     */
    public function getSubjectsRepository(): SubjectsRepository
    {
        return $this->subjectsRepository;
    }

    /**
     * @return UsersRepository
     */
    public function getUsersRepository(): UsersRepository
    {
        return $this->usersRepository;
    }


}