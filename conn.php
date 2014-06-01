<?php
$db = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$db->exec("CREATE DATABASE IF NOT EXISTS iaatk");
$db->exec("use iaatk");

$events_table = 'iaatk_events';
$bid_card_table = 'iaatk_bid_items';
//$db->exec("DROP TABLE IF EXISTS " . $bid_card_table );
//$db->exec("DROP TABLE IF EXISTS " . $events_table );
$sql = "CREATE TABLE IF NOT EXISTS " . $events_table . " ("
        . "id           INT     AUTO_INCREMENT  NOT NULL,"
        . "name         TEXT    NOT NULL,"
        . "description  TEXT,"
        . "PRIMARY KEY (id)"
        . ")";
$db->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS " . $bid_card_table . " ("
        . "id           INT     AUTO_INCREMENT  NOT NULL,"
        . "event        INT     NOT NULL,"
        . "name         TEXT    NOT NULL,"
        . "value        FLOAT,"
        . "startprice   FLOAT,"
        . "buyout       FLOAT,"
        . "increment    FLOAT,"
        . "donorname    TEXT,"
        . "buyername    TEXT,"
        . "PRIMARY KEY (id),"
        . "FOREIGN KEY (event) REFERENCES " . $events_table . "(id)"
        . ")";
$db->exec($sql);