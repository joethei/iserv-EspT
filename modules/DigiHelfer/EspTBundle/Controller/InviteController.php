<?php

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Form\InviteStudentType;
use Doctrine\DBAL\Exception;
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
     * @throws Exception
     * @throws NonUniqueResultException
     * @Route("/invite/{id}", name="espt_invite")
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

                $entityManager->persist($timeslot);
                $entityManager->flush();
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

}