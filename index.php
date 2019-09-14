<?php

require 'imap/Email.php';
require 'imap/EmailAttachment.php';
require 'imap/Reader.php';

use Infmvc\Mailer\Email;
use Infmvc\Mailer\EmailAttachment;
use Infmvc\Mailer\Reader;

define('IMAP_HOST', '{mail.joadescendants.com.ng:993/imap/ssl}');
define('IMAP_USERNAME', 'fmail@joadescendants.com.ng');
define('IMAP_PASSWORD', '1qaz@wsx3edc4rfV');
define('ATTACHMENT_PATH', __DIR__ . '/attachments/');

$imap = null;

try {
    $imap = new Reader(IMAP_HOST, IMAP_USERNAME, IMAP_PASSWORD, ATTACHMENT_PATH);
    
    $imap->limit(10)->get();

    foreach ($imap->emails() as $email) {
        echo '<div>';
            
        echo '<div>' . $email->fromEmail() . '</div>';
            
        echo '<div>' . $email->subject() . '</div>';
            
        echo '<div>' . $email->date('Y-m-d H:i:s') . '</div>';
		
        echo '<div>' . $email->html() . '</div>';
		
        echo '<div>' . $email->id() . '</div>';

        if ($email->hasAttachments()) {
            foreach ($email->attachments() as $attachment) {
                echo '<div>' . $attachment->filePath() . '</div>';
            }
        }
        
        #print_r($email->plain());
        #print_r($email->html());

        echo '</div><br/><br/><hr />';
    }
} catch (Exception $e) {
    die($e->getMessage());
}
