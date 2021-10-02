<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Form\FilterGroupType;
use DigiHelfer\EspTBundle\Repository\TeacherGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use IServ\CoreBundle\Controller\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espt")
 */
class FilterController extends AbstractPageController {

    /**
     * @param TeacherGroupRepository $groupRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/filter", name="espt_filter_groups")
     */
    public function filter(TeacherGroupRepository $groupRepository, EntityManagerInterface $entityManager) : Response {
        $groups = $groupRepository->findForSelection($this->authenticatedUser());
        $form = $this->createForm(FilterGroupType::class, $groups);

        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $groups = $form->getData();

            $entityManager->persist($groups);
            $entityManager-flush();

            $this->redirectToRoute('espt_index');
        }

        return $this->render('@DH_EspT/Default/UserForm.html.twig', [
           'form' => $form->createView()
        ]);
    }

}