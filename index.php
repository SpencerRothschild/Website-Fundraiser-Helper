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
            body{
                background: -webkit-gradient(
                    linear,
                    left top,
                    left bottom,
                    color-stop(0, #DC143C),
                    color-stop(1, #FFFFFF)
                    ) no-repeat;
                background: -o-linear-gradient(bottom, #DC143C 0%, #FFFFFF 100%) no-repeat;
                background: -moz-linear-gradient(bottom, #DC143C 0%, #FFFFFF 100%) no-repeat;
                background: -webkit-linear-gradient(bottom, #DC143C 0%, #FFFFFF 100%) no-repeat;
                background: -ms-linear-gradient(bottom, #DC143C 0%, #FFFFFF 100%) no-repeat;
                background: linear-gradient(to bottom, #DC143C 0%, #FFFFFF 100%) no-repeat;
                font: 17px/19px Roboto, sans serif;
            }
            #wrapper{width:90%; margin:0 auto}
            .toppanel{
                padding:5px;
                text-align:center;
                background: rgba(255,255,255,0.8);
                border: 5px solid rgba(255,255,255,0.5);
                border-radius: 5px;
                margin:0px 0px 5px 0px;
            }
            .leftpanel{
                width:19%; 
                float:left; 
                padding:5px;
                background: rgba(255,255,255,0.8);
                border: 5px solid rgba(255,255,255,0.5);
                border-radius: 5px;
                margin:0px 5px 0px 0px;
            }
            .rightpanel{
                overflow:auto;
                padding:5px;
                background: rgba(255,255,255,0.8);
                border: 5px solid rgba(255,255,255,0.5);
                border-radius: 5px;
                margin:0px 0px 0px 5px;
            }
        </style>
        <?php require_once( 'conn.php' ); ?>
    </head>
    <body>
        <div >
            <div id="wrapper">
                <div class="toppanel">
                    <h2>Fundraiser Management </h2>
                    <img src="images/boy.jpg" title="cartoon boy" />
                    <img src="images/girl.jpg" title="cartoon girl" />
                </div>
                <div class="leftpanel">
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
                <div class="rightpanel">
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
                            <h4>Event details</h4>
                            <?php
                            foreach ($event as $row) {                            
                            ?>
                            <form action="update.php" method="post">
                                <input type="hidden" name="action" value="updateevent" />
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                                Event Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"required /> <br />
                                Event Description: <input type="text" name="description" value="<?php echo $row['description']; ?>" /> <br />
                                Event Location: <input type="text" name="location" value="<?php echo $row['location']; ?>" /> <br />
                                Event Date: <input type="date" name="date" value="<?php echo $row['date']; ?>"/> <br />
                                <input type="submit" value="Update Event"/>
                            </form>
                            <a href="download.php?action=excel&event=<?php echo $row['id']; ?>">Download Excel</a>
                            <h4>Add bid item</h4>
                            <form action="add.php" method="post">
                                <input type="hidden" name="event" value="<?php echo $row['id']; ?>" />
                                <input type="hidden" name="action" value="newitem" />
                                <table style=""widthLauto">
                                    <tr><td>Name</td><td>Description</td><td>Value</td><td>Start Price</td><td>Buyout</td><td>Increment</td><td>Bought Price</td><td>Donor Name</td><td>Buyer Name</td></tr>
                                    <tr>
                                        <td><input type="text" name="name" required /></td>
                                        <td><input type="text" name="description" /></td>
                                        <td><input type="text" name="value" /></td>
                                        <td><input type="text" name="start" /></td>
                                        <td><input type="text" name="buyout" /></td>
                                        <td><input type="text" name="inc" /></td>
                                        <td><input type="text" name="boughtprice" /></td>
                                        <td><input type="text" name="donorname" /></td>
                                        <td><input type="text" name="buyername" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Personal Message for donor</td>
                                        <td colspan="7"><textarea name="donormessage"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Personal Message for buyer</td>
                                        <td colspan="6"><textarea name="buyermessage"></textarea></td>
                                        <td><input type="submit" value="Add Item" /></td>
                                    </tr>
                                </table>
                            </form>                            
                            <?php
                            }
                            ?>                                 
                            <h4>Current bid items</h4>                            
                            <?php
                            $stmt = $db->prepare("SELECT id, event, name, description, value, startprice, buyout, increment, boughtprice, donorname, buyername, donormessage, buyermessage FROM " . $bid_card_table . " WHERE event = ?");
                            $stmt->execute(array($_GET['event']));
                            $item_rows = $stmt->rowCount();
                            if ($item_rows == 0)
                                echo 'No bid items currently created.<br />';
                            else {
                                ?>
                                <table style="width:auto">
                                <tr><td>Name</td><td>Description</td><td>Value</td><td>Start Price</td><td>Buyout</td><td>Increment</td><td>Bought Price</td><td>Donor Name</td><td>Buyer Name</td></tr>
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
                                        <td colspan="2">Personal Message for donor</td>
                                        <td colspan=7><textarea rows=1 name="donormessage"><?php echo $item['donormessage'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Personal Message for buyer</td>
                                        <td colspan=6><textarea rows=1 name="buyermessage"><?php echo $item['buyermessage'];?></textarea></td>
                                        <td><input type="submit" value="Update" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan=9><a href="download.php?action=allword&item=<?php echo $item['id'];?>">Download Word documents for this item</a></td>
                                    </tr>
                                    <tr style="border:none"><td colspan="9" style="border:none">&nbsp</td></tr>
                                    </form>
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
                <div style="clear: both;">&nbsp;</div>
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
