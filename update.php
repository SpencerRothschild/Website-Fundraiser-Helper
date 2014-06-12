<?php
require_once( 'conn.php' );
$header = 'Location: index.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'updateevent') {
        if (isset($_POST['id'])) {
            $name = $_POST['name'];
            $stmt = $db->prepare('UPDATE ' . $events_table . ' SET '
                                 . 'name = ?, description = ?, location = ?, date =?'
                                 . ' WHERE id = ?');
            $stmt->execute(array($name, $_POST['description'], $_POST['location'], $_POST['date'], $_POST['id']));
            $header .= '?event=' . $_POST['id'];
        }
    }
    if ($action === 'updateitem') {
        if ( isset($_POST['id']) ) {
            $name = $_POST['name'];
            $stmt = $db->prepare('UPDATE ' . $bid_card_table . ' SET '
                    . 'name = ?, description = ?, value = ?, buyout = ?, startprice = ?, ' 
                    . 'increment = ?, boughtprice = ?, donorname = ?, buyername = ?, donormessage = ?, buyermessage = ? '
                    . ' WHERE id = ?');
            $stmt->execute(array($name, $_POST['description'], $_POST['value'], 
                                $_POST['buyout'], $_POST['startprice'], $_POST['increment'], $_POST['boughtprice'], 
                                $_POST['donorname'], $_POST['buyername'], $_POST['donormessage'], $_POST['buyermessage'], $_POST['id']));
            $header .= '?event=' . $_POST['event'];
        }
    }
}
header($header);

