<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CrudBundle\Routing\RoutingDefinition;
use IServ\CrudBundle\Table\Filter\ListPropertyFilter;
use IServ\CrudBundle\Table\ListHandler;

class TimeslotCrud extends AdminServiceCrud {

    protected static $entityClass = Timeslot::class;

    protected function configure(): void {
        $this->title = _('espt_timeslot');
        $this->itemTitle = _('espt_timeslot');
        $this->templates['crud_index'] = '@DH_EspT/AdminMenu.html.twig';
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('id')
            ->add('group', null, ['label' => _('espt_group')])
            ->add('user', null, ['label' => _('Student')])
            ->add('start', null, ['label' => _('espt_starttime')])
            ->add('end', null, ['label' => _('espt_endtime')])
            ->add('type', null, ['label' => _('espt_timeslot_type')]);
    }

    protected function configureListFilter(ListHandler $listHandler): void {
        $listHandler
            ->addListFilter(new ListPropertyFilter(_('Room'), 'group', TeacherGroup::class, 'room', 'id'));
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('group', null, ['label' => _('espt_group')])
            ->add('user', null, ['label' => _('Student')])
            ->add('start', null, ['label' => _('espt_starttime')])
            ->add('end', null, ['label' => _('espt_endtime')])
            ->add('type', null, ['label' => _('espt_timeslot_type')]);

    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('user', null, ['label' => _('Student')]);
    }

    public function isAuthorized(): bool {
        return ($this->isGranted(Privilege::ADMIN));
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