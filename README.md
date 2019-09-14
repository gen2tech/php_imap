# PHP IMAP Reader
A PHP class that makes working with IMAP as easy as possible. 

This class is written to be chain-able so to create a logically fluent and easily readable way to access an IMAP mailbox. 

It simplifies the PHP IMAP_* library into a set of easy to read methods that do the heavy lifting for you.

It has been fully tested to work with PHP 5.3+, including **PHP 7.0.**

## Basic Usage


```<?php
// Create a php spl_autoload method of include the following
require 'imap/Email.php';
require 'imap/EmailAttachment.php';
require 'imap/Reader.php';

// Then use the classes like
use Infmvc\Mailer\Email;
use Infmvc\Mailer\EmailAttachment;
use Infmvc\Mailer\Reader;

After you have added the required classes.

In its simplest form, use the following to connect:

define('IMAP_HOST', '{mail.example.com:993/novalidate-cert/ssl}');
define('IMAP_USERNAME', 'username');
define('IMAP_PASSWORD', 'password');
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
```






## Other method of the class can be used as follows

```<?php
try{
    
    # create a new Reader object
    $imap = new Reader(IMAP_MAILBOX, IMAP_USERNAME, IMAP_PASSWORD, ATTACHMENT_PATH);

    # use one or more of the following chain-able methods to filter your email selection
    $imap
        ->folder($folder)           # alias for mailbox($mailbox)
        ->mailbox($mailbox)         # sets the mailbox to return emails from. Default = INBOX
        ->id($id)                   # retrieve a specific email by id
        ->recent()                  # get all RECENT emails
        ->flagged()                 # get all FLAGGED emails
        ->unflagged()               # get all UNFLAGGED emails
        ->unanswered()              # get all UNANSWERED emails
        ->deleted()                 # get all DELETED emails
        ->unread() 		    		# alias for UNSEEN()
        ->unseen()                  # get all UNSEEN emails
        ->from($email)              # get all emails from $email
        ->searchSubject($string)    # get all emails with $string in the subject line
        ->searchBody($string)       # get all emails with $string in the body
        ->searchText($string)       # get all emails with $string TEXT
        ->seen()                    # get all SEEN emails
        ->read() 		    		# alias for SEEN()
        ->newMessages()             # get all NEW emails
        ->oldMessages()             # get all OLD emails
        ->keyword($keyword)         # get all emails with $keyword KEYWORD
        ->unkeyword($keyword)       # get all emails without $keyword KEYWORD
        ->beforeDate($date)         # get all emails received before $date. *Date should be in a format that can be parsed by strtotime.*
        ->sinceDate($date)          # get all emails received since $date. *Date should be in a format that can be parsed by strtotime.*
        ->sentTo($to)               # get all emails sent to $to
        ->searchBCC($string)        # get all emails with $string in the BCC field
        ->searchCC($string)         # get all emails with $string in the CC field
        ->onDate($date)             # get all emails received on $date. *Date should be in a format that can be parsed by strtotime.*
        ->limit($limit)             # limit the number of emails returned to $limit for pagination
        ->page($page)               # used with limit to create pagination
        ->orderASC()                # order the emails returned in ASCending order
        ->orderDESC()               # order the emails returned in DESCendeing order
        ->all()                     # get all emails (default)
        ->get();                    # finally make the connection and retrieve the emails.
    
    # You can then loop through $imap->emails() for each email.
    foreach($imap->emails() as $email){

        # The email has been clean and formated.
        # see below.

    }
    
    # ... your code here ...

} catch (Exception $e){

    echo $e->getMessage();

}
```


### While looping through the returned emails, each email object can be used as below:


```<?php
    $email->isTo('mail@example.com');   # Return true if the email is to $email, else returns false

    $email->replyTo();              # Returns an array of Reply To email addresses (and names)

    $email->cc();                 	# Returns an array of CC email addresses (and names)

    $email->to();                   # Returns the recipient email address

    $email->id();                   # Returns the id of the email

    $email->size();                 # Returns the size of the email

    $email->date($format);        	# Returns the date in the $format specified. Default Y-m-d H:i:s

    $email->subject();          	# Returns the email subject

    $email->fromName();     		# Returns the sender's name, if set.

    $email->fromEmail();     		# Returns the sender's email address

    $email->plain();            	# Returns the plain text body of the email, if present

    $email->html();            		# Returns the html body of the email, if present

    $email->hasAttachments();       # Returns true/false based on if the email has attachments

    $email->attachments();      	# Returns an array of EmailAttachment objects

    $email->attachment($id);    	# Returns an attachment based on the given attachment $id

    $email->isRecent();   			# Returns true/false based on the recent flag

    $email->isUnseen();       		# Returns true/false based on the unseen flag

    $email->isFlagged();  			# Returns true/false based on the flagged flag

    $email->isAnswered(); 			# Returns true/false based on the answered flag

    $email->isDeleted();      		# Returns true/false based on the deleted flag

    $email->isDraft();          	# Returns true/false based on the draft flag
```


The $email->attachments(); method returns an array of attachments belonging to the email in Mailer\EmailAttachment object. The following methods are available for each attachment.

```<?php
# check if the current $email has any attachments.
	if($email->hasAttachments()){
	
		# get the attachments for the current $email in the	loop.
		$attachments = $email->attachments();
	
		# loop through the found attachments.
		foreach($attachments as $attachment){

			$attachment->id(); 			# Returns the attachments ID.

			$attachment->name(); 		    	# Returns the attachments name.

			$attachment->filePath(); 		# Returns the local file path for the attachment. This is based on the ATTACHMENT_PATH constant set in the imap config.

			$attachment->content();			# Returns the attachments content data.

			$attachment->type(); 			# Returns either 'attachment' or 'inline'.

		}

}	
```



