<?php

namespace Cache\Adapter;

use Cache\Cache;
use Symfony\Component\Finder\Finder;
use Exception;

require __DIR__.'/file_helpers.php';

class File implements AdapterInterface
{
    protected $cache;

    public function __construct(Cache $cache, array $config = [])
    {
        $this->cache = $cache;
        $this->config = $config + [
            'path' => '/var/tmp/php-cache',
        ];
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function delete($key)
    {
        $keyFilePath = $this->config['path'] .'/'. $key;

        if (file_exists($keyFilePath)) {
            if (is_dir($keyFilePath)) {
                return rrmdir($keyFilePath);
            }

            return unlink($keyFilePath);
        }

        return true;
    }

    public function keys()
    {
        $finder = new Finder();

        $result = $finder
            // ->files()
            ->in($this->config['path']);

        $ret = [];

        foreach ($result as $file) {
            $ret[] = $file->getRelativePathname();
        }

        return $ret;
    }

    public function fetch($key)
    {
        $path = $this->config['path'] . '/' . $key;
        $value = null;

        if (file_exists($path)) {
            $value = unserialize(file_get_contents($path));
        }

        return $value;
    }

    public function store($key, $value, $overwrite = true)
    {
        $cachePath = $this->config['path'];
        $path = $cachePath . '/' . $key;
        $dir = dirname($path);

        if ( ! file_exists($dir)) {
            if ( ! @mkdir($dir, 0777, true)) {
                throw new Exception('The cache directory does not exist, and could not be made: ' . $dir);
            }
        }

        if (file_exists($path) && $overwrite || ! file_exists($path)) {
            if (@file_put_contents($path, serialize($value))) {
                return true;
            } else {
                throw new Exception('Cache is not writeable: ' . $path);
            }
        }

        return false;
    }
}
