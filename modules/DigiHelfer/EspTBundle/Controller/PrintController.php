<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\PdfCreator;
use DigiHelfer\EspTBundle\Security\Privilege;
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

    private function buildPdf(CreationSettings $settings, array $headers, array $data) : PdfCreator {
        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $subject = _('espt_on') . strftime('%A %e.%B %G', $settings->getStart()->getTimestamp())
            . " " . __('espt_start_end_time',
                $settings->getStart()->format('G:i'),
                $settings->getEnd()->format('G:i'));

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('IServ ' . _('EspT'));
        $pdf->SetTitle(_('EspT'));
        $pdf->SetSubject($subject);

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

        $pdf->Table($headers, $data);

        return $pdf;
    }


    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/teacher", name="espt_print_teacher")
     */
    public function printTeacher(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository) : Response {
        $this->denyAccessUnlessGranted(Privilege::TEACHER);
        $settings = $settingsRepository->findFirst();

        // column titles
        $header = array(_('Time'), _('Student'));

        $timeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());
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

        $pdf = $this->buildPdf($settings, $header, $data);

        $filename = '/tmp/espt_temp' .$this->authenticatedUser()->getUuid() . '.pdf';
        $pdf->Output($filename, 'F');

        // display the file contents in the browser instead of downloading it
        return $this->file($filename, _('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/student", name="espt_print_student")
     */
    public function printStudent(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository) : Response {
        $this->denyAccessUnlessGranted("ROLE_STUDENT");
        $settings = $settingsRepository->findFirst();

        // column titles
        $header = array(_('Time'), _('Teacher'));

        $timeslots = $timeslotRepository->findForUser($this->authenticatedUser());
        $data = array();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {
            $group = $timeslot->getGroup();
            if($group != null) {
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    $group
                );
            }else {
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    ''
                );
            }
        }

        $pdf = $this->buildPdf($settings, $header, $data);

        $filename = '/tmp/espt_temp' .$this->authenticatedUser()->getUuid() . '.pdf';
        $pdf->Output($filename, 'F');

        // display the file contents in the browser instead of downloading it
        return $this->file($filename, _('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

}