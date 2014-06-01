<?php
require_once( 'Classes/PHPWord.php' );
require_once( 'conn.php' );
if ( !isset($_GET['item']))
    header('Location: index.php');
$item = $_GET['item'];
$stmt = $db->prepare('SELECT * FROM ' . $bid_card_table . ' WHERE id = ?');
$stmt->execute(array($item));
$rows = $stmt->rowCount();
if ($rows == 0)
    header('Location: index.php');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$files = array();
if ( isset($result['name']) && isset($result['value']) ) {
	$PHPWord = new PHPWord();

	// Every element you want to append to the word document is placed in a section. So you need a section:
	$section = $PHPWord->createSection();

	// You can directly style your text by giving the addText function an array:
	$section->addText($result['name'], array('name'=>'Tahoma', 'size'=>32, 'bold'=>true));
	$section->addText('Value: $' . $result['value'], array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));
	
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	$objWriter->save('files/sign.docx');
	$files[] = 'files/sign.docx';
}
if ( isset($result['name']) && isset($result['value']) && isset($result['startprice']) && isset($result['buyout']) && isset($result['increment']) ) {
	$PHPWord = new PHPWord();

	$template = $PHPWord->loadTemplate('templates/bid_card.docx');
	$template->setValue('item', $result['name']);
	$template->setValue('value', $result['value']);
	$template->setValue('buyout', $result['buyout']);
	$template->setValue('start', $result['startprice']);
	$template->setValue('inc1', $result['startprice'] + $result['increment']);
        $template->setValue('inc2', $result['startprice'] + 2*$result['increment']);
        $template->setValue('inc3', $result['startprice'] + 3*$result['increment']);
        $template->setValue('inc4', $result['startprice'] + 4*$result['increment']);
        $template->setValue('inc5', $result['startprice'] + 5*$result['increment']);
	$template->save('files/card.docx');
	$files[] = 'files/card.docx';
}
if ( isset($result['name']) && isset($result['donorname']) && isset($result['event'])) {
	$PHPWord = new PHPWord();
        
	$template = $PHPWord->loadTemplate('templates/donor_letter.docx');
	$template->setValue('item', $result['name']);
	$template->setValue('name', $result['donorname']);
        
        $date = date('F j, Y');
	$template->setValue('date', $date);
        
        $event = $db->prepare('SELECT name FROM ' . $events_table . ' WHERE id = ?');
        $event->execute(array($result['event']));        
        $event_result = $event->fetch(PDO::FETCH_ASSOC);
        $template->setValue('event', $event_result['name']);
        
        
	$template->save('files/donor_letter.docx');
	$files[] = 'files/donor_letter.docx';
}
if ( isset($result['name']) && isset($result['buyername']) && isset($result['event']) ) {
	$PHPWord = new PHPWord();
        
	$template = $PHPWord->loadTemplate('templates/purchase_letter.docx');
	$template->setValue('item', $result['name']);
	$template->setValue('name', $result['buyername']);	
        
        $date = date('F j, Y');
        $template->setValue('date', $date);
        
        $event = $db->prepare('SELECT name FROM ' . $events_table . ' WHERE id = ?');
        $event->execute(array($result['event']));        
        $event_result = $event->fetch(PDO::FETCH_ASSOC);
        $template->setValue('event', $event_result['name']);
        
	$template->save('files/purchase_letter.docx');
	$files[] = 'files/purchase_letter.docx';
}
$zip = new ZipArchive();
$zip->open('files.zip', ZipArchive::OVERWRITE);
foreach ($files as $file) {
	$zip->addFile($file);
}
$zip->close();
$file = 'files.zip';
header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: '.filesize($file));
readfile($file);