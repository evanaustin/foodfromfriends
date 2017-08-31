<?php

/**
 * Instantiates the AWS SDK client with a global configuration.
 *
 * To load a service, instantiate this class and run `Sdk->create<service>()`.
 * Example:
 *
 * ```
 * $S3 = $Aws->Sdk->createS3();
 * $Sqs = $Aws->Sdk->createSqs();
 * $Dynamodb = $Aws->Sdk->createDynamoDb();
 * ```
 */
class Aws {
    /**
     * An instance of \Aws\Sdk.
     *
     * @var object
     */
    public $Sdk;

    /**
     * Sets region, version, and credentials and instantiates the SDK.
     *
     * Note that we can set these on a per-service basis if need be:
     *
     * ```
     * $this->Sdk = new \Aws\Sdk([  
     *     'region' => 'us-east-1',  
     *     'version' => 'latest',  
     *     'credentials' => <credentials>,  
     *     'DynamoDb' => [  
     *         'region' => 'us-west-2',  
     *     ],  
     * ]);
     *
     * $sqs = $sdk->createSqs();
     * // Note: SQS client will be configured for us-east-1.
     * 
     * $dynamodb = $sdk->createDynamoDb();
     * // Note: DynamoDB client will be configured for us-west-2.
     * ```
     *
     * The `version` setting is for specifying which version of each individual
     * service we wish to use.  We can use the string 'latest' to use the most
     * recent API version the SDK can find within /vendor/aws/aws-sdk-php/data.
     *
     * To ensure breaking changes to a service don't affect our implementation,
     * it's best to specify versions explicitly.
     *
     * A list of available API versions can be found in the 
     * [SDK documentation](http://docs.aws.amazon.com/aws-sdk-php/v3/api/index.html).
     */
    public function __construct() {
        $this->Sdk = new \Aws\Sdk([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => AWS_KEY,
                'secret' => AWS_SECRET
            ],
            'S3' => [
                'version' => 'latest'
            ]
        ]);
    }
}