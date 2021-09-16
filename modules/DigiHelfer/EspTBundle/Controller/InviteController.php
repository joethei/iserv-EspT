<?php

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Form\InviteStudentType;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class InviteController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
class InviteController extends AbstractPageController {

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TeacherGroupRepository $groupRepository
     * @param TimeslotRepository $timeslotRepository
     * @return array
     * @throws NonUniqueResultException
     * @Route("/invite/{id}", name="espt_invite", options={"expose": true})
     * @Template("@DH_EspT/Default/index.html.twig")
     */
    public function invite(int $id, Request $request, EntityManagerInterface $entityManager, TimeslotRepository $timeslotRepository): array {
        $timeslot = $timeslotRepository->find($id);
        $form = $this->createForm(InviteStudentType::class, $timeslot);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //only save if timeslot is correct type
            if($timeslot->getType() == EventType::INVITE) {
                $timeslot = $form->getData();

                //block all timeslots that overlap for this group
                //if the teacher decides to remove this invite this will be rolled back
                $groupTimeslots = $timeslotRepository->findForGroup($timeslot->getGroup());
                foreach($groupTimeslots as $groupTimeslot) {
                    if(DateUtils::datesOverlap($groupTimeslot->getStart(), $groupTimeslot->getEnd(), $timeslot->getStart(), $timeslot->getEnd()) > 0) {
                        if($timeslot->getUser() != null && $groupTimeslot->getType() == EventType::BOOK) {
                            $groupTimeslot->setType(EventType::BLOCKED);
                        }else if ($groupTimeslot->getType() == EventType::BLOCKED) {
                            $groupTimeslot->setType(EventType::BOOK);
                        }
                        $entityManager->persist($groupTimeslot);
                    }
                }

                $entityManager->persist($timeslot);
                $entityManager->flush();
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

}