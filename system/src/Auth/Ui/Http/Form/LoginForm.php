<?php

declare(strict_types=1);

namespace App\Auth\Ui\Http\Form;

use App\Auth\Ui\Http\Request\LoginCredentials;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LoginCredentials::class,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}