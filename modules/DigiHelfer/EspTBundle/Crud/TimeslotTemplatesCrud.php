<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection;
use DigiHelfer\EspTBundle\Form\TimeslotTemplateType;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\BootstrapBundle\Form\Type\BootstrapCollectionType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CrudBundle\Routing\RoutingDefinition;

class TimeslotTemplatesCrud extends AdminServiceCrud {

    protected static $entityClass = TimeslotTemplateCollection::class;

    protected function configure(): void {
        $this->title = _('espt_timeslot_templates');
        $this->itemTitle = _('espt_timeslot_template');
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('name', null)
            ->add('timeslots', null, ['label' => _('espt_timeslot')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('name', null, ['label' => _('espt_name')])
            ->add('timeslots', null, ['label' => _('espt_timeslots')]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('name', null, [
                'label' => _('espt_name')
            ])
            ->add('timeslots', BootstrapCollectionType::class, [
                'entry_type' => TimeslotTemplateType::class,
                'label' => _('espt_timeslot'),
                'allow_add'          => true,
                'allow_delete'       => true,
                'add_button_text'    => _('espt_timeslot_add'),
                'delete_button_text' => _('espt_timeslot_remove'),
                'sub_widget_col'     => 9,
                'button_col'         => 3
            ]);
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