<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Untitled Document</title>
        <?php require_once( 'conn.php' ); ?>
    </head>
    <body>
        <h4>Add An Event</h4>
        <form action="add.php" method="post">
            <input type="hidden" name="action" value="newevent" />
            Event Name: <input type="text" name="name" required/> <br />
            Event Description: <input type="text" name="desc" /> <br />
            <input type="submit" />
        </form>
        <h4>Current Events:</h4>
        <?php 
        $events = array();
        $stmt = $db->query("SELECT id, name, description FROM " . $events_table);
        $rows = $stmt->rowCount();
        if ($rows == 0)
            echo 'No events currently entered. <br />';
        else {
            echo '<div>';
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $events[$row['id']] = $row['name'];
                echo 'Name: ';
                echo $row['name'];
                echo '<br />';
                echo 'Description: ';
                echo $row['description'];
                echo '<br />';
                $stmt = $db->query("SELECT id, name, value FROM " . $bid_card_table . " WHERE event = " . $row['id']);
                $rows = $stmt->rowCount();
                if ($rows == 0)
                    echo 'No bid items currently created.<br />';
                else {
                    echo '<div>';
                    echo 'Bid cards: <br />';
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($items as $item) {
                        echo 'Name: ';
                        echo $item['name'];
                        echo '<br />';
                        echo 'Value ';
                        echo $item['value'];
                        echo '<br />';
                        echo '<a href="process.php?item=' . $item['id'] . '">Download Word documents for this item</a>';
                        echo '<br />';
                    }
                    echo '<br />';
                }
                echo '<br />';
            }
            echo '</div>';
        }
        if ( count($events) ) {
        ?>
        <h4>Add Bid Item</h4>
        <form action="add.php" method="post">
            <input type="hidden" name="action" value="newitem" />
            Select event: <select name="event" required>
                <option value="">Please select event</option>
                <?php
                foreach ($events as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                }
                ?>
            </select><br />
            Name: <input type="text" name="name" required /> <br />
            Value: <input type="text" name="value" /> <br />
            Start Price: <input type="text" name="start" /> <br />
            Buyout: <input type="text" name="buyout" /> <br />
            Increment: <input type="text" name="inc" /> <br />
            Donor name: <input type="text" name="donorname" /> <br />
            Buyer name: <input type="text" name="buyername" /> <br />
            <input type="submit" />
        </form>
        <?php 
        }
        ?>
    </body>
</html>
