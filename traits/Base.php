<?php

namespace Anton;

class Base
{
    public array $config = [
        'path' => 'storage/projects.json',
        'storage' => 'storage',
        'storage/logs' => 'logs',
        'workspace' => 'workspace'
    ];

    public function getConfig()
    {
        return $this->config;
    }

    public function getBranch()
    {
        return exec('git rev-parse --abbrev-ref HEAD');
    }

    public function getConfigByBranch(string $branch, string $dir)
    {
        $json = file_get_contents($dir . '/.anton/config.json');
        $data = json_decode($json);
        $pipeline = $this->findPipeline($branch, $data->pipelines);
        return $this->findServer($data->servers, (array) $pipeline);
    }

    public function findServer($data, array $pipeline)
    {
        foreach ($data as $key => $server) {
            if ($key == $pipeline['server']) {
                return $server;
            }
        }

        throw new  \Exception('Server not found');
    }

    public function findPipeline(string $branch, $pipelines)
    {
        foreach ($pipelines as $pipeline) {
            if ($branch == $pipeline->branch) {
                return $pipeline;
            }
        }
        return [];
    }

    public function initProject(string $project, string $pipeline):array{
        $projectConfig = json_decode(file_get_contents('workspace/'.$project.'/.anton/config.json'));
        $server = [];

        foreach ($projectConfig->pipelines as $index => $pipe){
            if($index == $pipeline ){
                $serv = $pipe->server;
                $branch = $pipe->branch;
            }
        }

        if($serv === null || $branch == null){
            throw new \Exception('Pipeline unknown');
        }

        foreach ($projectConfig->servers as $key => $server){
            if($key == $serv){
                $server = $serv;
            }
        }

        return [
            'project' => $projectConfig,
            'server' => $server,
            'branch' => $branch,
            'timestamp' => time()
        ];
    }

}