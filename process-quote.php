<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require './vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

include_once "./configuration.php";
include_once "./connection2.php";

$google_maps_api_key = $config["google"]["apiKey"];
$mover_email = "heribertfel20@gmail.com";
$mover_name = "Sheltar Movers";

function sendEmailsToQueue($clientName, $clientEmail, $quote, $clientPhone, $currentAddress, $destination, $movingDate, $rooms, $additionalServices, $distance, $moverEmail, $moverName)
{
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('moving_email_queue', false, true, false, false);

    $data = [
        'clientName' => $clientName,
        'clientEmail' => $clientEmail,
        'quote' => $quote,
        'clientPhone' => $clientPhone,
        'currentAddress' => $currentAddress,
        'destination' => $destination,
        'movingDate' => $movingDate,
        'rooms' => $rooms,
        'addons' => $additionalServices,
        'distance' => $distance,
        'moverEmail' => $moverEmail,
        'moverName' => $moverName
    ];

    $message = new AMQPMessage(json_encode($data), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    $channel->basic_publish($message, '', 'moving_email_queue');

    $channel->close();
    $connection->close();

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $current_address = $_POST['current_address'];
    $destination_address = $_POST['destination_address'];
    $moving_date = $_POST['moving_date'];
    $rooms = $_POST['rooms'];
    $additional_services = isset($_POST['additional_services']) ? implode(", ", $_POST['additional_services']) : '';

    $distance = get_distance($current_address, $destination_address, $google_maps_api_key);
    if ($distance === null) {
        response_json(false, 'Failed to determine distance.');
        exit();
    }

    $quote = calculate_quote($rooms, $additional_services, $distance);

    $query = "INSERT INTO moving_request (client_name, client_email, client_phone, current_address, destination_address, moving_date, rooms, additional_services, distance, quote) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssssisdd", $name, $email, $phone, $current_address, $destination_address, $moving_date, $rooms, $additional_services, $distance, $quote);

    if ($stmt->execute()) {

        $moverQuery = "SELECT mover_name, mover_email FROM movers WHERE mover_id = 1 AND status = 1 AND delete_flag = 0";
        $mover_stmt = mysqli_prepare($con, $moverQuery);
        mysqli_stmt_execute($mover_stmt);
        mysqli_stmt_store_result($mover_stmt);

        if (mysqli_stmt_num_rows($mover_stmt) > 0) {
            mysqli_stmt_bind_result($mover_stmt, $moverName, $moverEmail);

            if (mysqli_stmt_fetch($mover_stmt)) {
                $sendToQueue = sendEmailsToQueue($name, $email, $quote, $phone, $current_address, $destination_address, $moving_date, $rooms, $additional_services, $distance, $moverEmail, $moverName);
                if ($sendToQueue) {
                    response_json(true, "Quote request submitted successfully. Your estimated quote is Ksh " . number_format($quote) . ". Kindly note that this quote amount is provisional and is subject to change.");
                } else {
                    response_json(false, 'An error occurred while processing your request. Please try again later.');
                }
            }
            mysqli_stmt_close($mover_stmt);
        }
    } else {
        response_json(false, 'Cannot complete request. Please try again later.');
    }

    $stmt->close();
    $con->close();
}

function get_distance($origin, $destination, $api_key)
{
    $origin = urlencode($origin);
    $destination = urlencode($destination);
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$api_key";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);
    if ($data['status'] == 'OK') {
        $distance = $data['rows'][0]['elements'][0]['distance']['value']; // distance in meters
        return $distance / 1000; // convert to kilometers
    } else {
        return null;
    }
}

function calculate_quote($rooms, $additional_services, $distance)
{
    $base_rate = 10000; // base rate
    $room_rate = 5000; // per room
    $distance_rate = 150; // per km
    $packing_rate = 3000; // additional service: packing
    $unpacking_rate = 3000; // additional service: unpacking
    $storage_rate = 2000; // additional service: storage

    $total_quote = $base_rate + ($room_rate * $rooms) + ($distance_rate * $distance);

    if (strpos($additional_services, 'packing') !== false) {
        $total_quote += $packing_rate;
    }
    if (strpos($additional_services, 'unpacking') !== false) {
        $total_quote += $unpacking_rate;
    }
    if (strpos($additional_services, 'storage') !== false) {
        $total_quote += $storage_rate;
    }

    return $total_quote;
}

function response_json($status, $message)
{
    ob_end_clean();
    echo json_encode(['success' => $status, 'message' => $message]);
}