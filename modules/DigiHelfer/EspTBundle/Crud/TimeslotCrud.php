<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\Timeslot;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\CoreBundle\Form\Type\UserType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CrudBundle\Routing\RoutingDefinition;

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
            ->add('group', null, ['label' => _('espt_teachers')])
            ->add('user', null, ['label' => _('espt_student')])
            ->add('start', null, ['label' => _('espt_starttime')])
            ->add('end', null, ['label' => _('espt_endtime')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('group', null, ['label' => _('espt_teachers')])
            ->add('user', null, ['label' => _('espt_student')])
            ->add('start', null, ['label' => _('espt_starttime')])
            ->add('end', null, ['label' => _('espt_endtime')]);

    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('user', null, ['label' => _('espt_student')]);
    }

    public function isAuthorized(): bool {
        return $this->isGranted('PRIV_ESPT_ADMIN');
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