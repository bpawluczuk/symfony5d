<?php

declare(strict_types=1);

namespace App\Task\Ui\Http\Form;

use App\Task\Ui\Http\Request\NewProjectTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProjectTaskForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => NewProjectTask::class,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}