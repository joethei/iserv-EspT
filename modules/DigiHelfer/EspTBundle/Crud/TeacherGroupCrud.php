<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\CoreBundle\NameSort\NamesSortingDirectorInterface;
use IServ\CoreBundle\Repository\UserRepository;
use IServ\CoreBundle\Twig\EntityFormatter;
use IServ\CoreBundle\Util\Collection\OrderedCollection;
use IServ\CrudBundle\Crud\Action\Link;
use IServ\CrudBundle\Entity\CrudInterface;
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
        $this->templates['crud_index'] = '@DH_EspT/AdminMenu.html.twig';
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('id')
            ->add('room', null, ['label' => _('espt_room')])
            ->add('users', null, ['label' => _('espt_teachers')])
            ->add('timeslotTemplates', null, ['label' => _('espt_timeslot_template')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('room', null, ['label' => _('espt_room')])
            ->add('users', null, [
                'label' => _('espt_teachers'),
                'order_by' => $this->locator->get(NamesSortingDirectorInterface::class)->getSortBy(),
                'entity_format' => EntityFormatter::FORMAT_USER
            ])
            ->add('timeslotTemplates', null, ['label' => _('espt_timeslot_template')]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('room', null, ['label' => _('espt_room'), 'required' => false,])
            ->add('users', UserType::class, [
                'label' => _('espt_teachers'),
                'by_reference' => false,
                'query_builder' => function(UserRepository $repository) {
                    //only show teachers in selection
                    return $repository->createPrivilegeQueryBuilder(Privilege::TEACHER);
                }
            ])
            ->add('timeslotTemplates', EntityType::class, [
                'class' => TimeslotTemplateCollection::class,
                'label' => _('espt_timeslot_templates'),
                'help' => _('espt_timeslot_templates_help'),
                'choice_label' => 'name',
                'multiple' => true,
                'crud_create_remote' => $this->router()->generate('espt_admin_timeslottemplatecollection_add')
            ]);

    }

    public function getShowActions(CrudInterface $item) : OrderedCollection {
        $actions = parent::getShowActions($item);
        $actions['pdf'] = Link::create($this->router()->generate('espt_print_group', ['groupId' => $item->getId()]), _('espt_generate_print'), 'list', 'btn-primary');
        return $actions;
    }

    public function getIndexActions() : OrderedCollection {
        $actions = parent::getIndexActions();
        $actions['roomlist'] = Link::create($this->router()->generate('espt_print_rooms'), _('espt_room_list'), 'list', 'btn-primary');
        return $actions;
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