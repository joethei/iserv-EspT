<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class TimeslotTemplateType extends AbstractType {

    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('start', TimeType::class, [
                'label' => _('espt_starttime'),
                'help' => _('espt_timeslot_start_help'),
                'input' => 'datetime_immutable',
                'input_format' => 'H:i'
                ])
            ->add('end', TimeType::class, [
                'label' => _('espt_endtime'),
                'help' => _('espt_timeslot_end_help'),
                'input' => 'datetime_immutable',
                'input_format' => 'H:i',
                ])
            ->add('type', EntityType::class, [
                'label' => _('espt_timeslot_type'),
                'help' => _('espt_timeslot_type_help'),
                'class' => EventType::class,
                'by_reference' => true,
                'choice_label' => function(EventType $type) {
                    return _('espt_timeslot_type_' . $type->getName());
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => TimeslotTemplate::class,]);
    }

}