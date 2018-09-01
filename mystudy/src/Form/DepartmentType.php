<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Institute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name',null, array('label' => 'Полное название кафедры'))
            ->add('short_name',null, array('label' => 'Сокращение'))
            ->add('description',null, array('label' => 'Краткое описание кафедры'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
