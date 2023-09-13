<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = 'Metode validasi email harus melewati sebuah array.';
$lang['email_invalid_address'] = 'Alamat email tidak valid: %s';
$lang['email_attachment_missing'] = 'Tidak dapat menemukan lampiran email berikut: %s';
$lang['email_attachment_unreadable'] = 'Tidak dapat membuka lampiran berikut: %s';
$lang['email_no_recipients'] = 'Anda harus menyertakan penerima: To, Cc, or Bcc';
$lang['email_send_failure_phpmail'] = 'Tidak dapat mengirim email menggunakan PHP mail (). Server Anda mungkin tidak memiliki konfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_send_failure_sendmail'] = 'Tidak dapat mengirim email menggunakan PHP Sendmail. Server Anda mungkin tidak memiliki konfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_send_failure_smtp'] = 'Tidak dapat mengirim email menggunakan PHP SMTP. Server Anda mungkin tidak memiliki konfigurasi untuk mengirim email menggunakan metode ini.';
$lang['email_sent'] = 'Pesan Anda telah berhasil dikirim menggunakan protokol berikut: %s';
$lang['email_no_socket'] = 'Tidak dapat membuka soket untuk mengirim email. Silakan periksa pengaturan.';
$lang['email_no_hostname'] = 'You did not specify a SMTP hostname.';
$lang['email_smtp_error'] = 'The following SMTP error was encountered: %s';
$lang['email_no_smtp_unpw'] = 'Error: You must assign a SMTP username and password.';
$lang['email_failed_smtp_login'] = 'Failed to send AUTH LOGIN command. Error: %s';
$lang['email_smtp_auth_un'] = 'Failed to authenticate username. Error: %s';
$lang['email_smtp_auth_pw'] = 'Failed to authenticate password. Error: %s';
$lang['email_smtp_data_failure'] = 'Unable to send data: %s';
$lang['email_exit_status'] = 'Exit status code: %s';

/* End of file email_lang.php */
/* Location: ./system/language/english/email_lang.php */