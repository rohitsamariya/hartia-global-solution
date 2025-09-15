<?php
/* Set e-mail recipient */
$myemail = "info@hartiaglobal.com";

/* Check all form inputs using check_input function */
$yourname = check_input($_POST['name'], "Enter your name");
$email = check_input($_POST['email'], "Enter your email (Invalid format)"); 
// Validate email format

$number = check_input($_POST['number'], "Enter your phone number");
$joining_date = check_input($_POST['joining-date'], "Enter your joining date");

// Attach files to the email
$attachments = [
  'CV' => $_FILES['cv'],
  'Aadhaar' => $_FILES['aadhaar'],
  'PAN' => $_FILES['pan'],
  'Qualification Certificates' => $_FILES['qualification'],
];

/* Prepare the email headers and body */
$boundary = md5("sanity" . time());
$headers = "From: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= "Employee Application Form!\n\n";
$body .= "Your details:\n\n";
$body .= "Name  : $yourname\n";
$body .= "Email : $email\n";
$body .= "Phone Number : $number\n";
$body .= "Joining Date : $joining_date\n\n";

/* Validate and Attach Files */
foreach ($attachments as $name => $file) {
  if ($file['error'] === UPLOAD_ERR_OK) {
    // Validate file type
    $allowed_types = [
      'application/pdf',
      'image/jpeg',
      'image/png',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // docx
    ];
    $file_type = mime_content_type($file['tmp_name']); 
    if (!in_array($file_type, $allowed_types)) {
      show_error("$name file type is not allowed.");
    }

    // Attach file
    $file_content = file_get_contents($file['tmp_name']);
    $file_name = $file['name'];
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($file_content)) . "\r\n";
  } else {
    show_error("$name file is required and must be uploaded.");
  }
}

/* End of the email body */
$body .= "--$boundary--";

/* Send the email with attachments */
if (mail($myemail, "New Employee Application", $body, $headers)) {
  header('Location: thank-you.html'); // Redirect to thank-you.html
  exit(); 
} else {
  show_error("Unable to send email. Please try again later.");
}

/* Functions */
function check_input($data, $problem = '')
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  if ($problem && strlen($data) == 0) {
    show_error($problem);
  }
  return $data;
}

function show_error($myError)
{
  echo "<html><body><b>Error:</b> $myError</body></html>";
  exit();
}
?>