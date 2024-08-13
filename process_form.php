<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Email settings
    $to = "daniduchamikara@gmail.com"; // Your email address
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $email_subject = "Inquiry from: " . $name . " - " . $subject;
    $email_body = "You have received a new message from your website contact form.\n\n" .
                  "Name: " . $name . "\n" .
                  "Email: " . $email . "\n\n" .
                  "Subject: " . $subject . "\n\n" .
                  "Message:\n" . $message . "\n";

    // Send email
    if (mail($to, $email_subject, $email_body, $headers)) {
        // Redirect to a thank-you page or show a success message
        header('Location: thank_you.html'); // Redirect to a thank-you page
        exit;
    } else {
        echo "Sorry, there was an issue sending your message. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
