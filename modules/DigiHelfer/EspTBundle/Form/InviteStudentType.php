<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\CoreBundle\Form\Type\UserType;
use IServ\CoreBundle\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InviteStudentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('user', null, [
                'label' => _('Student'),
                'help' => _('espt_student_help'),
                'by_reference' => false,
                'query_builder' => function(UserRepository $repository) {
                    //only show students in list
                    $queryBuilder = $repository->createDeletedAwareQueryBuilder('users');
                    $queryBuilder->join('users.roles', 'roles');
                    $queryBuilder->orderBy("users.firstname, users.lastname");
                    $queryBuilder->setParameter("role", "ROLE_STUDENT");

                    $where = $queryBuilder->expr()->in('roles', ':role');

                    $queryBuilder->andWhere($where);

                    return $queryBuilder;
                }

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