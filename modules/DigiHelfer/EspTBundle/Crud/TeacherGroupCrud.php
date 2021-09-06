<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\CoreBundle\NameSort\NamesSortingDirectorInterface;
use IServ\CoreBundle\Twig\EntityFormatter;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CoreBundle\Form\Type\UserType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Routing\RoutingDefinition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TeacherGroupCrud extends AdminServiceCrud {

    protected static $entityClass = TeacherGroup::class;

    protected function configure(): void {
        $this->title = _('espt_groups');
        $this->itemTitle = _('espt_group');
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('id')
            ->add('room', null, ['label' => _('espt_room')])
            ->add('users', null, ['label' => _('espt_teachers')])
            ->add('timeslotTemplate', null, ['label' => _('espt_timeslot_template')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('room', null, ['label' => _('espt_room')])
            ->add('users', UserType::class, [
                'label' => _('espt_teachers'),
                'order_by' => $this->locator->get(NamesSortingDirectorInterface::class)->getSortBy(),
                'entity_format' => EntityFormatter::FORMAT_USER
            ])
            ->add('timeslotTemplate', null, ['label' => _('espt_timeslot_template')]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('room', null, ['label' => _('espt_room'), 'required' => false,])
            ->add('users', UserType::class, [
                'label' => _('espt_teachers'),
                'by_reference' => false
            ])
            ->add('timeslotTemplate', EntityType::class, [
                'label' => _('espt_timeslot_template'),
                'help' => _('espt_timeslot_template_help'),
                'class' => TimeslotTemplateCollection::class,
                'choice_label' => 'name'
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

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedServices(): array {
        // Take all subscribed services from the parent classes.
        $services = parent::getSubscribedServices();
        // Add services we commonly use and don't want to inject in each controller or action.
        $services[] = NamesSortingDirectorInterface::class;
        return $services;
    }

}