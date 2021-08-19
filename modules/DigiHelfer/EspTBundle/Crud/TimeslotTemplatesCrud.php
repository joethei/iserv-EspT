<?php

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\BootstrapBundle\Form\Type\BootstrapCollectionType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CrudBundle\Routing\RoutingDefinition;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TimeslotTemplatesCrud extends AdminServiceCrud {

    protected static $entityClass = TimeslotTemplateCollection::class;

    protected function configure(): void {
        $this->title = "Zeitfenster Templates";
        $this->itemTitle = "Zeitfenster Template";
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('name', null)
            ->add('timeslots', null, ['label' => 'Zeitfenster']);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('name', null, ['label' => 'Name'])
            ->add('timeslots', null, ['label' => 'Zeitfenster']);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('timeslots', BootstrapCollectionType::class, [
                'label' => "Zeitfenster",
                'allow_add'          => true,
                'allow_delete'       => true,
                'add_button_text'    => 'Zeitfenster hinzufügen',
                'delete_button_text' => 'Zeitfenster entfernen',
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
            ->setNamePrefix('espt_admin')
            ->prependPathPrefix('espt/');
    }
}