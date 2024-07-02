<?php

namespace App\Http\Controllers;

use App\Models\WhatsappApi;
use App\Models\WhatsappMessage;
use App\Models\MessageTemplate;
use App\Models\WhtasappTemplate;
use App\Models\WhatsappChatContact;

use App\Events\MessageReceived;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WebHook;
use GuzzleHttp\Client;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
// use Netflie\WhatsAppCloudApi\Message\Component;
// use Netflie\WhatsAppCloudApi\Message\Template;

class WhatsappController extends Controller
{
    // create whatsapp api
    public function create(Request $request)
    {
        $data = WhatsappApi::first();
        return view('admin.whatsapp.create', compact('data'));
    }

    //======================================= Webhook =================================
    const VERIFY_TOKEN = 'LaravelToken';

    public function setupWebhook(Request $request)
    {
        Log::info($request->all());

        if ($request->isMethod('get')) {
            $hubMode = $request->query('hub_mode');
            $hubChallenge = $request->query('hub_challenge');
            $hubVerifyToken = $request->query('hub_verify_token');

            if ($hubVerifyToken !== self::VERIFY_TOKEN) {
                return response()->json(['error' => 'VerifyToken doesn\'t match'], 403);
            }

            // Log::info('WebHook with GET executed.');
            // Log::info("Parameters: hub_mode=$hubMode  hub_challenge=$hubChallenge  hub_verify_token=$hubVerifyToken");

            return response($hubChallenge);
        } elseif ($request->isMethod('post')) {
            $data = $request->all();
            $recipient_id = null;

            // Check if 'statuses' key exists in the request data
            if (isset($data['entry'][0]['changes'][0]['value']['statuses'])) {
                $statuses = $data['entry'][0]['changes'][0]['value']['statuses'];

                foreach ($statuses as $status) {
                    // Check if the message_id exists, then update, otherwise create a new record
                    WhatsappMessage::updateOrCreate(
                        ['message_id' => $status['id']], // Condition to check
                        [
                            // 'whatsapp_message' => json_encode($data),
                            'template_name' => $status['conversation']['origin']['type'] ?? null,
                            'template_type' => $status['conversation']['origin']['type'] ?? null,
                            'type' => 'send',
                            'status' => $status['status'],
                            'phone_number' => $data['entry'][0]['changes'][0]['value']['metadata']['display_phone_number'],
                            'from' => $data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'],
                            'recipient_id' => $status['recipient_id'],
                            'send_at' => isset($status['timestamp']) ? date('Y-m-d H:i:s', $status['timestamp']) : null,
                        ]
                    );

                    $recipient_id = $status['recipient_id'];
                }
            } else if (isset($data['entry'][0]['changes'][0]['value']['messages'])) {
                $messages = $data['entry'][0]['changes'][0]['value']['messages'];

                foreach ($messages as $message) {
                    // Extracting data
                    $message_id = $message['id'] ?? null;
                    $reply = $message['text']['body'] ?? null;
                    $profile_name = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? null;
                    $type = $message['type'] ?? null;
                    $reply_at = isset($message['timestamp']) ? date('Y-m-d H:i:s', $message['timestamp']) : null;
                    $status = 'received'; // or another status based on your requirements
                    $phone_number = $data['entry'][0]['changes'][0]['value']['metadata']['display_phone_number'] ?? null;
                    $recipient_sid = $message['from'] ?? null;
                    $from = $message['from'] ?? null;

                    // Creating the reply in the database
                    WhatsappMessage::create([
                        'message_id' => $message_id,
                        'whatsapp_message' => $reply,
                        'reply' => $message,
                        'profile_name' => $profile_name,
                        'type' => 'reply',
                        'reply_at' => $reply_at,
                        'status' => $status,
                        'phone_number' => $phone_number,
                        'recipient_id' => $recipient_sid,
                        'from' => $from,
                    ]);
                }

                $recipient_id = $message['from'];
            }

            // // Respond with 200 OK to acknowledge receipt of the message
            // return response()->json(['status' => 'Message received'], 200);

            return redirect()->route('whatsapp.chat.index', ['recipient_id' => $recipient_id]);
        } else {
            return response()->json(['error' => 'Invalid request method'], 405);
        }
    }

