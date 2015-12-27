<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	//parameters to DB
    	$servername = "ibenzyk.mysql.ukraine.com.ua";
		$username = "ibenzyk_db";
		$password = "UkmETFqY";
		$dbname = "ibenzyk_db";

        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $tel = trim($_POST["tel"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Упс! Произошла проблема с Вашим сообщением. Пожалуйста, заполните форму и попробуйте еще раз."; //Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        $recipient = "info@juristconsult.in.ua";

        // Set the email subject.
        $subject = "Новое сообщение от $name";

        // Build the email content.
        $email_content = "Имя: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Телефон: $tel\n\n";
        $email_content .= "Сообщение:\n$message\n";

        // Build the email headers.
        $email_headers = "From: $name <$email>";


        // Build the email content for autoresponse.
        $response_to = $email;
        $response_subject = "Новое сообщение от Юридической Консультации";
        $response_headers = "From: Юридическая Консультация <$recipient>";
        $response_content = "Добрый день, $name!\n\n";
        $response_content .= "Мы получили Ваше обращение. В ближайшее время наш предствавитель свяжется с Вами.\n\n";
        $response_content .= "С Уважением,\nЮридическая Консультация\n+38 (066) 891-5420\n$recipient";

        //try to save personal information to DB
        try {
		    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $conn->exec("set names utf8");
		    // prepare sql and bind parameters
		    $stmt = $conn->prepare("INSERT INTO Persons (name, email, tel) VALUES(:name, :email, :tel) ON DUPLICATE KEY UPDATE name=VALUES(name), tel=VALUES(tel)");
		    //VALUES (:name, :email, :tel)");
		    $stmt->bindParam(':name', $name);
		    $stmt->bindParam(':email', $email);
		    $stmt->bindParam(':tel', $tel);
		    $stmt->execute();

		} catch(PDOException $e) {
			//
		}
		$conn = null;


        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
        	//send auto response to client
        	mail($response_to, $response_subject, $response_content, $response_headers);
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Спасибо! Ваше сообщение отправлено."; //Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Упс! Что то пошло не так, мы не смогли отправить сообщение. Попробуйте еще раз.";//Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "Упс! Произошла проблема с Вашим сообщением. Пожалуйста, заполните форму и попробуйте еще раз.";
    }

?>