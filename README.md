# PHP SDK for ResellerClub API

## Description
This client is used to interact with one of the following APIs:
 * ResellerClub ([Docs](https://resellerclub.webpropanel.com/kb/answer/751))
 * LogicBoxes ([Docs](https://manage.logicboxes.com/kb/node/751))
 
Available API requests: 
* Actions
* Contacts
* Customers
* Domains
* Products

# Installation
`composer.json`:
```
"require": {
    "heronsantoscom/resellerclub-php-api": "dev-master"
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/HeronSantosCom/resellerclub-php-api.git"
    }
]
```

## Usage Example
```php
use HeronSantosCom\ResellerClub\ResellerClub;

$resellerClub = new ResellerClub('<userId>', '<apiKey>');
$resellerClub->domains()->available(['google', 'example'], ['com', 'net']);
```
Note: All functions return a raw response from API.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

### Disclaimer
This repository is fork on his [privyreza/resellerclub-php-api](https://github.com/privyreza/resellerclub-php-api) repository and [amirkhiz/resellerclub-php-api](https://github.com/amirkhiz/resellerclub-php-api) repository and based on his [ResellerClub PHP SDK](https://github.com/afbora/resellerclub-php-sdk "ResellerClub PHP SDK") repository.

Many thanks to [privyreza](https://github.com/privyreza "privyreza"), [Siavash Habil](https://github.com/amirkhiz "Siavash Habil"), [Ahmet Bora](https://github.com/afbora "Ahmet Bora").