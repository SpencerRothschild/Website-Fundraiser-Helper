<?php
require_once( 'conn.php' );
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'excel' && isset($_GET['event'])) {
        $event = $_GET['event'];
        require_once( 'Classes/PHPExcel.php' );
        
        $eventInfo = $db->prepare('SELECT name, description, location, date FROM ' . $events_table . ' WHERE id = ?');
        $eventInfo->execute(array($event));
        $eventResult = $eventInfo->fetch(PDO::FETCH_ASSOC);
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("It's All About the Kids Foundation");
        $objPHPExcel->getProperties()->setTitle($eventResult['name']);
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Event name: ' . $eventResult['name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Event description: ' . $eventResult['description']);
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Event location: ' . $eventResult['location']);
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Event date: ' . $eventResult['date']);
        
        $rowIndex = 6;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowIndex, 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowIndex, 'Description');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowIndex, 'Value');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowIndex, 'Starting Bid');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowIndex, 'Increment');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowIndex, 'Buyout');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowIndex, 'Bought Price');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowIndex, 'Donor Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowIndex, 'Personal Message for Donor');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowIndex, 'Buyer Name');        
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowIndex, 'Personal Message for Buyer');
        foreach(range('A','K') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $stmt = $db->prepare('SELECT * FROM ' . $bid_card_table . ' WHERE event = ?');
        $stmt->execute(array($event));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowIndex++;
        foreach ($result as $row) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowIndex, $row['name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowIndex, $row['description']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowIndex, $row['value']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowIndex, $row['startprice']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowIndex, $row['increment']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowIndex, $row['buyout']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowIndex, $row['boughtprice']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowIndex, $row['donorname']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowIndex, $row['donormessage']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowIndex, $row['buyername']);        
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowIndex, $row['buyermessage']);
            $rowIndex++;
        }
        
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:K4');
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="' . $eventResult['name'] . '.xlsx"');

        // Write file to the browser
        $objWriter->save('php://output');
    }
    if ($action === 'allword' && isset($_GET['item'])) {
        require_once( 'Classes/PHPWord.php' );
        $item = $_GET['item'];
        //get bid item information
        $stmt = $db->prepare('SELECT * FROM ' . $bid_card_table . ' WHERE id = ?');
        $stmt->execute(array($item));
        $rows = $stmt->rowCount();
        if ($rows == 0)
            header('Location: index.php');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //get information about event
        $event = $db->prepare('SELECT name FROM ' . $events_table . ' WHERE id = ?');
        $event->execute(array($result['event']));
        $eventResult = $event->fetch(PDO::FETCH_ASSOC);

        $files = array();

        //delete existing files
        $old_files = glob('files/');
        foreach ($old_files as $old_file) {
            if (is_file($old_file))
                unlink($old_file);
        }
        /** Generate bid item sign * */
        if ($result['name'] !== '' && $result['value'] !== '' && $result['value']) {
            $PHPWord = new PHPWord();
            $section = $PHPWord->createSection();

            //add information
            $section->addText($result['name'], array('name' => 'Tahoma', 'size' => 32, 'bold' => true), array('align' => 'center'));
            if ($result['description'] !== '')
                $section->addText($result['description'], array('name' => 'Tahoma', 'size' => 24, 'bold' => true), array('align' => 'center'));
            $section->addText('Value: $' . $result['value'], array('name' => 'Tahoma', 'size' => 16, 'bold' => true), array('align' => 'center'));

            $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
            $objWriter->save('files/sign.docx');
            $files[] = 'files/sign.docx';
        }
        /** Generate bid item auction card * */
        if ($result['name'] !== '' && $result['value'] !== '' && $result['startprice'] !== '' && $result['buyout'] !== '' && $result['increment'] !== '') {
            $PHPWord = new PHPWord();

            $template = $PHPWord->loadTemplate('templates/bid_card.docx');
            $template->setValue('item', $result['name']);
            $template->setValue('value', $result['value']);
            $template->setValue('buyout', $result['buyout']);
            $template->setValue('start', $result['startprice']);
            $template->setValue('inc1', $result['startprice'] + $result['increment']);
            $template->setValue('inc2', $result['startprice'] + 2 * $result['increment']);
            $template->setValue('inc3', $result['startprice'] + 3 * $result['increment']);
            $template->setValue('inc4', $result['startprice'] + 4 * $result['increment']);
            $template->setValue('inc5', $result['startprice'] + 5 * $result['increment']);
            $template->save('files/card.docx');
            $files[] = 'files/card.docx';
        }
        /** Generate bid item donor letter * */
        if ($result['name'] !== '' && $result['donorname'] !== '' && $result['value'] !== '') {
            $PHPWord = new PHPWord();

            $template = $PHPWord->loadTemplate('templates/donor_letter.docx');
            $template->setValue('item', $result['name']);
            $template->setValue('name', $result['donorname']);

            $date = date('F jS, Y');
            $template->setValue('date', $date);

            $template->setValue('event', $eventResult['name']);
            $template->setValue('value', $result['value']);
            if ($result['donormessage'] !== '')
                $template->setValue('message', "<w:br/>" . $result['donormessage'] . "<w:br/>");
            else
                $template->setValue('message', "");
            $template->save('files/donor_letter.docx');
            $files[] = 'files/donor_letter.docx';
        }
        /** Generate bid item purchase letter * */
        if ($result['name'] !== '' && $result['buyername'] !== '') {
            $PHPWord = new PHPWord();

            $template = $PHPWord->loadTemplate('templates/purchase_letter.docx');
            $template->setValue('item', $result['name']);
            $template->setValue('name', $result['buyername']);

            $date = date('F jS, Y');
            $template->setValue('date', $date);

            $template->setValue('event', $eventResult['name']);
            if ($result['buyermessage'] !== '')
                $template->setValue('message', "<w:br/>" . $result['buyermessage'] . "<w:br/>");
            else
                $template->setValue('message', "");
            $template->save('files/purchase_letter.docx');
            $files[] = 'files/purchase_letter.docx';
        }
        $zip = new ZipArchive();
        $zip->open('files/' . $eventResult['name'] . ' - ' . $result['name'] . '.zip', ZipArchive::OVERWRITE);
        foreach ($files as $file) {
            $zip->addFile($file, str_replace('files/', '', $file));
        }
        $zip->close();
        $file = 'files/' . $eventResult['name'] . ' - ' . $result['name'] . '.zip';
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
}
else
    header('Location: index.php');
