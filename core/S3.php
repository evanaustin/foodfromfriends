<?php

/**
 * Used for interacting with the FoodFromFriends S3 buckets.
 */
 
class S3 {
    /**
     * An instance of the S3 client.
     *
     * @var object \Aws\S3
     */
    private $S3;

    public $Bucket;
    
    /**
     * Initializes S3
     *
     * @param $Aws AWS SDK
     */
    public function __construct($Aws) {
        $this->S3 = $Aws->Sdk->createS3();
        $this->Bucket;
    }

    /**
    * Each environment of the FFF website is serviced by its own S3 bucket
    * for static resources.
    *
    * @return string The name of the bucket associated with the current environment.
    */
    public function bucket() {
        return 'foodfromfriends';
        
        /* return \Env::value_from_env(
            'dev',
            'stage',
            'prod'
        ); */
    }

    /**
    * For a given "folder" within an S3 bucket, return a list of all 
    * "files" therein.
    *
    * @param string $folder The containing folder of the objects you're searching for.
    * @param string $bucket The bucket to search.  Defaults to the current environment's bucket.
    * @return array The keys of each object returned by the search.
    */
    public function list_objects($folder, $bucket=null) {
        if (!isset($bucket)) {
            $bucket = $this->bucket();
        }

        $results = $this->S3->getPaginator('ListObjects', [
            'Bucket' => $bucket,
            'Prefix' => $folder
        ]);

        $list = array();

        foreach ($results as $result) {
            foreach ($result['Contents'] as $object) {
                if ($object['Size'] > 0) {
                    $list []= $object['Key'];
                }
            }
        }

        return $list;
    }

    /**
    * Replaces a bucket's policy with a new one.  We could use $this->bucket()
    * to retrieve a default bucket name if no name was provided, but that could
    * be disastrous in the case of an inadvertent omission (we wouldn't want the 
    * main foodfromfriends bucket policy to be accidentally overwritten).
    *
    * See [Amazon's documentation](http://docs.aws.amazon.com/aws-sdk-php/v2/api/class-Aws.S3.S3Client.html#_putBucketPolicy) 
    * on putBucketPolicy().
    *
    * @param string $bucket The name of the bucket to be updated.
    * @param array $policy PHP array of desired bucket policy.  Will be converted to JSON.
    * @return void
    */
    public function update_bucket_policy($bucket, array $policy) {
        $this->S3->putBucketPolicy([
            'Bucket' => $bucket,
            'Policy' => json_encode($policy)
        ]);
    }

    /**
    * Fetches a bucket's policy and returns it in PHP array format.
    *
    * See [Amazon's documentation](http://docs.aws.amazon.com/aws-sdk-php/v2/api/class-Aws.S3.S3Client.html#_getBucketPolicy) 
    * on getBucketPolicy().
    *
    * @param string $bucket The name of the bucket policy to grab.  Defaults to the current environment's bucket.
    * @return array The requested bucket policy in PHP array format.
    */
    public function get_bucket_policy($bucket=null) {
        if (!isset($bucket)) {
            $bucket = $this->bucket();
        }

        $response = $this->S3->getBucketPolicy([
            'Bucket' => $bucket
        ]);

        return json_decode($response['Policy'], true);
    }

    /**
    * Deletes object(s) from one of the foodfromfriends buckets.
    *
    * @param array $objects The paths to each object we want to delete.
    * @return array The responses from AWS (one response per each delete operation).
    */
    public function delete_objects(array $objects) {
        if (count($objects) == 0) {
            throw new \Exception('Please provide an object to delete.');
        }

        foreach ($objects as $object) {
            // Objects in a 0/ "folder" are defaults.
            // Don't delete those.
            if (strpos($object, '/0/') === false) {
                $this->S3->deleteObject([
                    'Bucket' => $this->bucket(),
                    'Key' => $object
                ]);
            }
        }
    }

    /**
    * Uploads an object to one of the foodfromfriends buckets.
    *
    * @param string $key Where to save the object in S3.
    * @param resource $file The file to be uploaded.
    * @param null|string $mimetype The mimetype.  If this isn't set, the file will download when acessed at its URL instead of opening in the browser (even for images).  See http://stackoverflow.com/questions/14150854/aws-s3-display-file-inline-instead-of-force-download
    * @param null|string $cache_control Cache options for the file.  Defaults to 3 years.
    * @param null|string $acl Access control options.  Defaults to public read.
    * @param null|int $file_size Optional.  The content-length in bytes.
    * @return array The response from AWS.
    */
    public function save_object($key, $file, $mimetype=null, $cache_control='max-age=94608000, public', $acl='public-read', $file_size=null) {
        $bucket = $this->bucket();

        $obj = [
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $file,
            'CacheControl' => $cache_control,
            'ACL' => $acl
        ];

        if (isset($file_size) && is_int($file_size)) {
            $obj['ContentLength'] = $file_size;
        }

        if (isset($mimetype)) {
            $obj['ContentType'] = $mimetype;
        }

        $response = $this->S3->putObject($obj);

        return $response;
    }

    /**
    * Runs `save_object()` for an array of files to upload.
    *
    * @param array $objects An array of arrays, each of those having a key for each argument in `save_object`: `key`, `file`, `file_size`, `cache_control`, `acl` (same defaults).
    * @return array The responses from AWS (one per object).
    */
    public function save_objects(array $objects) {
        if (count($objects) == 0) {
            throw new \Exception('Please provide an object to save.');
        }

        $bucket = $this->bucket();
        $responses = [];

        for ($i=0; $i<count($objects); $i++) {
            if (!isset($objects[$i]['file_size'])) {
                $objects[$i]['file_size'] = null;
            }

            if (!isset($objects[$i]['cache_control'])) {
                $objects[$i]['cache_control'] = 'max-age=94608000, public';
            }

            if (!isset($objects[$i]['acl'])) {
                $objects[$i]['acl'] = 'public-read';
            }

            $responses []= $this->save_object(
                $objects[$i]['key'],
                $objects[$i]['file'],
                $objects[$i]['cache_control'],
                $objects[$i]['acl'],
                $objects[$i]['file_size']
            );
        }

        return $responses;
    }
    
    /**
    * Copy object(s) from one of the foodfromfriends buckets.
    *
    * @param array $objects The paths to each object we want to copy.
    * @return array The responses from AWS (one response per each copy operation).
    */
    public function copy_objects(array $objects) {
        if (count($objects) == 0) {
            throw new \Exception('Please provide an object to copy.');
        }
        
        $bucket = $this->bucket();
        
        $responses = [];

        foreach ($objects as $object) {
            $responses []= $this->S3->copyObject([
                'Bucket' => $bucket,
                'Key' => $object['targetKeyname'],
                'CopySource' => "{$bucket}/{$object['sourceKeyname']}"
            ]);
        }

        return $responses;
    }

    public function get_object($key, $bucket = null) {
        if (!isset($bucket)) {
            $bucket = $this->bucket();
        }
        
        $response = $this->S3->getObject([
            'Bucket' => $bucket,
            'Key'    => $key
        ]);

        return $response;
    }
}