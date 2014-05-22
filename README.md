Website-Fundraiser-Helper
=========================

Setup for Netbeans instructions:

1. Download XAMPP server. https://www.apachefriends.org/index.html

  Only need to select PHP, MySQL, Apache when selecting components.

2. Download Netbeans PHP/HTML5. https://netbeans.org/downloads/index.html

3. Start Netbeans.

4. Pull from this repository By going to Team > Git > Clone. Use this repositories HTTPS url and your credentials.

5. Right click on the project in the project Explorer and click on Properties.
  
  A) Click on Sources in the left pane.

  B) Check copy files from Sources Folder to another location and set the location to C:\xampp\htdocs\
  
  C) Click on Run Configuration in the left pane.
  
  D) Set project URL to http://localhost/Website-Fundraiser-Helper
  
  E) Set indexfile to index.php
  
  F) Click Ok.

6. Start the XAMPP Control Panel and Start Apache and MySQL. 

  Skype uses the same ports as Apache, so if you Apache does not start due to Skype, go to Skype>Tools>Options>Advanced>Connections and uncheck ports 80 and 443.
  
7. Go back to netbeans and click the green play button on top or go to your browser and type in http://localhost/Website-Fundraiser-Helper

8. Website should work.
