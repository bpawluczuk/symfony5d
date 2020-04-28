<?php

declare(strict_types=1);

namespace App\Location\Ui\Http\Form;

use App\Organization\Ui\Http\Request\NewOrganizationLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewOrganizationLocationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewOrganizationLocation::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}