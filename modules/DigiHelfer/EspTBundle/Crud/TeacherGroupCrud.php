<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Crud;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use IServ\AdminBundle\Admin\AdminServiceCrud;
use IServ\CoreBundle\Autocomplete\AutocompleteType;
use IServ\CoreBundle\Autocomplete\Form\Type\AutocompleteTagsType;
use IServ\CrudBundle\Model\Breadcrumb;
use IServ\CoreBundle\Form\Type\UserType;
use IServ\CrudBundle\Mapper\FormMapper;
use IServ\CrudBundle\Mapper\ListMapper;
use IServ\CrudBundle\Mapper\ShowMapper;
use IServ\CrudBundle\Routing\RoutingDefinition;

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
            ->add('users', UserType::class, ['label' => _('espt_teachers')]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
            ->add('room', null, ['label' => _('espt_room')])
            ->add('users', UserType::class, ['label' => _('espt_teachers')]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('room', null, ['label' => _('espt_room'), 'required' => false,])
            ->add('users', AutocompleteTagsType::class, [
                'label' => _('espt_teachers'),
                'autocomplete_types' => AutocompleteType::user(),
                'tag_default_icon' => 'user'
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