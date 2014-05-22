<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/star/inc/files.php');
//require_once($includes.'error.php');
require_once( 'Classes/PHPWord.php' );
$files = array();
if ( isset($_POST['item']) && isset($_POST['value']) ) {
	$PHPWord = new PHPWord();

	// Every element you want to append to the word document is placed in a section. So you need a section:
	$section = $PHPWord->createSection();

	// You can directly style your text by giving the addText function an array:
	$section->addText($_POST['item'], array('name'=>'Tahoma', 'size'=>32, 'bold'=>true));
	$section->addText('Value: ' . $_POST['value'], array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));
	
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	$objWriter->save('files/sign.docx');
	$files[] = 'files/sign.docx';
}
if ( isset($_POST['item']) && isset($_POST['value']) && isset($_POST['inc']) && isset($_POST['buyout']) ) {
	$PHPWord = new PHPWord();

	$template = $PHPWord->loadTemplate('templates/bid_card.docx');
	$template->setValue('item', $_POST['item']);
	$template->setValue('value', $_POST['value']);
	$template->setValue('buyout', $_POST['buyout']);
	$template->setValue('start', $_POST['start']);
	
	$template->save('files/card.docx');
	$files[] = 'files/card.docx';
}
if ( isset($_POST['item']) && isset($_POST['donorname']) ) {
	$PHPWord = new PHPWord();

	$template = $PHPWord->loadTemplate('templates/donor_letter.docx');
	$template->setValue('item', $_POST['item']);
	$template->setValue('name', $_POST['donorname']);
	
	$template->save('files/donor_letter.docx');
	$files[] = 'files/donor_letter.docx';
}
if ( isset($_POST['item']) && isset($_POST['buyername']) ) {
	$PHPWord = new PHPWord();

	$template = $PHPWord->loadTemplate('templates/purchase_letter.docx');
	$template->setValue('item', $_POST['item']);
	$template->setValue('name', $_POST['buyername']);
	
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
header('Content-type: application/zip'); // Please check this, i just guessed
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: '.filesize($file));
readfile($file);