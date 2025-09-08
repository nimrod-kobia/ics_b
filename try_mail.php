<?php
require 'ClassAutoLoad.php';

$mailCnt = [
    'name_from' => 'Nimrod Kobia',
    'mail_from' => 'nimrodkobia066@gmail.com',
    'name_to' => 'ICS B Student',
    'mail_to' => 'nimrod.kobia@strathmore.edu',
    'subject' => 'Hello From ICS B',
    'body' => 'Welcome to ICS B! <br> This is a new semester. Let\'s have fun together.'
];

$ObjSendMail->Send_Mail($conf, $mailCnt);