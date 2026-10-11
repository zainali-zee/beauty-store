<?php

include 'config/database.php';

echo "<h2>Settings Test</h2>";

echo "<hr>";

echo "<strong>Website Name:</strong> ";

echo SITE_NAME;

echo "<br><br>";

echo "<strong>Currency:</strong> ";

echo CURRENCY;

echo "<br><br>";

echo "<strong>Email:</strong> ";

echo $settings['email'];

echo "<br><br>";

echo "<strong>Phone:</strong> ";

echo $settings['phone'];

echo "<br><br>";

echo "<strong>WhatsApp:</strong> ";

echo $settings['whatsapp'];

echo "<br><br>";

echo "<strong>Footer:</strong> ";

echo $settings['footer_text'];