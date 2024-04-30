<?php
session_start();    
require(__DIR__ . "/../../lib/functions.php");                  //UCID: LM457 
reset_session();                                                //Date: 3/31/2024                           

flash("Successfully logged out", "success");
redirect("login.php");                                          //UCID: LM457