<?php

namespace Anton;

include('traits/Base.php');

trait Collect
{

    public function __construct()
    {
        $this->base = new Base();
    }

    public function antonCollect()
    {
        $config = $this->base->getConfig();

        if(is_file($config['path'])){
            $projects = json_decode(file_get_contents($config['path']));
            foreach($projects as $key => $project){
                if(is_dir('workspace/'.$key)){
                    exec('rm -rf workspace/'.$key);
                }
                exec('git clone '.$project->repo.' workspace/'.$key);
                $path = 'workspace/'.$key.'/.anton/config.json';
                if(is_file($path)){
                    $projectConfig = json_decode(file_get_contents($path));
                    $projectConfig->timestamp = time();
                    file_put_contents($path, json_encode($projectConfig));
                }
            }
        }
        else{
            $this->say('Projects Config doesnt exists');
        }
    }
}