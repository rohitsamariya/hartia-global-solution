<?php
/* Set e-mail recipient */
$myemail  = "info@hartiaglobal.com";

/* Check all form inputs using check_input function */
$yourname  = check_input($_POST['name'], "Enter your name");
$number    = check_input($_POST['number']);
$email     = check_input($_POST['email']);
$service   = check_input($_POST['service']);
$message   = check_input($_POST['message'] );


// /* If e-mail is not valid show error message */
// if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
// {
//     show_error("E-mail address not valid");
// }


/* Let's prepare the message for the e-mail */
$message = "Hartia Global Solution !

Your contact form has been submitted by:

Name      : $yourname
Number    : $number
Email     : $email
Service   : $service
Message   : $message


End of message
";

/* Send the message using mail() function */
mail($myemail, $number, $message);

/* Redirect visitor to the thank you page */
header('Location: thank-you.html');
exit();

/* Functions we used */
function check_input($data, $problem='')
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($problem && strlen($data) == 0)
    {
        show_error($problem);
    }
    return $data;
}

function show_error($myError)
{
?>
    <html>
    <body>

    <b>Please correct the following error:</b><br />
    <?php echo $myError; ?>

    </body>
    </html>
<?php
exit();
}
?>