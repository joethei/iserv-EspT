<?php

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
        $this->title = "Zeitfenster";
        $this->itemTitle = "Zeitfenster";
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('id')
            ->add('group', null, ['label' => 'Lehrkräfte'])
            ->add('user', null, ['label' => 'Schüler'])
            ->add('start', null, ['label' => 'Beginn'])
            ->add('end', null, ['label' => 'Ende']);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('group', null, ['label' => 'Lehrkräfte'])
            ->add('user', null, ['label' => 'Schüler'])
            ->add('start', null, ['label' => 'Beginn'])
            ->add('end', null, ['label' => 'Ende']);

    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('user', UserType::class, ['label' => 'Schüler']);
    }

    public function isAuthorized(): bool {
        return $this->isGranted('PRIV_ESPT_ADMIN');
    }

    public function prepareBreadcrumbs(): array {
        return [Breadcrumb::create(_('EspT'))];
    }

    public static function defineRoutes(): RoutingDefinition {
        return parent::defineRoutes()
            ->setNamePrefix('espt_admin')
            ->prependPathPrefix('espt/');
    }

}