<?php

namespace Anton;

trait Init
{
    public function __constructor(): void
    {
        $this->base = new Base();
    }

    public function prepareFolders(){
        $config = $this->base->getConfig();

        if(!file_exists($config['storage']) && !is_dir($config['storage'])){
            exec('mkdir ' . $config['storage']);
        }

        if(!file_exists($config['workspace']) && !is_dir($config['workspace'])) {
            exec('mkdir ' . $config['workspace']);
        }

        if(!file_exists($config['logs']) && !is_dir($config['logs'])) {
            exec('mkdir ' . $config['logs']);
        }
    }

    public function AntonInit(): void
    {
        $this->prepareFolders();
    }
}