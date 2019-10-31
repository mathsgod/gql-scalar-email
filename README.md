# gql-scalar-email

```php
    
    $gql = "
scalar Email
type Query{
    test(email:Email):Email
}      
";

   $typeConfigDecorator = function ($typeConfig) {
        $name = $typeConfig['name'];

        if ($name === 'Email') {
            $email = new Scalar\Email();

            $typeConfig["serialize"] = [$email, "serialize"];
            $typeConfig["parseLiteral"] = [$email, "parseLiteral"];
        }
        return $typeConfig;
    };

    $schema = BuildSchema::build($gql, $typeConfigDecorator);
```