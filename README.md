# MidtransCI3 PHP Library

MidtransCI3 is a PHP library for integrating Midtrans payment gateway into CodeIgniter 3 applications. This library simplifies the process of setting up and making transactions through the Midtrans payment gateway.

## Installation

You can install this library via Composer:

```bash
composer require wildanmzaki/midtrans-ci3
```

## Usage

### Basic Setup

First, import the necessary classes:

```php
use WildanMZaki\MidtransCI3\CoreAPI;
```

### Initialization

To start using the library, initialize the `CoreAPI` class and set up the basic parameters:

```php
$response = CoreAPI::method($method, $option)
    ->config('midtrans')
    ->invoice('JL-1234566')
    ->total(100000)
    ->va('89619925681')
    ->message('Test Payment')
    ->options([
        'alfamart_free_text_1' => 'jasdfkas'
    ])
    ->params();
```

### Additional Options

You can set additional options such as items, customer details, and card tokens using the provided methods:

```php
$response = CoreAPI::method($method, $option)
    ->config('midtrans')
    ->invoice('JL-1234566')
    ->total(100000)
    ->va('89619925681')
    ->message('Test Payment')
    ->items([
        [
            'id' => 'a01',
            'price' => 7000,
            'quantity' => 1,
            'name' => 'Apple'
        ]
    ])
    ->customer([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'billing_address' => [
            // billing address details
        ],
        'shipping_address' => [
            // shipping address details
        ]
    ])
    ->card_token('card_token_here')
    ->options([
        'alfamart_free_text_1' => 'jasdfkas'
    ])
    ->params();
```

### Sending Payment Request

To send the payment request to the Midtrans API, use the `send()` method:

```php
$response = CoreAPI::method($method, $option)
    // set up parameters
    ->send();
```

### Handling Responses

The library handles exceptions internally. However, you can catch exceptions and handle them as needed:

```php
try {
    $response = CoreAPI::method($method, $option)
        // set up parameters
        ->send();
    // Handle success response
} catch (\Exception $e) {
    // Handle exception
}
```

## License

This library is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
```
