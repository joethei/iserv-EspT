<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\DBAL\Types\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeslotType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('start', TimeType::class, [
            'label' => "Start"
        ])
            ->add('end', TimeType::class, [
               'label' => "Ende",
            ])
            ->add('type', TimeslotType::class, [
               'label' => "Art"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) :void {
        $resolver->setDefaults([
           'data_class' => Timeslot::class,
        ]);
    }

}