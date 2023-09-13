<?php
defined('BASEPATH') || exit('No direct script access allowed');

$config['encrypto'] = 'tls';
$config['validate'] = true;
$config['host']     = 'mail.acumenbizcorp.com.sg';
$config['port']     = 993;
$config['username'] = 'enquiry@acumenbizcorp.com.sg';
$config['password'] = 'Syapac12345678#';

$config['folders'] = [
	'inbox'  => 'INBOX',
	'sent'   => 'Sent',
	'trash'  => 'Trash',
	'spam'   => 'Spam',
	'drafts' => 'Drafts',
];

$config['expunge_on_disconnect'] = false;

$config['cache'] = [
	'active'     => false,
	'adapter'    => 'file',
	'backup'     => 'file',
	'key_prefix' => 'imap:',
	'ttl'        => 60,
];
