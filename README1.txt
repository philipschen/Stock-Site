This README file will guide you through the usage and function of the provided files

===================================================================================
Before Running:

1. Install a web server that includes mysql, and PHP 5.6 or later
	1.a. We  recommend WampServer: http://www.wampserver.com/en/
2. Place folder 1_code in the correct folder for your server
	2.a. In the case of wampserver, paste the folder into c:/wampserver/www/
3. Launch the web server
4. Launch in order the files from 4_datacollection
	1. CreateDB.php
	2. CreateTestPortfolioUsers.php
	3. ReadCSVtoStockDB.php

Note: If using WampServer, run php files by entering into a web browser: localhost/filepath/yourfile.php
For Example: localhost/1_code/4_data_collection/RemoveDB.php
===================================================================================

Launching the Website:

1. Run the php file checklogin.php in a web browser
2. There is one test users
	username: user1
	password: test1

Note: If using WampServer, run php files by entering into a web browser: localhost/filepath/yourfile.php
For Example: localhost/1_code/checklogin.php
===================================================================================

The following files are included:

===================================================================================

CheckLogin.php
This file checks to see if a user enters the correct username or password, and redirects them to their portfolio.

===================================================================================

registeruser.php
This file checks registers a user, adding them to the database.

===================================================================================

portfolio.php
Displays the portfolio of the user logged in.

===================================================================================

predictionpage.php
Creates a compilation of the outputs and visualization of all algorithms including cup and handle pattern matching, RSI, and OBV.

===================================================================================

obvchartrender.php
Creates the graphical view of OBV algorithm.

===================================================================================

rsichartrender.php
Creates the graphical view of RSI algorithm.

===================================================================================

machartrender.php
Creates the graphical view of MA algorithm.

===================================================================================

Folder 4_data_collection
Contains files for data collection and creating the database. For more information, resd README4 in 4_data_collection
