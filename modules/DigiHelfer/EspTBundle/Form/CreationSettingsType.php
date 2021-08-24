<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => _('espt_starttime'),
                'help' => _('espt_starttime_help'),
                'input' => 'datetime_immutable',
            ])
            ->add('end', DateTimeType::class, [
                'label' => _("espt_endtime"),
                'help' => _('espt_endtime_help'),
                'input' => 'datetime_immutable',
            ])
            ->add('regStart', DateTimeType::class, [
                'label' => _("espt_registration_start"),
                'help' => _('espt_registration_start_help'),
                'input' => 'datetime_immutable',
            ])
            ->add('regEnd', DateTimeType::class, [
                'label' => _("espt_registration_end"),
                'help' => _('espt_registration_end_help'),
                'input' => 'datetime_immutable',
            ])
        ->add('save', SubmitType::class, [
            'label' => _("espt_save"),
            'icon' => 'ok',
            'buttonClass' => 'btn-success',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => CreationSettings::class,]);
    }
}