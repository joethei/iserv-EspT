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
                'label' => _("Date")
            ])
            ->add('start', TimeType::class, [
                'label' => _('StartTime')
            ])
            ->add('end', TimeType::class, [
                'label' => _("EndTime")
            ])
            ->add('regStart', DateTimeType::class, [
                'label' => _("RegStart")
            ])
            ->add('regEnd', DateTimeType::class, [
                'label' => _("RegEnd")
            ])
            ->add('normalLength', DateIntervalType::class, [
                'label' => _("NormalLength"),
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => false,
                'with_minutes' => true,
                'labels' => [
                    'minutes' => _("minutes")
                ]
            ])
            ->add('inviteLength', DateIntervalType::class, [
                'label' => _("InviteLength"),
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => false,
                'with_minutes' => true,
                'labels' => [
                    'minutes' => _("minutes")
                ]
            ])
            ->add('maxNumberOfInvites', IntegerType::class, [
                'label' => _("MaxNumberOfInvites"),
                'icon' => 'ok',
                'buttonClass' => 'btn-success',
            ])
        ->add('save', SubmitType::class, [
            'label' => _("save")
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => CreationSettings::class,]);
    }
}