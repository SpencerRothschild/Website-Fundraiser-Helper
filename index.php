<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>It's All About The Kids Fundraiser Management</title>
        <style>
            table input{width:90%}
            table textarea{width:60%}
            table {border-collapse: collapse;}
            table tr{border:1px solid}
            table tr td{border:1px solid; padding: 2px}
        </style>
        <?php require_once( 'conn.php' ); ?>
    </head>
    <body>
        <div>
            <div style="width:90%; margin:0 auto">
                <div style="text-align:center">
                    Fundraiser Management
                </div>
                <div style="width:15%; float:left; border: 1px solid; padding:5px">
                    <a href="#" onclick="toggleVisibility('addevent');">Click to add event</a>
                    <div id="addevent" style="display: none;">                    
                    <form action="add.php" method="post">
                        <input type="hidden" name="action" value="newevent" />
                        Event Name: <input type="text" name="name" required /> <br />
                        Event Description: <input type="text" name="desc" /> <br />
                        Event Location: <input type="text" name="location" /> <br />
                        Event Date: <input type="date" name="date" /> <br />
                        <input type="submit" />
                    </form>
                    </div>
                    <h4>Current Events:</h4>
                    <?php
                        $events = array();
                        $stmt = $db->query("SELECT id, name, date FROM " . $events_table);
                        $event_rows = $stmt->rowCount();
                        if ($event_rows == 0)
                            echo 'No events currently entered. <br />';
                        else {
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($results as $row) {
                                echo "<a href='index.php?event=" . $row['id'] . "'>" . $row['name'] . " on " . date('F j, Y', strtotime($row['date'])) . "</a><br />";
                            }
                        }
                    ?>
                </div>
                <div style="width:80%; float:right; border: 1px solid; padding:5px">
                    <?php
                        if (isset($_GET['event'])) {
                            $stmt = $db->prepare("SELECT id, name, description, location, date FROM " . $events_table . " WHERE id = ?");
                            $stmt->execute(array($_GET['event']));
                            $specific_event_rows = $stmt->rowCount();
                            if ($specific_event_rows == 0)
                                echo 'This event does not exist';
                            else {
                            $event = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <a href="#" onclick="toggleVisibility('additem');">Click to add bid item</a>
                            <div id="additem" style="display: none;">   
                            <form action="add.php" method="post">
                                <input type="hidden" name="event" value="<?php echo $event[0]['id']; ?>" />
                                <input type="hidden" name="action" value="newitem" />
                                Name: <input type="text" name="name" required /> <br />
                                Description: <input type="text" name="description" /> <br />
                                Value: <input type="text" name="value" /> <br />
                                Start Price: <input type="text" name="start" /> <br />
                                Buyout: <input type="text" name="buyout" /> <br />
                                Increment: <input type="text" name="inc" /> <br />
                                Bought price: <input type="text" name="boughtprice" /><br />
                                Donor name: <input type="text" name="donorname" /> <br />
                                Buyer name: <input type="text" name="buyername" /> <br />
                                Personal Message: <textarea name="message"></textarea> <br />
                                <input type="submit" />
                            </form>
                            </div>
                            <h4>Event details</h4>
                            <?php
                            foreach ($event as $row) {                            
                            ?>
                            <form action="update.php" method="post">
                            <input type="hidden" name="action" value="updateevent" />
                            <input type="hidden" name="id" value="<?php echo $row['id'];?>" />
                            Event Name: <input type="text" name="name" value="<?php echo $row['name'];?>"required /> <br />
                            Event Description: <input type="text" name="description" value="<?php echo $row['description'];?>" /> <br />
                            Event Location: <input type="text" name="location" value="<?php echo $row['location'];?>" /> <br />
                            Event Date: <input type="date" name="date" value="<?php echo $row['date'];?>"/> <br />
                            <input type="submit" value="Update Event"/>
                            </form>
                            <?php
                            }
                            ?>
                            <h4>Current bid items</h4>
                            <?php
                            $stmt = $db->prepare("SELECT id, event, name, description, value, startprice, buyout, increment, boughtprice, donorname, buyername, message FROM " . $bid_card_table . " WHERE event = ?");
                            $stmt->execute(array($_GET['event']));
                            $item_rows = $stmt->rowCount();
                            if ($item_rows == 0)
                                echo 'No bid items currently created.<br />';
                            else {
                                ?>
                                <table style="width:auto">
                                <tr><td>Name</td><td>Description</td><td>Value</td><td>Start Price</td><td>Buyout</td><td>Increment</td><td>Bought Price</td><td>Donor Name</td><td>Buyer Name</td><td></td></tr>
                                <?php
                                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($items as $item) {
                                    ?>
                                    <form action="update.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $item['id'];?>" />
                                    <input type="hidden" name="action" value="updateitem" />
                                    <input type="hidden" name="event" value="<?php echo $item['event'];?>" />
                                    <tr>
                                    <td><input type="text" name="name" value="<?php echo $item['name'];?>" /></td>
                                    <td><input type="text" name="description" value="<?php echo $item['description'];?>" /></td>
                                    <td><input type="text" name="value" value="<?php echo $item['value'];?>" /></td>
                                    <td><input type="text" name="startprice" value="<?php echo $item['startprice'];?>" /></td>
                                    <td><input type="text" name="buyout" value="<?php echo $item['buyout'];?>" /></td>
                                    <td><input type="text" name="increment" value="<?php echo $item['increment'];?>" /></td>
                                    <td><input type="text" name="boughtprice" value="<?php echo $item['boughtprice'];?>" /></td>
                                    <td><input type="text" name="donorname" value="<?php echo $item['donorname'];?>" /></td>
                                    <td><input type="text" name="buyername" value="<?php echo $item['buyername'];?>" /></td>
                                    </tr>
                                    <tr>
                                    <td colspan=8>Personal Message: <textarea rows=1 name="message"><?php echo $item['message'];?></textarea></td>
                                    <td><input type="submit" value="Update" /></td>
                                    </tr>
                                    <td colspan=9><a href="process.php?item=<?php echo $item['id'];?>">Download Word documents for this item</a></td>
                                    </tr>
                                    </form>
                                    <tr><td colspan="9"></td></tr>
                                    <?php
                                }
                                ?>
                                </table>
                                <?php
                                }         
                            }
                        }
                        else {
                            echo 'Select an event to view bid cards.';
                        }
                    ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        function toggleVisibility(div){
            if (document.getElementById(div).style.display === 'none')
                document.getElementById(div).style.display = 'block';
            else
                document.getElementById(div).style.display = 'none';
        }
        </script>
    </body>
</html>
