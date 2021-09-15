<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\Timeslot;
use IServ\CoreBundle\Form\Type\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InviteStudentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('user', UserType::class, [
                'label' => _('Student'),
                'help' => _('espt_student_help'),
            ])
            ->add('save', SubmitType::class, [
                'label' => _('Save'),
                'icon' => 'ok',
                'buttonClass' => 'btn-success',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => Timeslot::class,]);
    }

}