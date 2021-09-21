<?php

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use IServ\Library\Config\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegisterController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
class RegisterController extends AbstractPageController {

    /**
     * @param Request $request
     * @param TimeslotRepository $timeslotRepository
     * @param EntityManagerInterface $entityManager
     * @param Config $config
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/timeslots/reserve", name="espt_timeslots_reserve", options={"expose": true}, methods={"POST"})
     */
    public function reserveTimeslot(Request $request, TimeslotRepository $timeslotRepository, EntityManagerInterface $entityManager, Config $config): Response {
        $id = $request->request->get('id');
        if($id == null) return $this->json(array('success' => false, 'error'=> 'no id'));

        if (!$this->isGranted("ROLE_STUDENT") && !$this->isGranted("ROLE_PARENT")) {
            return $this->json(array('success' => false, 'error' => 'no perms'));
        }

        $timeslot = $timeslotRepository->find($id);
        if ($timeslot == null) {
            return $this->json(array('success' => false, 'error' => 'no timeslot'));
        }

        if ($timeslot->getType()->getName() != EventType::BOOK) return $this->json(array('success' => false, 'error' => 'wrong type', 'type' => $timeslot->getType()));

        if ($timeslot->getUser() != null) {
            if ($timeslot->getUser() === $this->authenticatedUser()) {
                //remove booking for user
                $timeslot->setUser(null);
                $entityManager->persist($timeslot);
                $entityManager->flush();
                return $this->json(array('success' => true));
            }

            return $this->json(array('success' => false, 'error' => 'already booked'));
        }

        $timeslots = $timeslotRepository->findForUser($this->authenticatedUser());
        /** @var Timeslot $otherTimeslot*/
        foreach ($timeslots as $otherTimeslot) {
            if(!$config->get('EspTAllowOverlap')) {
                if(DateUtils::datesOverlap($timeslot->getStart(), $timeslot->getEnd(), $otherTimeslot->getStart(), $otherTimeslot->getEnd())) {
                    return $this->json(array('success' => false, 'error' => _('espt_timeslot_overlap')));
                }
            }
            if(!$config->get('EspTAllowDuplicates')) {
                if($timeslot->getGroup()->getId() == $otherTimeslot->getGroup()->getId()) {
                    return $this->json(array('success' => false, 'error' => _('espt_timeslot_group_duplicate')));
                }
            }
        }

        $timeslot->setUser($this->authenticatedUser());
        $entityManager->persist($timeslot);
        $entityManager->flush();

        return $this->json(array('success' => true));
    }

}