<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\PdfCreator;
use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\CoreBundle\Controller\AbstractPageController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route('/espt/print')
 */
final class PrintController extends AbstractPageController {


    /**
     * @param CreationSettings $settings
     * @param TimeslotRepository $timeslotRepository
     * @Route('/teacher')
     */
    public function printTeacher(CreationSettings $settings, TimeslotRepository $timeslotRepository) {
        $this->denyAccessUnlessGranted(Privilege::TEACHER);

        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('IServ Elternsprechtagsmodul');
        $pdf->SetTitle(_('EspT'));
        $pdf->SetSubject(_('EspT') . _('espt_on') . $settings->getStart()->format('l j.n.Y'));

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setFooterData();

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

        // column titles
        $header = array(_('Time'), _('Student'));

        $timeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());
        $data = array();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {
            $user = $timeslot->getUser();
            if($user != null) {
                $groups = [];
                foreach ($user->getGroups() as $group) {
                    $groups[] = $group->getName();
                }
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    $user->getNameByFirstname() . " " . implode(', ', $groups)
                );
            }else {
                $data[] = array(
                    __('espt_start_end_time', $timeslot->getStart()->format('G:i'), $timeslot->getEnd()->format('G:i')),
                    _('espt_timeslot_type')
                );
            }
        }

        $pdf->Table($header, $data);

        $pdf->Output(_('EspT') . $settings->getStart()->format('j.n.Y') . '.pdf', 'I');
    }

}