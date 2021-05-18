<?php

namespace DigiHelfer\EspT\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class CreationSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('date', DateType::class)
            ->add('start', TimeType::class)
            ->add('end', TimeType::class)
            ->add('regStart', DateTimeType::class)
            ->add('regEnd', DateTimeType::class)
            ->add('normalLength', TimeType::class)
            ->add('maxNumberOfInvites', IntegerType::class);
    }
}