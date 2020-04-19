<!-- @format -->
[![Latest Stable Version](https://poser.pugx.org/tbclla/laravel-revolut-business/v/stable)](https://packagist.org/packages/tbclla/laravel-revolut-business)
[![License](https://poser.pugx.org/tbclla/laravel-revolut-business/license)](https://packagist.org/packages/tbclla/laravel-revolut-business)
[![Build Status](https://travis-ci.com/tbclla/laravel-revolut-business.svg?branch=master)](https://travis-ci.com/tbclla/laravel-revolut-business)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tbclla/laravel-revolut-business/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tbclla/laravel-revolut-business/?branch=master)

# Laravel-Revolut (Business)

An unofficial Laravel wrapper for [Revolut's Open API for Business](https://www.revolut.com/open-api).<br>
A sister package for [Revolut's Merchant API](https://developer.revolut.com/docs/merchant-api/#introduction-to-merchant-accounts) can be found [here](https://github.com/tbclla/laravel-revolut-merchant).

## Getting Started

Read [Revolut's official documentation](https://revolut-engineering.github.io/api-docs/business-api) to get familiar with the API and the authorization process.

⚠️ **Please use a [sandbox account](https://sandbox-business.revolut.com/signup) when setting up this package, and only switch to your real-world account once you're happy that everything is working correclty.**

### Requirements

- Laravel >=5.8
- PHP >=7.2

### Installation

Pull this package in through composer.

```
composer require tbclla/laravel-revolut-business
```

### Service Provider & Facade

If you have disabled Laravel's auto-discovery feature, add the service provider and facade to your `config/app.php`.

```php
'providers' => [
    // ...
    tbclla\Revolut\Providers\RevolutServiceProvider::class,
],

'aliases' => [
    // ...
    'Revolut' => tbclla\Revolut\Facades\Revolut::class,
]
```

### Configuration

After you have installed the package, publish the configuration file.

```
php artisan vendor:publish --provider "tbclla\Revolut\Providers\RevolutServiceProvider"
```

You can now configure this package under `config/revolut.php`.

#### Access Expiry

As per [the official documentation](https://developer.revolut.com/docs/business-api/#getting-started-usage-and-limits), businesses on the Freelancer plan must explicitly authorise access to their account via the API every 90 days.
If this applies to your business, set 'expire_api_access' to `true`.

#### Credentials

Add the following keys to your `.env` file, as most of the configuration values are read from there.<br>
You will complete the missing values as you [set up access to the API](https://github.com/tbclla/laravel-revolut-business#setting-up-access-to-the-api).

```
REVOLUT_SANDBOX=true
REVOLUT_PRIVATE_KEY=
REVOLUT_REDIRECT_URI=
REVOLUT_CLIENT_ID=
```

#### Token store

Tokens can be stored in either your database or in your cache.

##### Cache

When using the 'cache' store, you may optionally define a `driver`. If set to null, your app's default cache driver (`config/cache.php > default`) will be used.

##### Database

When using the 'database' store, a migration for the required table is automatically included when you run your migrations.
The name of this table can be customised in the configuration.

Run `php artisan migrate` to create the table.

## Setting up access to the API

Please follow steps 1 and 2 of [Revolut's documentation on how to set up access to the API](https://revolut-engineering.github.io/api-docs/business-api/#authentication-setting-up-access-to-your-business-account).

#### Step 1 - Generate a pair of public/private keys

Complete step 1 of Revolut's instructions to generate a key pair.<br>
❗Add the path to your private key to your `.env` as `REVOLUT_PRIVATE_KEY`.

#### Step 2 - Upload your public key

Follow Revolut's step 2 to upload your newly created public key and provide a redirect URI.<br>
⚠️ You do NOT have to create a route or controller for this redirect URI, this package will handle it.<br>
❗Add this redirect URI to your `.env` as `REVOLUT_REDIRECT_URI`.

Revolut will now have created a client ID for you.<br>
❗Add this client ID to your `.env` as `REVOLUT_CLIENT_ID`.

#### Step 3 - Sign a JWT

Skip this step, this package will generate a JWT for you whenever one is needed.<br>
You can verify that you have configured everything correctly by generating a JWT via the below artisan command.
Optionally, you can pass it the path to the matching public key with the `--public` flag, to validate the JWT.

```
php artisan revolut:jwt

php artisan revolut:jwt --public /Path/to/publickey.cer
```

#### Steps 4-7

You do not have to complete any of the remaining steps!<br>
Instead, initate the authorization with the following artisan command, and follow the generated link.

```
php artisan revolut:authorize
```

**To mitigate against CSRF attacks, requesting an authorization code via Revolut's web interface does _NOT_ work in conjunction with Laravel-Revolut!**
Read more about the authorization process [here](https://github.com/tbclla/laravel-revolut-business#authorization).

#### Finishing up

An access and refresh token will now have been stored in your defined token store.
This package will now use this access token until it expires, and request a new one from Revolut when needed, via the refresh token.

To verify that your app has been authorized, you can retrieve an access token with the below artisan command.

```
php artisan revolut:access-token
```

## Using the API

To use the client, either access its methods via the facade, or resolve it from Laravel's service container.

```php
use tbclla\Revolut\Client;
use tbclla\Revolut\Facades\Revolut;

$revolut = resolve(Client::class);
$revolut->account()->details('11d79893-2703-489f-96e9-7946d9aba8b7');
// or simply
Revolut::account()->details('11d79893-2703-489f-96e9-7946d9aba8b7');
```

### Accounts

Please refer to [Revolut's documentation](https://revolut-engineering.github.io/api-docs/business-api/#accounts).

#### Get all accounts

```php
$accounts = Revolut::account()->all();
```

#### Get an account

```php
Revolut::account()->get('ac57ffc9-a5cb-4322-89d2-088e8a007a97');
```

#### Get account details

```php
Revolut::account()->details('11d79893-2703-489f-96e9-7946d9aba8b7');
```

### Counterparties

#### Get all counterparties

```php
Revolut::counterparty()->all();
```

#### Get a counterparty

```php
Revolut::counterparty()->get('5435ff9e-bacd-430b-95c2-094da8662829');
```

#### Delete a counterparty

```php
Revolut::counterparty()->delete('5435ff9e-bacd-430b-95c2-094da8662829');
```

#### Create a counterparty

Please refer to [Revolut's documentation](https://revolut-engineering.github.io/api-docs/business-api/#counterparties-add-revolut-counterparty) for more details.

##### Create a Revolut counterparty

```php
Revolut::counterparty()->create([
    "profile_type" => "personal",
    "name" => "John Smith",
    "phone" => "+4412345678900"
]);
```

##### Create a non-Revolut counterparty

```php
Revolut::counterparty()->create([
    "company_name" => "John Smith Co.",
    "bank_country" => "GB",
    "currency" => "GBP",
    "account_no" => "12345678",
    "sort_code" => "223344",
    "email" => "test@sandboxcorp.com",
    "address" => [
        "street_line1" => "1 Canada Square",
        "street_line2" => "Canary Wharf",
        "region" => "East End",
        "postcode" => "E115AB",
        "city" => "London",
        "country" => "GB"
    ]
]);
```

#### Build a counterparty

Read more about builders and how to use them [here](https://github.com/tbclla/laravel-revolut-business#builders).

##### Build a Revolut counterparty

```php
$builder = Revolut::counterparty()->build();
$builder->profileType('personal')
$builder->name('John Doe')
$builder->phone('+4412345678900');
$builder->create();
```

The builder also provides the following shortcuts to achieve the same as above:

```php
Revolut::counterparty()->build()->personal('John Doe', '+4412345678900')->create();

Revolut::counterparty()->build()->business('test@sandboxcorp.com')->create();
```

##### Build a non-Revolut counterparty

```php
$counterparty = Revolut::counterparty()->build()
    ->bankCountry('GB')
    ->currency('GBP')
    ->accountNumber('12345678')
    ->sortCode('223344');

$counterparty->companyName('John Smith Co');

// or for an individual
$counterparty->individualName('John', 'Smith');

// The counterparty builder accepts the address as an array
$counterparty->address([
    "street_line1" => "1 Canada Square",
    "street_line2" => "Canary Wharf",
    "region" => "East End",
    "postcode" => "E115AB",
    "city" => "London",
    "country" => "GB"
]);

// Alternatively, the builder lets you build the address fluently
$counterparty->streetLine1('1 Canada Square')
    ->streetLine2('Canary Wharf')
    ->region('East End')
    ->postcode('E115AB')
    ->city('London')
    ->country('GB');
```

### Transfers

Please refer to [Revolut's documentation on how to create a transfer](https://revolut-engineering.github.io/api-docs/business-api/#transfers-create-transfer).

#### Create a transfer

```php

Revolut::transfer()->create([
    "request_id" => "e0cbf84637264ee082a848b",
    "source_account_id" => "bdab1c20-8d8c-430d-b967-87ac01af060c",
    "target_account_id" => "5138z40d1-05bb-49c0-b130-75e8cf2f7693",
    "amount" => 123.11,
    "currency" => "EUR",
]);
```

#### Build a transfer

Read more about builders and how to use them [here](https://github.com/tbclla/laravel-revolut-business#builders).

```php
$transfer = Revolut::transfer()->build()
    ->sourceAccount($sourceAccountId)
    ->targetAccout($targetAccountId)
    ->amount(231.20)
    ->reference('payroll'); // optional

// If you want to keep the request ID for your records, retrieve it from the builder
$requestId = $transfer->request_id;

$transfer->create();
```

### Payments

Please refer to [Revolut's documentation on how to create a payment](https://revolut-engineering.github.io/api-docs/business-api/#payments).

#### Create a payment

```php
Revolut::payment()->create([
    "request_id" => "e0cbf84637264ee082a848b",
    "account_id" => "bdab1c20-8d8c-430d-b967-87ac01af060c",
    "receiver" =>[
        "counterparty_id" => "5138z40d1-05bb-49c0-b130-75e8cf2f7693",
        "account_id" => "db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab"
    ],
    "amount" => 123.11,
    "currency" => "EUR",
]);
```

#### Build a payment

Read more about builders and how to use them [here](https://github.com/tbclla/laravel-revolut-business#builders).

```php
$payment = Revolut::payment()->build()
    ->account('bdab1c20-8d8c-430d-b967-87ac01af060c')
    ->receiver('5138z40d1-05bb-49c0-b130-75e8cf2f7693')
    ->amount(93.12)
    ->currency('USD')
    ->create();
```

#### Schedule a payment

The `schedule()` method accepts the same data as the `create()` method, and an ISO date as its second parameter.

```php
Revolut::payment()->schedule($data, '2020-05-19');
```

#### Cancel a scheduled payment

```php
Revolut::payment()->cancel('b63f30f0-62dc-4b6b-98cf-2a9a2e5ac981');
```

### Transactions

#### Get all transactions

The `all()` method accepts an optional array of filters as its first parameter.
Please refer to [Revolut's documentation](https://revolut-engineering.github.io/api-docs/business-api/#payments-get-transactions) for a list of available filters.

```php
$transactions = Revolut::transaction()->all();

$filtered = Revolut::transaction()->all([
    'count' => 200,
    'type' => 'card_payment',
]);
```

As per the offical documentation, transacations which are older than 90 days can only be accessed if your access token has been generated within the last 5 minutes. To handle this, you can pass an optional boolean value as a second parameter, indicating whether or not to force the client to refresh the access token before making the request.

```php
Revolut::transaction()->all([], true);
```

#### Get a transaction

The `get()` method let's you retrieve a transaction by its ID.
If you want to get a transaction by its request ID, you can use the `getByRequestId()` method instead.

```php
Revolut::transaction()->get($id);

Revolut::transaction()->getByRequestId($requestId);
```

### Payment Drafts

#### Get all payment drafts

```php
Revolut::paymentDraft()->all();
```

#### Get a payment draft

```php
Revolut::paymentDraft()->get($id);
```

#### Delete a payment draft

```php
Revolut::paymentDraft()->delete($id);
```

#### Create a payment draft

```php
Revolut::paymentDraft()->create([
    "title": "Sample title",
    "schedule_for": '2020-05-29',
    "payments" => [
        [
            "currency" => "EUR",
            "amount" => 123,
            "account_id" => "db7c73d3-b0df-4e0e-8a9a-f42aa99f52ab",
            "receiver" => [
                "counterparty_id" => "5138z40d1-05bb-49c0-b130-75e8cf2f7693",
                "account_id" => "bdab1c20-8d8c-430d-b967-87ac01af060c"
            ],
        ]
    ]
]);
```

#### Build a payment draft

When building a payment draft, the payments can either be set by passing an array of payments to the `payment()` method, or by adding individual payments via the `addPayment()` method.
```php
$date = now()->addDays(7)->format('Y-m-d');

$draft = Revolut::paymentDraft()->build()
    ->title('Sample title')
    ->schedule($date)
    ->payments($payments);

foreach ($employees as $employee) {
    $draft->addPayment($payment);
}

$draft->create()
```

### Rates

#### Get an exchange rate

The `get()` method accepts the source and target currencies as the first two parameters.<br>
You can optionally pass it the exchange amount as a third parameter, which otherwise defaults to 1.

```php
Revolut::rate()->get('EUR', 'CHF');

Revolut::rate()->get('USD', 'GBP', 143.23);
```

### Exchanges

#### Create an exchange

```php
Revolut::exchange()->create([
    "from" => [
        "account_id" => "d56dd396-523b-4613-8cc7-54974c17bcac",
        "currency" => "USD",
        "amount" => 135.25
    ],
    "to": [
        "account_id" => "a44dd365-523b-4613-8457-54974c8cc7ac",
        "currency" => "EUR"
    ],
    "reference" => "Time to sell",
    "request_id" => Revolut::generateRequestId(),
]);
```

#### Build an exchange

```php
$exchange = Revolut::exchange()->build()
    ->reference('Time to sell')
    ->from('d56dd396-523b-4613-8cc7-54974c17bcac', 'USD', 135.25)
    ->to('a44dd365-523b-4613-8457-54974c8cc7ac', 'EUR');

$response = $exchange->create()
```

### Web-hook

Refer to [Revolut's documentation](https://revolut-engineering.github.io/api-docs/business-api/#web-hooks) to read about web-hooks and available events.

#### Creating the web-hook

```php
Revolut::webhook()->create('https://mydomain.com/endpoint');
```

#### Deleting the web-hook

```php
Revolut::webhook()->delete();
```

## Builders

All API resources that have a `create()` method (except for Web-hooks) also have a `build()` method which returns a resource specific instance of the `tbclla\Revolut\Builders\Builder`. Builders can be used to create the at times complex arrays of data in a more fluent manner.

```php
Revolut::counterparty()->build() // tbclla\Revolut\Builders\CounterpartyBuilder
Revolut::payment()->build() // tbclla\Revolut\Builders\PaymentBuilder
Revolut::paymentDraft()->build() // tbclla\Revolut\Builders\PaymentDraftBuilder
Revolut::transfer()->build() // tbclla\Revolut\Builders\TransferBuilder
Revolut::exchange()->build() // tbclla\Revolut\Builders\ExchangeBuilder
```

#### Output

All builders use the `toArray()` method to return the data in the format required by Revolut.<br>
For example:

```php
Revolut::exchange()->build()
    ->from('d56dd396-523b-4613-8cc7-54974c17bcac', 'USD')
    ->to('a44dd365-523b-4613-8457-54974c8cc7ac', 'EUR', 735.23)
    ->reference('Off to France!')
    ->toArray();
```

Will return:

```
[
    'from' => [
        'account_id' => 'd56dd396-523b-4613-8cc7-54974c17bcac',
        'currency' => 'USD'
    ],
    'to' => [
        'account_id' => 'a44dd365-523b-4613-8457-54974c8cc7ac',
        'currency' => 'EUR',
        'amount' => 735.23,
    ],
    'reference' => 'Off to France!',
    'request_id' => 'c60ec5b3-c5b9-4cea-936c-fa0306374df5'
]
```

#### Creating the built resource

When you are done building, you can simply call the `create()` method on the builder.

```php
Revolut::transfer()->build()
    ->sourceAccount('bdab1c20-8d8c-430d-b967-87ac01af060c')
    ->targetAccout('5138z40d1-05bb-49c0-b130-75e8cf2f7693')
    ->amount(231.20)
    ->create();
```

## Request ID's

Revolut requires some requests to contain a unique `request_id` parameter.

If you are using the builder, the request ID will be created for you automatically.
You can set your own request ID, or get the existing request ID from the builder:

```php
$builder = Revolut::exchange()->build()->requestId('my_own_request_id');

$requestId = $builder->request_id;
```

If you are not using the builder, you can use the static `generateRequestId()` method on the Revolut Client to create a request ID - which is what the builder uses under the hood. This method uses `\Illuminate\Support\Str::Uuid()` to return a UUIDv4 string.

```php
use tbclla\Revolut\Client;

Client::generateRequestId();
```

## Tokens and Authorization

### Authorization

In accordance with [RFC6749 10.12](https://tools.ietf.org/html/rfc6749#section-10.12), this package implements CSRF protection for the `redirect_uri` and includes a controller to handle both the authorization request and subsequent response to enforce this.

#### Authorization Request

As the controller will require a valid state paramater, you can **not** use Revolut's web interface to authorize your app in conjunction with this package.
To authorize your app, you must enter Revolut's Oauth flow from the `auth_route`.<br>
You can get the url via the below artisan command.

```
php artisan revolut:authorize
```

If you need to redirect a user to Revolut's authorization flow, you can get the url via the route helper.
The `auth_route` is a named route and its name is configurable in your `config/revolut.php` file under `auth_route.name`.
You may pass it an optional 'after_success' paramater which will redirect the user to the specified location after the authorization has been completed.

```php
$url = route('revolut-authorize');

$url = route('revolut-authorize', [
    'after_success' => route('home')
]);

return redirect($url);
```

To redirect a user to the authorization flow whenever your app is not authorized, you can catch an `AppUnauthorizedException`, which is thrown when there is no valid refresh token available and your app needs to be re-authorized.

```php
use tbclla\Revolut\Exceptions\AppUnauthorizedException;

Route::get('/accounts', function () {
    try {
        return Revolut::account()->all();
    } catch(AppUnauthorizedException $e) {
        return redirect(route('revolut-authorization', [
            'after_success' => '/accounts'
        ]));
    }
});
```

#### Authorization Response

Once your app has been authorized, Revolut will redirect you to the redirect URI which you have set when creating the API certificate.
This redirect URI must match the `redirect_uri` set in `config/revolut.php`.

You do **not** need to create a route or controller for this redirect URI.

The authorization controller contained in this package will verify the presence and validity of the response's state parameter, and if accepted, will exchange the response's authorization code for an access and refresh token.
Once these tokens are received and stored, the controller will redirect the user to a specified location.
If no location was provided, the controller will return a `200` response instead.

### Token Storage

This package will store access and refresh tokens in either your database or the cache.
Authorization codes are never stored and are instead exchanged for tokens immediately.
You can configure the token driver in your `config/revolut.php` file.

### Token Encryption

By default, all access and refresh tokens are encrypted before being stored in either your database or cache.
This packages uses [Laravel's built-in encryption tools](https://laravel.com/docs/7.x/encryption#introduction) to encrypt your tokens, so make sure that you have a strong `key` set in your `config/app.php`.

### Cleaning up expired tokens

#### Cache

Tokens stored in your cache are only remembered for the duration of their validity, so there is no need to remove them.
If you would like to remove tokens from your cache, you can `forget()` them using their respective keys.
```php
// remove access token
Cache::forget('revolut_access_token');
// remove refresh token
Cache::forget('revolut_refresh_token');
```

#### Database

If you are using the database token store, you can remove expired access tokens and refresh tokens from your database with the below artisan command.

```
php artisan revolut:cleanup
```

You can also schedule the command in your `App\Console\Kernel` class, to automate this process.

```php
$schedule->command('revolut:cleanup')->daily();
```

## Switching from sandbox to a real account

- Update your `.env` file and set `REVOLUT_SANDBOX=false`.
- Clear any sandbox tokens from your database or cache.
- Update your `config/revolut.php` and set `encrypt_tokens` to true, if it isn't already.
- Whitelist the IP's that will access the API by visiting your account Settings > API.
- Reauthorize your app.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
