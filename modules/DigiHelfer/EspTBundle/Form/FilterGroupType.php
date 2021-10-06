<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Form;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TeacherGroupSelection;
use IServ\CoreBundle\Entity\User;
use IServ\CoreBundle\NameSort\NamesSortingDirectorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterGroupType extends AbstractType {

    /**@var NamesSortingDirectorInterface $director*/
    private $director;

    public function __construct(NamesSortingDirectorInterface $director) {
        $this->director = $director;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('groups', EntityType::class, [
                'label' => _('espt_teachers'),
                'help' => _('espt_filter_groups_help'),
                'class' => TeacherGroup::class,
                'by_reference' => true,
                'multiple' => true,
                'select2' => true,
                'select2-icon' => 'fugue-user',
                'choice_label' => function(TeacherGroup $group) {
                    $names = array();
                    /**@var User $user*/
                    //display teacher names according to user preferences
                    foreach ($group->getUsers() as $user) {
                        if($this->director->shouldSortByFirstname()) {
                            $names[] = $user->getNameByFirstname();
                        }else {
                            $names[] = $user->getNameByLastname();
                        }
                    }
                    return implode(', ', $names);
                }
            ])
        ->add('submit', SubmitType::class, [
            'label' => _('Filter'),
            'icon' => 'filter',
            'buttonClass' => 'btn-default'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults(['data_class' => TeacherGroupSelection::class,]);
    }
}