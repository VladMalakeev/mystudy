<?php
/**
 * Created by PhpStorm.
 * User: ASUS 553
 * Date: 17.08.2018
 * Time: 19:39
 */

namespace App\Form;
use App\Entity\Subjects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SubjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Полное название дисциплины'))
            ->add('description', null, array('label' => 'Краткое описание дисциплины'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subjects::class,
        ]);
    }
}