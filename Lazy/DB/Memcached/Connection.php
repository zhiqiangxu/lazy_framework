<?php
namespace Lazy\DB\Memcached;

class Connection {
    private $data_list = null;
    private $conn = null;

    public function __construct($array)
    {
        $this->data_list = $array;
    }

    public function join() {
        if (!$this->conn) {
            $memcached = new Memcached();
            $memcached->setOption(Memcached::OPT_HASH, Memcached::HASH_DEFAULT);
            $memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
            $memcached->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            foreach ($this->data_list as $data)
            {
                $memcached->addServer($data['host'], $data['port']);
            }

            $this->conn = $memcached;
        }
    }
}

