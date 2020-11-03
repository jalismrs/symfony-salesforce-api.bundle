# symfony.bundle.api.salesforce

Adds a service to query Salesforce API

## Test

`phpunit` or `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

```php
use Jalismrs\Symfony\Bundle\JalismrsSalesforceApiBundle\SalesforceApi as SalesforceApiBase;
use QueryResult;
use SObject;

class SalesforceApi extends SalesforceApiBase {
    public function someApiCallQueryResult(): QueryResult {
        $query = <<<SOQL

SOQL;
        
        return $this->query($query);
    }
    
    public function someApiCallNullableSObject(): ?SObject {
        $query = <<<SOQL

SOQL;
        
        return $this->queryOne($query);
    }
    
    public function someOtherApiCallSObjectOrFails(): SObject {
        $query = <<<SOQL

SOQL;
        
        return $this->queryOneOrFails($query);
    }
}
```
