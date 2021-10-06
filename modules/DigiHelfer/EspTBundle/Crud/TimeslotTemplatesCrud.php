<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection;
use DigiHelfer\EspTBundle\Form\TimeslotTemplateType;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\BootstrapBundle\Form\Type\BootstrapCollectionType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CrudBundle\Routing\RoutingDefinition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class TimeslotTemplatesCrud extends AdminServiceCrud {

    protected static $entityClass = TimeslotTemplateCollection::class;

    protected function configure(): void {
        $this->title = _('espt_timeslot_templates');
        $this->itemTitle = _('espt_timeslot_template');
        $this->templates['crud_index'] = '@DH_EspT/AdminMenu.html.twig';
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('name')
            ->add('day', null, ['label' => _('Day')])
            ->add('timeslots', null, ['label' => _('espt_timeslot')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('name', null, ['label' => _('espt_name')])
            ->add('day', null, ['label' => _('Day')])
            ->add('timeslots', null, ['label' => _('espt_timeslot')])
            ->add('groups', null, ['label' => _('espt_groups')]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('name', null, [
                'label' => _('espt_name')
            ])
            ->add('day', null, [
                'label' => _('Day'),
            ])
            ->add('timeslots', BootstrapCollectionType::class, [
                'entry_type' => TimeslotTemplateType::class,
                'by_reference' => false,
                'label' => _('espt_timeslot'),
                'allow_add'          => true,
                'allow_delete'       => true,
                'add_button_text'    => _('espt_timeslot_add'),
                'delete_button_text' => _('espt_timeslot_remove'),
                'sub_widget_col'     => 9,
                'button_col'         => 3,
            ])
        ->add('groups', EntityType::class, [
                'class' => TeacherGroup::class,
                'label'=> _('espt_groups'),
                'choice_label' => function(TeacherGroup $group) {
                    return $group;
                },
                'multiple' => true
            ]
        );

    }

    public function isAuthorized(): bool {
        return $this->isGranted(Privilege::ADMIN);
    }

    public function prepareBreadcrumbs(): array {
        return [Breadcrumb::create(_('EspT'))];
    }

    public static function defineRoutes(): RoutingDefinition {
        return parent::defineRoutes()
            ->setNamePrefix('espt_admin_')
            ->prependPathPrefix('espt/');
    }
}