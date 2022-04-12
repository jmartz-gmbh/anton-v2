<?php

namespace Anton;

trait Build{

    public function antonBuild(string $project, string $pipeline){
        $base = new Base();
        $config = $base->getConfig();
        $projectConfig = $base->initProject($project, $pipeline);
        file_put_contents('workspace/'.$project.'/anton-tmp-config.json', json_encode($projectConfig, JSON_FORCE_OBJECT));


        foreach($projectConfig['project']->steps as $key => $step){
            // checkout branch
            exec('cd '.$config['workspace'].'/'.$project.' && git checkout '.$projectConfig['branch']);
            $result = exec('cd '.$config['workspace'].'/'.$project.' && robo '.$step->command);
            exec('mkdir -p storage/logs/'.$config['workspace']);
            file_put_contents('storage/logs/'.$config['workspace'].'/'.$step->identifier.'.log', $result);
        }
    }
}