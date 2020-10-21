# Symfony Bundle Salesforce API

## Test

`phpunit` OU `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

```php
use Jalismrs\SalesforceApiBundle\SalesforceApi as SalesforceApiBase;
use QueryResult;
use SObject;

class SalesforceApi extends SalesforceApiBase {
    public function someApiCall(): QueryResult {
        $query = <<<SOQL

SOQL;
        
        return $this->query($query);
    }
    
    public function someOtherApiCall(): ?SObject {
        $query = <<<SOQL

SOQL;
        
        return $this->queryOne($query);
    }
}
```
