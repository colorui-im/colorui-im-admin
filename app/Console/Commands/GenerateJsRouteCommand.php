<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Route;
use Illuminate\Filesystem\Filesystem;

use App\Models\Order;

class GenerateJsRouteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:route:js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成route.js';

    protected $filesystem;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateRoutes();
        $this->generateMaps();
    }

    protected function generateRoutes()
    {
        $routes = Route::getRoutes();

        $map = [];
        foreach ($routes as $route) {
            if (!$route->getName()) {
                continue;
            }
            $map[$route->getName()] = ltrim($route->uri(), '/');
        }

        $this->writeJsFile('route', $map);
    }

    public function generateMaps()
    {
        $map = [];
        $classes = [];
        foreach ($classes as $class){
            $ref = new \ReflectionClass($class);
            $className = $ref->getShortName();
            $constants = $ref->getConstants();
            $staticProperties = $ref->getStaticProperties();
            $onlyMapsProperties = [];
            foreach ($staticProperties as $key=>$v){
                if(str_contains($key,'Maps')){
                    $onlyMapsProperties[$key] = $v;
                }
            }
            $methods = $ref->getMethods();

            $methodsMapsProperties=[];
            foreach ($methods as $method) {

                $methodName=$method->getName();

                if(str_contains($methodName,'Maps')){

                    $refMethod = $ref->getMethod($methodName);
                    $methodsMapsProperties[$methodName]=$refMethod->invoke(new $class(), $methodName);
                }

            }
            $map[$className]= array_merge($constants,$onlyMapsProperties,$methodsMapsProperties);
        }
        $this->writeJsFile('model',$map);
    }


    protected function writeJsFile($filename, $data)
    {
//        $this->isDirectory($path = storage_path('app/js'));
//        $path = storage_path(sprintf('app/js/%s.js', $filename));
        $path='/Users/jcc/Downloads/front-colorui/common/'.$filename.'.map.js';
        $content = sprintf('module.exports = %s;', json_encode($data,JSON_UNESCAPED_UNICODE));
        file_put_contents($path, $content);
    }


    public function isDirectory($path)
    {
        if (!$this->filesystem->isDirectory($path)) {
            $this->filesystem->makeDirectory($path);
        }
    }
}
