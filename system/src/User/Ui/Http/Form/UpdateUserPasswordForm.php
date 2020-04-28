<?php

declare(strict_types=1);

namespace App\User\Ui\Http\Form;

use App\User\Ui\Http\Request\NewUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword')
            ->add('newPassword')
            ->add('repeatNewPassword');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewUser::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}