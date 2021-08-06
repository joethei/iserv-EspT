<?php

namespace DigiHelfer\EspTBundle\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => _('StartTime'),
                'help' => "Um welche Uhrzeit soll der Sprechtag starten?"
            ])
            ->add('end', DateTimeType::class, [
                'label' => _("EndTime"),
                'help' => "Um welche Uhrzeit soll der Sprechtag enden?"
            ])
            ->add('regStart', DateTimeType::class, [
                'label' => _("RegStart"),
                'help' => "Ab wann soll die Anmeldung möglich sein?"
            ])
            ->add('regEnd', DateTimeType::class, [
                'label' => _("RegEnd"),
                'help' => "Bis wann soll die Anmeldung möglich sein?"
            ])->add('timeslots', CollectionType::class, [
                'entry_type' => TimeslotType::class,
                'entry_options' => ['label' => false],
            ])
        ->add('save', SubmitType::class, [
            'label' => _("save"),
            'icon' => 'ok',
            'buttonClass' => 'btn-success',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => CreationSettings::class,]);
    }
}