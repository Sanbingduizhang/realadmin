<?php
namespace App\Support;

use GuzzleHttp\Client;

class OpenPlatform
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function ceshi()
    {
        $res = $this->client->request('GET','http://lubo.com/api/wx/remote/shou');
        return $res->getStatusCode();
    }
}