    // ==================================== Profile =================================//
    public function getProfile(Request $request)
    {
        $fromPhoneNumberId = getenv("FB_PHONE_NUMBER");
        $accessToken = getenv("FB_METADATA_TOKEN");

        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v19.0/',
        ]);

        try {
            $response = $client->request('GET', $fromPhoneNumberId . '/whatsapp_business_profile', [
                'query' => [
                    'fields' => 'about,address,description,email,profile_picture_url,websites,vertical',
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $profileData = json_decode($body, true);

            // Handle the profile data as needed
            return response()->json($profileData);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        // Log::info($request->all());
        $phoneNumberId = getenv("FB_PHONE_NUMBER");
        $accessToken = getenv("FB_METADATA_TOKEN");

        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v19.0/',
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'about' => 'required',
                'address' => 'required',
                'description' => 'required',
                'vartical' => 'required',
                'email' => 'required',
                'website_1' => 'nullable',
                'website_2' => 'nullable',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $response = $client->request('POST', $phoneNumberId . '/whatsapp_business_profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "messaging_product" => "whatsapp",
                    "about" => $request->get('about'),
                    "address" => $request->get('address'),
                    "description" => $request->get('description'),
                    "vertical" => $request->get('vartical'),
                    "email" => $request->get('email'),
                    "websites" => [
                        $request->get('website_1'),
                        // $request->get('website_2'),
                    ],
                    // "profile_picture_handle" => "HANDLE_OF_PROFILE_PICTURE"
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                // return response()->json(['success' => true], 200);
                // Log::info('WhatsApp profile updated successfully');
                return redirect()->back()->with('success', 'Whatsapp Profile updated successfully');
            } else {
                Log::error('Failed to update WhatsApp profile');
                // return response()->json(['error' => 'Failed to update WhatsApp profile'], $statusCode);
                return redirect()->back()->with('error', 'Something went wrong!');
            }
        } catch (\Exception $e) {
            Log::error('Failed to update WhatsApp profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSubscribedApps(Request $request)
    {
        $version = 'v19.0'; // Replace with your desired API version
        $wabaId = getenv("FB_ACCOUNT_ID"); // Replace with your WhatsApp Business Account ID
        $authorization = getenv("FB_METADATA_TOKEN"); // Replace with your authorization token

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $authorization,
            ])->post("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps");

            // Check if the request was successful
            if ($response->successful()) {
                // Request was successful, handle response
                return $response->json();
            } else {
                // Request failed, handle error
                $errorCode = $response->status();
                return response()->json(['error' => "Request failed with status code {$errorCode}"], $errorCode);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //=========================== Templates ============================
    public function getMessageTemplate($templateName = null)
    {
        // Reference: https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates

        $version = getenv("FB_API_VERSION");
        $wabaId = getenv("FB_ACCOUNT_ID");
        $token = getenv("FB_METADATA_TOKEN");

        $url = "https://graph.facebook.com/$version/$wabaId/message_templates?name=$templateName";

        $response = Http::withToken($token)->get($url);

        Log::info($response->json());
        if ($response->successful()) {
            $data = $response->json();

            $components = [];
            $body_params = [];
            $header_img = null;
            $language = null;
            $response = $data['data'] ?? null;
            $template = [];

            foreach ($data['data'] as $item) {
                if ($item['name'] == $templateName && isset($item['components'])) {
                    foreach ($item['components'] as $component) {
                        $type = strtolower($component['type']);

                        if ($type === "header") {
                            $header = [
                                'type' => $type,
                                'parameters' => [
                                    'type' => strtolower($component['format']),
                                    strtolower($component['format']) => [
                                        'link' => $component['example']['header_handle'][0]
                                    ]
                                ]
                            ];
                            $components[] = $header;
                            $header_img = $component['example']['header_handle'][0] ?? null;
                        } else if ($type === "body") {

                            if (isset($component['text'])) {
                                $component['text'] = html_entity_decode($component['text'], ENT_QUOTES, 'UTF-8');

                                // Extract body parameters if available, otherwise set to an empty array
                                $body_params = $component['example']['body_text'][0] ?? [];

                                // Initialize body array
                                $body = [];

                                // Only add type and parameters if body_params is not empty
                                if (!empty($body_params)) {
                                    $body['type'] = $type;
                                    $body['parameters'] = array_map(function ($param) {
                                        return ['type' => 'text', 'text' => $param];
                                    }, $body_params);

                                    // Log::info($component['text']);
                                    // Log::info($body['parameters']);

                                }

                                // Add to components if body is not empty
                                if (!empty($body)) {
                                    $components[] = $body;
                                }

                                $body_params = $component['example']['body_text'][0] ?? null;
                                $body_text = $component['text'] ?? null;
                            }
                        }
                    }
                }

                if ($item['name'] == $templateName && isset($item['language'])) {
                    $language = $item['language'];
                }
            }

            // Log::info(json_encode($components));
            // Log::info(json_encode($body_params));
            // Log::info(json_encode($header_img));

            $template['name'] = $templateName;
            $template['language']['code'] = $language;
            $template['components'] = $components;
            // Log::info(json_encode($template));
            // Log::info($component['text']);


            return response()->json([
                'components' => $components,
                'language' => $language ?? null,
                'body_params' => $body_params,
                'header_img' => $header_img,
                'response' => $response
            ]);
        } else {
            return null;
        }
    }

    //============================= Messages ============================

    public function markAsRead($messageId)
    {
        Log::info($messageId);
        $phoneNumberId = env('FB_PHONE_NUMBER');
        $accessToken = env('FB_METADATA_TOKEN');
        $version = 'v19.0';

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'status' => 'read',
                'message_id' => $messageId,
            ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Message marked as read successfully.'], 200);
        }

        return response()->json(['error' => 'Failed to mark message as read.'], $response->status());
    }

    public function sendMessage(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'recipient_id' => 'required|string',
                'message' => 'required_without:media_image|string',
                'media_image' => 'sometimes|image',
            ]);

            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $request->recipient_id,
            ];

            if ($request->hasFile('media_image')) {
                $image = $request->file('media_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'whatsapp/images/' . $imageName;
                $image->move(public_path('whatsapp/images/'), $imageName);

                $imageUrl = asset('whatsapp/images/' . $imageName);

                $payload['type'] = 'image';
                $payload['image'] = [
                    'link' => $imageUrl,
                    'caption' => $request->message,
                ];
            } else {
                $payload['type'] = 'text';
                $payload['text'] = [
                    'preview_url' => false,
                    'body' => $request->message,
                ];
            }

            // Send the message via HTTP request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env("FB_METADATA_TOKEN"),
                'Content-Type' => 'application/json',
            ])->post('https://graph.facebook.com/v19.0/' . env("FB_PHONE_NUMBER") . '/messages', $payload);

            $data = json_decode($response->getBody(), true);

            if (isset($data['contacts'][0]['wa_id'])) {
                $recipientId = $data['contacts'][0]['wa_id'];
                $contactId = createContact($recipientId, $profile_name = null);

                if (isset($data['messages'])) {
                    foreach ($data['messages'] as $messageData) {
                        $existingMessage = WhatsappMessage::where('recipient_id', $recipientId)->whereNotNull('profile_name')->first();

                        $attributes = [
                            'contact_id' => $contactId,
                            'whatsapp_message' => $request->message ?? null,
                            'template_name' => null,
                            'template_type' => null,
                            'type' => 'send',
                            'status' => null,
                            'image' => $request->hasFile('media_image') ? $imagePath : null,
                            'phone_number' => $recipientId,
                            'from' => null,
                            'recipient_id' => $recipientId,
                            'send_at' => null,
                            'profile_name' => $existingMessage->profile_name ?? null,
                        ];

                        $message = WhatsappMessage::updateOrCreate(
                            ['message_id' => $messageData['id']],
                            $attributes
                        );

                        // broadcast(new MessageReceived($message))->toOthers();
                    }
                }
            }

            return redirect()->route('whatsapp.chat.index');

            // return response()->json(['status' => 'Message sent!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('An error occurred while sending the message');
        }
    }

    // public function createContact($phone_number, $profile_name)
    // {
    //     $contact = WhatsappChatContact::updateOrCreate(
    //         ['number' => $phone_number],
    //         ['name' => $profile_name]
    //     );

    //     return $contact->id;
    // }


    //========================= Testings ===========================================

    public function sendMetaMessage($phoneNumber = null, $templateName = null)
    {
        $url = 'https://graph.facebook.com/v19.0/' . getenv("FB_PHONE_NUMBER") . '/messages';
        $accessToken = getenv("FB_METADATA_TOKEN");
        $phoneNumber = '+919770019148';
        $templateName = 'knsa_test_temp';
        $languageCode = 'en';
        $imageUrl = 'https://registration.knsacademy.in/assets/img/learning/5.jpeg';
        // $textString = MessageTemplate::where('name', $templateName)->first()->message;
        $textString = "rohit";
        $currencyValue = 'VALUE';
        $currencyCode = 'USD';
        $amount = 200;
        $fallbackDate = 'MONTH DAY, YEAR';

        Log::info($textString);

        $response = Http::withToken($accessToken)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $phoneNumber,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode,
                    ],
                    'components' => [
                        [
                            'type' => 'header',
                            'parameters' => [
                                [
                                    'type' => 'image',
                                    'image' => [
                                        'link' => $imageUrl,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => 'rohit kumar',
                                ],
                                [
                                    'type' => 'text',
                                    'text' => '24-06-2024',
                                ],
                                [
                                    'type' => 'text',
                                    'text' => 'https://registration.knsacademy.in/',
                                ],
                                // [
                                //     'type' => 'currency',
                                //     'currency' => [
                                //         'fallback_value' => $currencyValue,
                                //         'code' => $currencyCode,
                                //         'amount_1000' => $amount,
                                //     ],
                                // ],
                                // [
                                //     'type' => 'date_time',
                                //     'date_time' => [
                                //         'fallback_value' => $fallbackDate,
                                //     ],
                                // ],
                            ],
                        ]
                    ],
                ],
            ]);
        Log::info($response->body());

        if ($response->successful()) {
            return response()->json(['message' => 'Message sent successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to send message', 'details' => $response->json()], $response->status());
        }
    }

    public function sendTempMessage()
    {
        $template = WhtasappTemplate::find(23);
        $template_content = $template->template_content;

        // Log::info($template->template_content['components'][1]['parameters']);


        if (!empty($template->template_content['components'])) {
            $components = $template->template_content['components'];
            if ($components[1]['type'] === 'body' && !empty($components[1]['parameters'])) {

                $replacements = ["rohit", "05/06/2024", "my Link"];
                $template_content = templateReplaceParameters($template_content, $replacements);
            }
        }

        Log::info('Template Content: ' . json_encode($template_content, JSON_PRETTY_PRINT));

        $phoneNumber = '+919770019148';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . getenv("FB_METADATA_TOKEN"),
            'Content-Type' => 'application/json',
        ])->post(
            'https://graph.facebook.com/v19.0/' . getenv("FB_PHONE_NUMBER") . '/messages',
            [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $phoneNumber,
                'type' => 'template',
                'template' => $template_content,
            ]
        );

        Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            Log::info('Message Status: ' . json_encode($response->json()));
            return response()->json(['message' => 'Message sent successfully'], 200);
        } else {
            Log::error('Failed to send message: ' . json_encode($response->json()));
            return response()->json(['error' => 'Failed to send message', 'details' => $response->json()], $response->status());
        }
    }

    public function sendTmpMessage(Request $request)
    {
        $template = MessageTemplate::find($request->template_id);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . getenv("FB_METADATA_TOKEN"),
            'Content-Type' => 'application/json',
        ])->post('https://graph.facebook.com/v19.0/' . getenv("FB_PHONE_NUMBER") . '/messages', [
            'messaging_product' => 'whatsapp',
            "recipient_type" => "individual",
            'to' => '+91' . $request->phone,
            'type' => 'template',

            'template' => [
                'name' => $template->name,
                'language' => [
                    'code' => 'en'
                ]
            ]
        ]);

        $data = json_decode($response, true);  // Assuming $response is a JSON string, decode it into an associative array

        if (isset($data['messages'])) {
            $messages = $data['messages'];

            foreach ($messages as $message) {
                // Check if the wa_id exists, then update, otherwise create a new record
                WhatsappMessage::updateOrCreate(
                    ['message_id' => $message['id']],
                    [
                        'whatsapp_message' => $request->message,
                        'template_name' => null,
                        'template_type' => null,
                        'type' => 'send',
                        'status' => null,
                        'phone_number' => $data['contacts'][0]['wa_id'],
                        'from' => null,
                        'recipient_id' => $data['contacts'][0]['wa_id'],
                        'send_at' => null,
                    ]
                );
            }
        }


        // return $response;

        // Log::info($response);

        return redirect()->route('whatsapp.chat.index', ['recipient_id' => $request->recipient_id]);
    }

    public function sendTextMessage(Request $request)
    {
        // Log::info($request->all());
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . getenv("FB_METADATA_TOKEN"),
            'Content-Type' => 'application/json',
        ])->post('https://graph.facebook.com/v19.0/' . getenv("FB_PHONE_NUMBER") . '/messages', [
            'messaging_product' => 'whatsapp',
            "recipient_type" => "individual",
            'to' => $request->recipient_id,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $request->message,
            ]
        ]);

        Log::info($response);

        $data = json_decode($response, true);  // Assuming $response is a JSON string, decode it into an associative array

        if (isset($data['messages'])) {
            $messages = $data['messages'];

            // foreach ($messages as $message) {
            //     // Check if the wa_id exists, then update, otherwise create a new record
            //     WhatsappMessage::updateOrCreate(
            //         ['message_id' => $message['id']],
            //         [
            //             'whatsapp_message' => $request->message,
            //             'template_name' => null,
            //             'template_type' => null,
            //             'type' => 'send',
            //             'status' => null,
            //             'phone_number' => $data['contacts'][0]['wa_id'],
            //             'from' => null,
            //             'recipient_id' => $data['contacts'][0]['wa_id'],
            //             'send_at' => null,
            //         ]
            //     );
            // }

            foreach ($messages as $message) {
                // Fetch the existing message if it exists
                $existingMessage = WhatsappMessage::where('recipient_id', $data['contacts'][0]['wa_id'])->whereNotNull('profile_name')->first();


                // Prepare the attributes for update or create
                $attributes = [
                    'whatsapp_message' => $request->message,
                    'template_name' => null,
                    'template_type' => null,
                    'type' => 'send',
                    'status' => null,
                    'phone_number' => $data['contacts'][0]['wa_id'],
                    'from' => null,
                    'recipient_id' => $data['contacts'][0]['wa_id'],
                    'send_at' => null,
                ];

                // If the message exists, add the profile name
                if ($existingMessage) {
                    $attributes['profile_name'] = $existingMessage->profile_name;
                }

                // Update or create the record
                WhatsappMessage::updateOrCreate(
                    ['message_id' => $message['id']],
                    $attributes
                );
            }
        }


        // return $response;

        // Log::info($response);

        // return redirect()->route('whatsapp.chat.index', ['recipient_id' => $request->recipient_id]);

        return redirect()->route('whatsapp.chat.index');
    }

    public function sendImgMessage(Request $request)
    {
        // Validate the request to ensure an image file and recipient ID are provided
        $request->validate([
            'media_image' => 'required|image',
            'recipient_id' => 'required|string',
        ]);

        // Store the uploaded image
        if ($request->hasFile('media_image')) {
            $image = $request->file('media_image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Store the image in the 'public/whatsapp/images' directory
            $path = $image->storeAs('public/whatsapp/images', $imageName);

            // Generate the URL for the stored image
            $imageUrl = Storage::url($path);
        }

        // Make the HTTP request to send the image
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env("FB_METADATA_TOKEN"),
            'Content-Type' => 'application/json',
        ])->post('https://graph.facebook.com/v19.0/' . env("FB_PHONE_NUMBER") . '/messages', [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $request->recipient_id,
            'type' => 'image',
            'image' => [
                'link' => url($imageUrl),
                'caption' => 'The best succulent ever?',
            ],
        ]);

        Log::info($response);

        $data = json_decode($response->getBody(), true);

        if (isset($data['messages'])) {
            $messages = $data['messages'];

            foreach ($messages as $message) {
                // Fetch the existing message if it exists
                $existingMessage = WhatsappMessage::where('recipient_id', $data['contacts'][0]['wa_id'])->whereNotNull('profile_name')->first();

                // Prepare the attributes for update or create
                $attributes = [
                    'whatsapp_message' => $request->message,
                    'template_name' => null,
                    'template_type' => null,
                    'type' => 'send',
                    'status' => null,
                    'image' => $path,
                    'phone_number' => $data['contacts'][0]['wa_id'],
                    'from' => null,
                    'recipient_id' => $data['contacts'][0]['wa_id'],
                    'send_at' => null,
                ];

                // If the message exists, add the profile name
                if ($existingMessage) {
                    $attributes['profile_name'] = $existingMessage->profile_name;
                }

                // Update or create the record
                WhatsappMessage::updateOrCreate(
                    ['message_id' => $message['id']],
                    $attributes
                );
            }
        }

        return redirect()->route('whatsapp.chat.index');
    }
}
