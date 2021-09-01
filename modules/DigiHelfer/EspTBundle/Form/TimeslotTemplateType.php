<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeslotTemplateType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('start', TimeType::class, [
                'label' => _('espt_starttime'),
                'help' => _('espt_timeslot_start_help')
                ])
            ->add('end', TimeType::class, [
                'label' => _('espt_endtime'),
                'help' => _('espt_timeslot_end_help')
                ])
            ->add('type', ChoiceType::class, [
                'label' => _('espt_timeslot_type'),
                'help' => _('espt_timeslot_type_help'),
                'choices' => [
                    _('espt_timeslot_type_blocked') => EventType::BLOCKED,
                    _('espt_timeslot_type_break') => EventType::BREAK,
                    _('espt_timeslot_type_book') => EventType::BOOK,
                    _('espt_timeslot_type_invite') => EventType::INVITE
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => TimeslotTemplate::class,]);
    }

}