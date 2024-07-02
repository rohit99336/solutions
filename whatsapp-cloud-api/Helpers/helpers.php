<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

use App\Models\WhatsappChatContact;

function sendSms($phone, $message)
{
    try {
        $mobileNumber = $phone;
        $message =  $message;
        $senderId = getenv("MSGCLUB_SENDER_ID");
        $serverUrl = getenv("MSGCLUB_SERVER_URL");
        $authKey = getenv("MSGCLUB_AUTH_KEY");
        $routeId = getenv("MSGCLUB_SMS_ROUTE");
        $result = sendsmsGET($mobileNumber, $senderId, $routeId, $message, $serverUrl, $authKey);
        // Log::info('sendSms result: ' . $result);
        $result = result($result);
        return $result;
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}

function sendsmsGET($mobileNumber, $senderId, $routeId, $message, $serverUrl, $authKey)
{
    try {
        $route = $routeId;
        $getData = 'mobileNos=' . $mobileNumber . '&message=' . urlencode($message) . '&senderId=' . $senderId . '&routeId=' . $route;

        /* API URL */
        $url = "http://" . $serverUrl . "/rest/services/sendSMS/sendGroupSms?AUTH_KEY=" . $authKey . "&" . $getData;

        /* init the resource */
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        /* get response */
        $output = curl_exec($ch);

        /* Print error if any */
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        return $output;
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}

function sendWhatsAppMessage($phone, $message)
{
    $authKey = getenv("MSGCLUB_AUTH_KEY");
    $whatsappNumber = getenv("MSGCLUB_AWHATSAPP_NO");
    $toNumber = $phone; // Replace with the recipient's WhatsApp number
    $bodyText = $message; // Replace with the message body text

    $url = 'http://msg.msgclub.net/rest/services/sendSMS/v2/sendtemplate?AUTH_KEY=' . $authKey;

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Cookie' => 'JSESSIONID=23BD7D8B4F08B438F9A42E5334C2DDEB.node3',
    ])->post($url, [
        'senderId' => $whatsappNumber,
        'component' => [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $toNumber,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $bodyText,
            ],
        ],
    ]);

    $result = $response->body();
    // Log::info('sendWhatsAppMessage result: ' . $result);
    return result($result);

    return $response->body();
}


function result($result)
{
    $decodedResult = json_decode($result, true); // Assuming $result is a JSON response

    // Check if the responseCode is 3001
    if (isset($decodedResult['responseCode']) && $decodedResult['responseCode'] == '3001') {
        Log::info('Message sent successfully. Response: ' . $result);
        return 'sent';
    } else {
        // Update message status to 'failed'
        Log::error('Error sending message. Response: ' . $result);
        return 'failed';
    }
}


// not used
function sendBulkWhatsAppMessages(array $phones, $message)
{
    $authKey = getenv("MSGCLUB_AUTH_KEY");
    $whatsappNumber = getenv("MSGCLUB_AWHATSAPP_NO");

    $url = 'http://msg.msgclub.net/rest/services/sendSMS/v2/sendtemplate?AUTH_KEY=' . $authKey;

    $responses = [];

    foreach ($phones as $phone) {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Cookie' => 'JSESSIONID=23BD7D8B4F08B438F9A42E5334C2DDEB.node3',
        ])->post($url, [
            'senderId' => $whatsappNumber,
            'component' => [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'preview_url' => true,
                    'body' => $message,
                ],
            ],
        ]);

        $responses[] = $response->json();
    }

    return $responses;
}


