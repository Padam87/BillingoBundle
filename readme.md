#Installation

`composer require padam87/billingo-bundle`

App kernel: `new Padam87\BillingoBundle\Padam87BillingoBundle(),`

# Configuration:

```yaml
padam87_billingo:
    authentication:
        public_key: your_public_key
        private_key: your_private_key
```

# Usage:

```php
$client = $this->get('padam87_billingo.api')->request('POST', 'clients', [/* ... */]);
```

In the example above `$client` will contain the complete response from the API.
The response data should be accessed by `$client['data']`.

# Configuration reference:

```yaml
padam87_billingo:
    authentication:       # Required
        public_key:           ~ # Required
        private_key:          ~ # Required
        lifetime:             120
    api:
        version:              2
        base_url:             'https://www.billingo.hu/api/'
```

