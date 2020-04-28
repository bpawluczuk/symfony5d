<?php

declare(strict_types=1);

namespace App\Organization\Ui\Http\Form;

use App\Organization\Ui\Http\Request\NewOrganizationUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewOrganizationUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('repeatPassword');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewOrganizationUser::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}