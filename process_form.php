<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure the path to autoload.php is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                      // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
        $mail->Username   = 'daniduchamikara@gmail.com';           // SMTP username
        $mail->Password   = 'qpie zjtu cdln ntak'; // SMTP password (use an app password if you have 2FA enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                   // TCP port to connect to

        // Recipients for admin notification
        $mail->setFrom($email, $name);                              // Sender's email and name
        $mail->addAddress('daniduchamikara@gmail.com', 'Euro Lanka'); // Your email address to receive the form submission

        // Content for admin notification
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = '<strong>Name:</strong> ' . $name . '<br>' .
                         '<strong>Email:</strong> ' . $email . '<br>' .
                         '<strong>Message:</strong><br>' . nl2br($message);

        // Send the email to admin
        $mail->send();

        // Prepare to send a thank you email to the user
        $mail->clearAddresses();                                    // Clear all recipients
        $mail->addAddress($email, $name);                           // Add the user's email address
        $mail->Subject = 'Thank You for Contacting Euro Lanka';
        $mail->Body    = '<p>Dear ' . $name . ',</p>' .
                         '<p>Thank you for getting in touch with us. We have received your message and will get back to you as soon as possible.</p>' .
                         '<p>Here is a copy of your message:</p>' .
                         '<p><strong>Subject:</strong> ' . $subject . '</p>' .
                         '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>' .
                         '<p>Best regards,<br>Euro Lanka Team</p>';

        // Send the thank you email to the user
        $mail->send();

        // Redirect to the contact form page with a success parameter
        header('Location: contact.html?success=true');
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>
