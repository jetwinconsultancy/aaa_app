<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Jose\Component\KeyManagement\JWKFactory;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;

class Jwks extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function message($to = 'World')
    {
    	echo "Hello {$to}!".PHP_EOL;
    }

    public function key($private = null)
    {
        //------------------------------------------------------------------------------
        $sig_ec_key = JWKFactory::createECKey('P-521', ['use' => 'sig', 'kid' => 'abc_stg_01']);
        $enc_ec_key = JWKFactory::createECKey('P-521', ['use' => 'enc', 'kid' => 'abc_stg_02', 'alg' => 'ECDH-ES+A256KW']);

        $private_jwks = (object) ['keys' => [$sig_ec_key, $enc_ec_key]];  
        $jwks = [(object) ['keys' => [$sig_ec_key->toPublic(), $enc_ec_key->toPublic()]], (object) ['private_keys' => [$sig_ec_key, $enc_ec_key]]];       

        $cache_new_key_storage = new FileStorage("./cache_new_key");
        $cache_new_key = new Cache($cache_new_key_storage);
        $result_new_key = $cache_new_key->load($new_key);

        if ($result_new_key) {
            $jwks = $result_new_key;
        }
        else {
            // if ($result_backup_key) {
            //     $merge_backup_key = array_push($result_backup_key, $jwks);
            //     $cache_backup_key->save($backup_key, $merge_backup_key);
            // }
            // else
            // {
            //     $cache_backup_key->save($backup_key, [$jwks]);
            // }
            $cache_new_key->save($new_key, $jwks, array(Cache::EXPIRE => '1 hours'));//, array(Cache::EXPIRE => '1 hours')
        }
        //--------------------------------------------------------------------------------------
        if($private == true)
        {
            return $jwks;
        }
        else
        {
            echo json_encode($jwks[0]);
        }
    }

    public function getAAASingpassPrivatekey()
    {
        $cache_new_key_storage = new FileStorage("./cache_new_key");
        $cache_new_key = new Cache($cache_new_key_storage);
        $result_new_key = $cache_new_key->load($new_key);

        if ($result_new_key) {
            $jwks = $result_new_key;
        }
        else {
            $updateKeys = $this->key(true);
            $jwks = $updateKeys;
        }

        echo json_encode($jwks[1]);
    }
}

