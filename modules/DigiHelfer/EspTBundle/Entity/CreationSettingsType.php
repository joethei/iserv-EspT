<?php

namespace DigiHelfer\EspTBundle\Entity;

use Symfony\Component\Form\AbstractType;
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
            ->add('date', DateType::class, [
                'label' => _("Date"),
                'help' => "An welchem Tag soll der Sprechtag stattfinden?"
            ])
            ->add('start', TimeType::class, [
                'label' => _('StartTime'),
                'help' => "Um welche Uhrzeit soll der Sprechtag starten?"
            ])
            ->add('end', TimeType::class, [
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
            ])
            ->add('normalLength', DateIntervalType::class, [
                'label' => _("NormalLength"),
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => false,
                'with_minutes' => true,
                'widget' => 'integer',
                'labels' => [
                    'minutes' => _("minutes")
                ],
                'help' => "Wie lang ist ein normaler Termin?"
            ])
            ->add('inviteLength', DateIntervalType::class, [
                'label' => _("InviteLength"),
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => false,
                'with_minutes' => true,
                'widget' => 'integer',
                'labels' => [
                    'minutes' => _("minutes")
                ],
                'help' => "Wie lang ist ein Einladungstermin?"
            ])
            ->add('maxNumberOfInvites', IntegerType::class, [
                'label' => _("MaxNumberOfInvites"),
                'help' => "Wie viele Einladungstermine können maximal vergeben werden?"
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