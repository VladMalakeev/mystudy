<?php

namespace App\Form;

use App\Entity\Lecturers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LecturersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name',null,array('label' => 'Имя'))
            ->add('last_name',null,array('label' => 'Фамилия'))
            ->add('patronymic',null,array('label' => 'Отчество'))
            ->add('description',null,array('label' => 'Информация о преподавателе'))
            ->add('photo', FileType::class,array('label' => 'Фото', 'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lecturers::class,
        ]);
    }
}
