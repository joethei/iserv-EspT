<?php

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Config\Configuration;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\CoreBundle\Repository\UserRepository;
use IServ\Library\Config\Config;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InviteStudentType extends AbstractType {

    /**@var Config $config*/
    private $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('user', null, [
                'label' => _('Student'),
                'help' => _('espt_student_help'),
                'by_reference' => true,
                'query_builder' => function(UserRepository $repository) {
                    //only show students in list
                    $queryBuilder = $repository->createDeletedAwareQueryBuilder('users');
                    $queryBuilder->join('users.roles', 'roles');
                    $queryBuilder->orderBy("users.firstname, users.lastname");
                    $queryBuilder->setParameter("privilege", Privilege::STUDENT);

                    $where = $queryBuilder->expr()->in('privileges', ':privilege');

                    $queryBuilder->andWhere($where);

                    return $queryBuilder;
                }

            ]);

            //allow teachers to edit event type
            if($this->config->get(Configuration::ALLOW_EDIT)) {
                $builder->add('type', EntityType::class, [
                    'label' => _('espt_timeslot_type'),
                    'help' => _('espt_timeslot_type_help'),
                    'class' => EventType::class,
                    'by_reference' => true,
                    'choice_label' => function(EventType $type) {
                        return _('espt_timeslot_type_' . $type->getName());
                    }
                ]);
            }

            $builder->add('save', SubmitType::class, [
                'label' => _('Save'),
                'icon' => 'ok',
                'buttonClass' => 'btn-success',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => Timeslot::class,]);
    }

}