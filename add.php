<?php

require_once( 'conn.php' );
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'newevent') {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $stmt = $db->prepare('INSERT INTO ' . $events_table . ' '
                                 . '(name, description) VALUES (?,?) ');
            $stmt->execute(array($name, $desc));
        }
    }
    if ($action === 'newitem') {
        if ( isset($_POST['name']) && isset($_POST['event']) ) {
            $name = $_POST['name'];
            $event = $_POST['event'];
            $stmt = $db->prepare('INSERT INTO ' . $bid_card_table . ' '
                    . '(event, name, value, buyout, startprice, increment, donorname, buyername)'
                    . ' VALUES (?,?,?,?,?,?,?,?) ');
            $stmt->execute(array($event, $name, $_POST['value'], 
                                $_POST['buyout'], $_POST['start'], $_POST['inc'], 
                                $_POST['donorname'], $_POST['buyername']));
        }
    }
}
header('Location: index.php');

