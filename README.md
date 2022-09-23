# Calendly PHP SDK

PHP implementation of [Calendly API](https://developer.calendly.com/api-docs) (v2).


### Installation

```
composer require lobrs/calendly-sdk-php
```

### Usage

```
\LoBrs\Calendly\Calendly::setToken($token);
$currentUser = \LoBrs\Calendly\Calendly::me();
```

#### Get user from UUID

```
$user = \LoBrs\Calendly\Models\User::get($uuid);
```

#### Get model list

Returns an array of objects from the model class.

```
// List scheduled events from a user
$scheduled_events = \Calendly\Models\Event::getList([
    "user" => $user->uri
]);
```

You can also use `paginate($options)` method, returning a `PaginatedList`.
```
$result = \Calendly\Models\Event::paginate([
    "user" => $user->uri
]);

echo $result->countResults() . " results \n";
echo $result->getNextPageURL();

// Request next page with the same options.
$next_page_results = $result->next();
```

Refer to the [Calendly API documentation](https://developer.calendly.com/api-docs) to find requests options.

### Webhooks

You can use `WebhookSubscription` Model directly to manage your Webhooks methods `getList`, `get`, `create` and `delete`.

#### Webhook subscription

We provide a simple `subscribe` method to add a new webhook. 
The `$uuid` parameter referencing a user or organization uuid.

```
\LoBrs\Calendly\Webhooks\Webhook::subscribe("https://example.com/my-webhook", [
    'invitee.created'
], $uuid, 'my-webhook-secret-key');
```

To improve security, you can [sign your webhooks](https://developer.calendly.com/api-docs/ZG9jOjM2MzE2MDM4-webhook-signatures) 
by providing the webhook secret key parameter.

#### Webhook payload example

```
$payload = @file_get_contents('php://input');
$header = $_SERVER['HTTP_CALENDLY_WEBHOOK_SIGNATURE'];
try {
    $webhook = Webhook::readEvent($payload, $header, 'my-webhook-secret-key');
} catch (WebhookExpiredResponseException|WebhookSignatureException $e) {}
```

### OAuth

Uses [ThePhpLeague OAuth2 Client](https://github.com/thephpleague/oauth2-client)

#### Usage flow

```

$provider = new CalendlyOAuthProvider([
    "clientId"     => env('CALENDLY_CLIENT'),
    "clientSecret" => env('CALENDLY_OAUTH_SECRET'),
    'redirectUri'  => env('CALENDLY_REDIRECT_URI'),
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();

    header('Location: ' . $authUrl);
    exit;

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);

    try {
        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        print_r($user->toArray());

    } catch (Exception $e) {
        exit('Failed to get user details');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```
