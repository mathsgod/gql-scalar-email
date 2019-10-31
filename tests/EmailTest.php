<?
declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{

    public function test()
    {
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

        //---
        $query = <<<gql
query{
    test(email:"abcd")
}
gql;

        $result = GraphQL::executeQuery($schema, $query);

        $result = $result->toArray();
        $this->assertArrayHasKey("errors", $result);


        //---
        $query = <<<gql
query{
    test(email:"abcd@xyz.com")
}
gql;
        $result = GraphQL::executeQuery($schema, $query);
        $result = $result->toArray();
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("test", $result["data"]);
    }
}
