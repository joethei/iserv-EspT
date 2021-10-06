<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CreationSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => _('espt_starttime'),
                'help' => _('espt_starttime_help'),
                'input' => 'datetime_immutable',
                'constraints' => [
                    new GreaterThanOrEqual (  ['value'=>"today",'message'=>"error message"] )
                ]
            ])
            ->add('end', DateTimeType::class, [
                'label' => _("espt_endtime"),
                'help' => _('espt_endtime_help'),
                'input' => 'datetime_immutable',
                'constraints' => [
                    new GreaterThanOrEqual (  ['value'=>"today",'message'=>"error message"] )
                ]
            ])
            ->add('regStart', DateTimeType::class, [
                'label' => _("espt_registration_start"),
                'help' => _('espt_registration_start_help'),
                'input' => 'datetime_immutable',
                'constraints' => [
                    new GreaterThanOrEqual (  ['value'=>"today",'message'=>"error message"] )
                ]
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
            'attr' => array('onclick' => 'return confirm(' . _('espt_confirm_creation') . ')'),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => CreationSettings::class,]);
    }
}