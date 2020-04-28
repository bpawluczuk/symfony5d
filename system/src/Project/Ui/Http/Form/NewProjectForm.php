<?php

declare(strict_types=1);

namespace App\Project\Ui\Http\Form;

use App\Project\Ui\Http\Request\NewProject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => NewProject::class
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}