<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class ProjectBaseType extends AbstractType
{
    final protected function addBaseFields(FormBuilderInterface $builder): void
    {
        $builder->add(
            'name',
            TextType::class,
            ['label' => 'project.form.name'],
        );
    }
}
