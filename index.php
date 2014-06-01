<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>It's All About The Children Fundraiser Management</title>
        <?php require_once( 'conn.php' ); ?>
    </head>
    <body>
        <h4>Add An Event</h4>
        <form action="add.php" method="post">
            <input type="hidden" name="action" value="newevent" />
            Event Name: <input type="text" name="name" required /> <br />
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
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $events[$row['id']] = $row['name'];
                echo '<table border="1" cellspacing="2" cellpadding="2" style="padding:5px">';
                echo '<tr><td>Event Name</td><td>Event Description</td></tr>';
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                
                echo '</tr>';
                $stmt = $db->query("SELECT id, name, value, startprice, buyout, increment, donorname, buyername FROM " . $bid_card_table . " WHERE event = " . $row['id']);
                $rows = $stmt->rowCount();
                if ($rows == 0)
                    echo 'No bid items currently created.<br />';
                else {
                    echo '<tr><td colspan="10">Bid Cards</td></tr>';
                    echo '<tr><td>Name</td><td>Value</td><td>Start Price</td><td>Buyout</td><td>Increment</td><td>Donor Name</td><td>Buyer Name</td><td></td></tr>';
                    
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($items as $item) {
                        echo '<tr>';
                        echo '<td>' . $item['name'] . '</td>';
                        echo '<td>' . $item['value'] . '</td>';
                        echo '<td>' . $item['startprice'] . '</td>';
                        echo '<td>' . $item['buyout'] . '</td>';
                        echo '<td>' . $item['increment'] . '</td>';
                        echo '<td>' . $item['donorname'] . '</td>';
                        echo '<td>' . $item['buyername'] . '</td>';
                        echo '<td><a href="process.php?item=' . $item['id'] . '">Download Word documents for this item</a></td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
            }
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