function sendWhatsAppMessageWithMedia($phone, $message, $mediaFileName, $mediaFileData)
{
    $authKey = getenv("MSGCLUB_AUTH_KEY");
    $whatsappNumber = getenv("MSGCLUB_WHATSAPP_NO");
    $toNumber = $phone;
    $bodyText = $message;

    $url = 'http://msg.msgclub.net/rest/services/sendSMS/v2/sendtemplate?AUTH_KEY=' . $authKey;

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Cookie' => 'JSESSIONID=23BD7D8B4F08B438F9A42E5334C2DDEB.node3',
    ])->post($url, [
        'senderId' => $whatsappNumber,
        'component' => [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $toNumber,
            'type' => 'media', // Set the type to 'media' for sending multimedia content
            'media' => [
                'preview_url' => true,
                'filename' => $mediaFileName,
                'filedata' => $mediaFileData,
                'caption' => $bodyText, // Caption for the media
            ],
        ],
    ]);

    return $response->body();
}
// not used


// send email
function sendEmail($to, $subject, $message, $cc = null, $bcc = null)
{
    Log::info('sendEmail');
    try {
        $from = getenv("MAIL_FROM_ADDRESS");
        $fromName = getenv("MAIL_FROM_NAME");
        // $data = [
        //     'to' => $to,
        //     'subject' => $subject,
        //     'message' => $message,
        //     'cc' => $cc,
        //     'bcc' => $bcc,
        // ];
        // Mail::send('emails.email', $data, function ($message) use ($from, $fromName, $to, $subject) {
        //     $message->from($from, $fromName);
        //     $message->to($to)->subject($subject);
        // });
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}


//============================================== TWILIO ========================================//
// send message
function sendMessage()
{
    try {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilioNumber = getenv("TWILIO_NUMBER");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "whatsapp:+919770019148", // to +14155238886
                [
                    "body" => "This is a message that I want to send over WhatsApp with Twilio!",
                    "from" => "whatsapp:" . $twilioNumber,
                ]
            );

        return $message->sid;
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}

// send email
function sendEmailWithTwilio($to, $subject, $message, $cc = null, $bcc = null)
{
    Log::info('sendEmailWithTwilio');
    try {
        $from = getenv("MAIL_FROM_ADDRESS");
        $fromName = getenv("MAIL_FROM_NAME");
        $data = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'cc' => $cc,
            'bcc' => $bcc,
        ];
        Mail::send('emails.email', $data, function ($message) use ($from, $fromName, $to, $subject) {
            $message->from($from, $fromName);
            $message->to($to)->subject($subject);
        });
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}

//======================================== Facebook =================================================//
/**
 * Get whatsapp cloud api template
 * Reference: https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates
 */
function getMessageTemplate($templateName = null, $header_img_loc = null)
{
    $version = env("FB_API_VERSION");
    $wabaId = env("FB_ACCOUNT_ID");
    $token = env("FB_METADATA_TOKEN");

    $url = "https://graph.facebook.com/$version/$wabaId/message_templates?name=$templateName";

    $response = Http::withToken($token)->get($url);

    //Log::info($response->json());
    // Log::info(json_encode($response, JSON_PRETTY_PRINT));

    $data = $response->json()['data'] ?? [];

    if (empty($data) || !$response->successful()) {
        return null;
    }

    $components = [];
    $header_img = null;
    $language = null;
    $body_text = null;

    foreach ($data as $item) {
        if ($item['name'] !== $templateName) {
            continue;
        }

        $language = $item['language'] ?? $language;

        foreach ($item['components'] as $component) {
            $type = strtolower($component['type']);
            if ($type === "header") {
                if($component['format'] == "IMAGE" && isset($component['example']['header_handle'][0])){
                    $header_file_url = asset($header_img_loc);
                    $components[] = [
                        'type' => $type,
                        'parameters' => [
                            [
                                'type' => strtolower($component['format']),
                                strtolower($component['format']) => ['link' => $header_file_url]
                            ]
                        ]
                    ];
                }elseif($component['format'] == "TEXT"){
                    $components[] = [
                        'type' => $type,
                        'parameters' => [
                            [
                                'type' => strtolower($component['format']),
                                strtolower($component['format']) => ['text' => $component['text']]
                            ]
                        ]
                    ];
                }
            } elseif ($type === "body" && isset($component['text'])) {
                $component['text'] = html_entity_decode($component['text'], ENT_QUOTES, 'UTF-8');
                $body_params = $component['example']['body_text'][0] ?? [];

                if (!empty($body_params)) {
                    $components[] = [
                        'type' => $type,
                        'parameters' => array_map(fn ($param) => ['type' => 'text', 'text' => $param], $body_params)
                    ];

                    $component['text'] = replacePlaceholders($component['text'], $components[count($components) - 1]['parameters']);
                }
                $body_text = $component['text'];
            }
        }
    }

    $template = [
        'name' => $templateName,
        'language' => ['code' => $language],
    ];

    if (!empty($components)) {
        $template['components'] = $components;
    }

    return [
        'components' => $components,
        'language' => $language,
        'body_params' => $body_params ?? [],
        'header_img' => $header_img,
        'response' => $data,
        'body_text' => $body_text,
        'template' => $template
    ];
}

/**
 * Replace template parameter values
 */
function templateReplaceParameters($template_content, $replacements)
{
    foreach ($template_content['components'] as &$component) {
        if ($component['type'] === 'body') {
            foreach ($component['parameters'] as $index => &$parameter) {
                if ($parameter['type'] === 'text') {
                    $parameter['text'] = $replacements[$index] ?? $parameter['text'];
                }
            }
        }
    }
    return $template_content;
}

/**
 * Replcae template parameters
 */
function replacePlaceholders($text, $parameters)
{
    foreach ($parameters as $index => $param) {
        $placeholder = '{{' . ($index + 1) . '}}';
        $text = str_replace($placeholder, '{' . $param['text'] . '}', $text);
    }
    return $text;
}

/**
 * Send template message
 */
function sendTempMessage($template, $phone, $replacements = null)
{
    // Remove extra characters from the phone number
    $phone = preg_replace('/\D/', '', $phone); // Remove any non-digit characters
    if (strlen($phone) > 10) {
        $phone = substr($phone, -10); // Keep only the last 10 digits
    }

    // Get the template content
    $template_content = $template->template_content;

    if (!empty($template->template_content['components'])) {
        $components = $template->template_content['components'];
        if (isset($components[1]) && $components[1]['type'] === 'body' && !empty($components[1]['parameters'])) {
            // Replace placeholders with actual values
            // $replacements = ["rohit", "05/06/2024", "my Link"];
            $template_content = templateReplaceParameters($template_content, $replacements);
        }
    }

    // Log::info('Template Content: ' . json_encode($template_content, JSON_PRETTY_PRINT));

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . getenv("FB_METADATA_TOKEN"),
        'Content-Type' => 'application/json',
    ])->post(
        'https://graph.facebook.com/v19.0/' . getenv("FB_PHONE_NUMBER") . '/messages',
        [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '+91' . $phone,
            'type' => 'template',
            'template' => $template_content,
        ]
    );

    // Log::info('API Response: ' . $response->body());

    if ($response->successful()) {
        // Log::info('Message Status: ' . json_encode($response->json()));
        // return response()->json(['message' => 'Message sent successfully'], 200);
        return $data = json_decode($response->getBody(), true);

        // return 'send';
    } else {
        Log::error('Failed to send message: ' . json_encode($response->json()));
        // return response()->json(['error' => 'Failed to send message', 'details' => $response->json()], $response->status());
        return 'failed';
    }
}

function createContact($phone_number, $profile_name)
{
    $contact = WhatsappChatContact::where('number', $phone_number)->first();

    if ($contact) {
        // If profile name is null or empty
        if (is_null($profile_name) || $profile_name === '') {
            // If existing profile name is null or empty, update with null, otherwise keep the existing name
            $profile_name = $contact->name ?? null;
        } else {
            // If a new profile name is provided, check if it's different from the existing name
            if ($profile_name === $contact->name) {
                // If the new profile name is the same as the existing one, do not update
                $profile_name = $contact->name;
            }
            // Otherwise, update with the new profile name
        }
    }

    $contact = WhatsappChatContact::updateOrCreate(
        ['number' => $phone_number],
        ['name' => $profile_name]
    );

    return $contact->id;
}
