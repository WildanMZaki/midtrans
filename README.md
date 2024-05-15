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

### Example Usage

To start using the library, initialize the `CoreAPI` class and set up the basic parameters:

```php
// This snippet would getting parameter based on method and option that inputted to the 'method' function
$params = CoreAPI::method($method, $option)
    ->invoice('JL-1234566')
    ->total(100000)
    ->va('89619925681')
    ->message('Test Payment')
    ->options([
        'alfamart_free_text_1' => 'jasdfkas'
    ])
    ->params();
```

### Methods Description

```php
::method($method, $option) // initialization method to create instance of CoreAPI object statically

# invoice method
->invoice($inv)  // To set up order id that would be used in the transaction

// Variasi penggunaan: bisa diisikan callback function yang mereturn data dengan type string
->invoice(function() {
    return 'string of order id'
})

# inv method : similiar with invoice, but it will use library utility to generate the invoice in format Prefix-Ymd000x
->inv('INV-', 2)  // this would set up order id to 'INV-20240515002'

->inv('INV-', 2, 5) // this variant would set up the order_id to: 'INV-2024-051500001'  (The third parameter is digits it's mean how long the order id number would be set up)

// other variant: use callback that return int in second parameter
->inv('INV-', function(string $prefix) {
    // $prefix is prefix that already set up for the invoice including the date when the invoice generated.
    // This can help you to count how many invoice that have same prefix (get by like query)

    return 5;
})
})

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
