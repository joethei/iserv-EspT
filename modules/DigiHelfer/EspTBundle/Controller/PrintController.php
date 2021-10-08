<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Repository\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Repository\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\PdfCreator;
use DigiHelfer\EspTBundle\Security\Privilege;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PrintController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt/print")
 */
final class PrintController extends AbstractPageController {

    private function buildPdf(CreationSettings $settings, int $logoWidth = 30) : PdfCreator {
        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $diffDays = $settings->getStart()->diff($settings->getEnd())->days;

        $subject = _('espt_on') . " " .   $diffDays > 0 ? strftime('%A %e.%B', $settings->getStart()->getTimestamp()) : strftime('%A %e.%B %G', $settings->getStart()->getTimestamp())
            . " " . __('espt_start_end_time',
                $settings->getStart()->format('G:i'),
                $diffDays > 0 ?  strftime('%A %e.%B %G %H:%M', $settings->getEnd()->getTimestamp()) : $settings->getEnd()->format(' G:i'));

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('IServ ' . _('EspT'));
        $pdf->SetTitle(_('EspT'));
        $pdf->SetSubject($subject);

        $pdf->setLogoSize($logoWidth);
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setCustomHeaderText($subject);
        $pdf->setCustomFooterText(_('espt_print_for') . $this->authenticatedUser()->getNameByFirstname());

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();
        $pdf->Ln();

        return $pdf;
    }


    /**
     * @param int $groupId
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @param TeacherGroupRepository $groupRepository
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/group/{groupId}", name="espt_print_group")
     */
    public function printGroup(int $groupId, CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository, TeacherGroupRepository $groupRepository) : Response {
        $this->denyAccessUnlessGranted(Privilege::TEACHER);

        //only allow admins to view lists for other groups
        $groups = $groupRepository->findFor($this->authenticatedUser());

        $isInGroup = false;
        /** @var TeacherGroup $group*/
        foreach ($groups as $group) {
            if ($group->getId() == $groupId) {
                $isInGroup = true;
            }
        }

        if(!$isInGroup) {
            $this->denyAccessUnlessGranted(Privilege::ADMIN);
        }

        $settings = $settingsRepository->findFirst();

        // column titles
        $header = array(_('Time'), _('Student'));

        $teacherGroup = $groupRepository->find($groupId);
        $timeslots = $timeslotRepository->findForGroup($teacherGroup);
        $data = array();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {
            $user = $timeslot->getUser();
            if($user != null) {
                $groups = array();
                foreach ($user->getGroups() as $group) {
                    $groups[] = $group->getName();
                }
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    $user->getNameByFirstname() . ": " . implode(', ', $groups)
                );
            }else {
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    ''
                );
            }
        }
        $pdf = $this->buildPdf($settings);
        $pdf->setCustomFooterText("");
        $pdf->Ln(5);
        $pdf->Cell(0, 10, implode(", ", $teacherGroup->getUsers()->toArray()), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Ln(5);
        $pdf->Cell(0, 10, _('Room') . " " . $teacherGroup->getRoom(), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Ln(5);
        $pdf->Table($header, $data);

        $filename = '/tmp/espt_temp' .$this->authenticatedUser()->getUuid() . '.pdf';
        $pdf->Output($filename, 'F');

        // display the file contents in the browser instead of downloading it
        return $this->file($filename, _('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/student", name="espt_print_student")
     */
    public function printStudent(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository) : Response {
        $this->denyAccessUnlessGranted(Privilege::STUDENT);
        $settings = $settingsRepository->findFirst();

        // column titles
        $header = array(_('Time'), _('Teacher'), _('Room'));
        $headerWidth = array(40, 100, 30);

        $timeslots = $timeslotRepository->findForUser($this->authenticatedUser());
        $data = array();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {
            $group = $timeslot->getGroup();

            $data[] = array(
                __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                $group,
                $group->getRoom()
            );
        }

        $pdf = $this->buildPdf($settings, 20);
        $pdf->Ln(5);
        $pdf->Table($header, $data, $headerWidth);

        $filename = '/tmp/espt_temp' .$this->authenticatedUser()->getUuid() . '.pdf';
        $pdf->Output($filename, 'F');

        // display the file contents in the browser instead of downloading it
        return $this->file($filename, _('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TeacherGroupRepository $teacherGroupRepository
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/rooms", name="espt_print_rooms")
     */
    public function printRooms(CreationSettingsRepository $settingsRepository, TeacherGroupRepository $teacherGroupRepository) : Response {
        $this->denyAccessUnlessGranted(Privilege::ADMIN);
        $settings = $settingsRepository->findFirst();

        // column titles
        $header = array(_('Room'), _('espt_teacher'));

        $groups = $teacherGroupRepository->findAll();
        $data = array();

        /** @var TeacherGroup $group */
        foreach ($groups as $group) {
            $data[] = array($group->getRoom(), $group);
        }

        //Sort by name, check by setting
        //sort by roomname afterwards

        $pdf = $this->buildPdf($settings, 20);
        $pdf->Ln(5);
        $pdf->setCustomFooterText("");
        $pdf->Table($header, $data, array(30, 130));

        $filename = '/tmp/espt_temp' .$this->authenticatedUser()->getUuid() . '.pdf';
        $pdf->Output($filename, 'F');

        // display the file contents in the browser instead of downloading it
        return $this->file($filename, _('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

}