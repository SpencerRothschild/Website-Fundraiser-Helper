<?php
require_once( 'Classes/PHPWord.php' );
require_once( 'conn.php' );
if ( !isset($_GET['item']))
    header('Location: index.php');
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
$event_result = $event->fetch(PDO::FETCH_ASSOC);

$files = array();

//delete existing files
$old_files = glob('files/');
foreach($old_files as $old_file){
  if(is_file($old_file))
    unlink($old_file);
}
/** Generate bid item sign **/
if ( $result['name'] !== '' && $result['value'] !== '' && $result['value'] ) {
	$PHPWord = new PHPWord();
	$section = $PHPWord->createSection();
        
        //add information
	$section->addText($result['name'], array('name'=>'Tahoma', 'size'=>32, 'bold'=>true), array('align' => 'center'));
        if ( $result['description'] !== '')
            $section->addText($result['description'], array('name'=>'Tahoma', 'size'=>24, 'bold'=>true), array('align' => 'center'));
	$section->addText('Value: $' . $result['value'], array('name'=>'Tahoma', 'size'=>16, 'bold'=>true), array('align' => 'center'));
	
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	$objWriter->save('files/sign.docx');
	$files[] = 'files/sign.docx';
}
/** Generate bid item auction card **/
if ( $result['name'] !== '' && $result['value'] !== '' && $result['startprice'] !== '' && $result['buyout'] !== '' && $result['increment'] !== '' ) {
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
/** Generate bid item donor letter **/
if ( $result['name'] !== '' && $result['donorname'] !== '' ) {
	$PHPWord = new PHPWord();
        
	$template = $PHPWord->loadTemplate('templates/donor_letter.docx');
	$template->setValue('item', $result['name']);
	$template->setValue('name', $result['donorname']);
        
        $date = date('F jS, Y');
	$template->setValue('date', $date);
        
        $template->setValue('event', $event_result['name']);
        
	$template->save('files/donor_letter.docx');
	$files[] = 'files/donor_letter.docx';
}
/** Generate bid item purchase letter **/
if ( $result['name'] !== '' && $result['buyername'] !== '' ) {
	$PHPWord = new PHPWord();
        
	$template = $PHPWord->loadTemplate('templates/purchase_letter.docx');
	$template->setValue('item', $result['name']);
	$template->setValue('name', $result['buyername']);	
        
        $date = date('F jS, Y');
        $template->setValue('date', $date);
        
        $template->setValue('event', $event_result['name']);
        
	$template->save('files/purchase_letter.docx');
	$files[] = 'files/purchase_letter.docx';
}
$zip = new ZipArchive();
$zip->open('files/' . $event_result['name'] . ' - ' . $result['name'] . '.zip', ZipArchive::OVERWRITE);
foreach ($files as $file) {
	$zip->addFile($file, str_replace('files/','',$file));
}
$zip->close();
$file = 'files/' . $event_result['name'] . ' - ' . $result['name'] . '.zip';
header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: '.filesize($file));
readfile($file);