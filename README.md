# Symfony Bundle Salesforce API

this bundle helps you use Salesforce API using SOQL requests

## Test

`phpunit` or `vendor/bin/phpunit`

coverage reports will be available in `var/coverage`

## Use

```php
use Jalismrs\SalesforceApiBundle\SalesforceApi as SalesforceApiBase;
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
