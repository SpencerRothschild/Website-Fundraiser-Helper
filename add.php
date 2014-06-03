<?php
require_once( 'conn.php' );
$header = 'Location: index.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'newevent') {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $stmt = $db->prepare('INSERT INTO ' . $events_table . ' '
                                 . '(name, description, location, date) VALUES (?,?,?,?) ');
            $stmt->execute(array($name, $desc, $_POST['location'], $_POST['date']));
        }
    }
    if ($action === 'newitem') {
        if ( isset($_POST['name']) && isset($_POST['event']) ) {
            $name = $_POST['name'];
            $event = $_POST['event'];
            $stmt = $db->prepare('INSERT INTO ' . $bid_card_table . ' '
                    . '(event, name, description, value, buyout, startprice, increment, boughtprice, donorname, buyername, message)'
                    . ' VALUES (?,?,?,?,?,?,?,?,?,?,?) ');
            $stmt->execute(array($event, $name, $_POST['description'], $_POST['value'], 
                                $_POST['buyout'], $_POST['start'], $_POST['inc'], $_POST['boughtprice'], 
                                $_POST['donorname'], $_POST['buyername'], $_POST['message']));
            $header .= '?event=' . $_POST['event'];
        }
    }
}
header($header);

