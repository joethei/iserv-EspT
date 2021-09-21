<?php

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Repository\EventTypeRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Form\InviteStudentType;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class InviteController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
class InviteController extends AbstractPageController {

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TimeslotRepository $timeslotRepository
     * @param EventTypeRepository $typeRepository
     * @return Response|JsonResponse
     * @throws NonUniqueResultException
     * @Route("/invite/{id}", name="espt_invite", options={"expose": true})
     */
    public function invite(int $id, Request $request, EntityManagerInterface $entityManager, TimeslotRepository $timeslotRepository, EventTypeRepository $typeRepository): Response {
        $timeslot = $timeslotRepository->find($id);
        $form = $this->createForm(InviteStudentType::class, $timeslot);

        $form->handleRequest($request);

        /** @var Timeslot $timeslot*/
        if ($form->isSubmitted() && $form->isValid()) {
            $timeslot = $form->getData();

            //only save if timeslot is correct type
            if($timeslot->getType()->getName() == EventType::INVITE) {

                //block all timeslots that overlap for this group
                //if the teacher decides to remove this invite this will be rolled back
                $groupTimeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());
                /** @var Timeslot $groupTimeslot */
                foreach($groupTimeslots as $groupTimeslot) {
                    if(DateUtils::datesOverlap($groupTimeslot->getStart(), $groupTimeslot->getEnd(), $timeslot->getStart(), $timeslot->getEnd()) > 0) {
                        if($timeslot->getUser() != null && $groupTimeslot->getType()->getName() == EventType::BOOK) {
                            $groupTimeslot->setType($typeRepository->findFor(EventType::BLOCKED));
                        }else if ($groupTimeslot->getType()->getName() == EventType::BLOCKED) {
                            $groupTimeslot->setType($typeRepository->findFor(EventType::BOOK));
                        }
                        $entityManager->persist($groupTimeslot);
                    }
                }

                $entityManager->persist($timeslot);
                $entityManager->flush();

                return $this->redirectToRoute('espt_index');

                //return $this->json([
                //   'status' => 'success'
                //]);
            }else {
                return $this->json([
                   'status' => 'error',
                   'message' => 'timeslot is of wrong type',
                ]);
            }
        }

        return $this->render("@DH_EspT/Default/UserForm.html.twig", ["form" => $form->createView()]);
    }

}