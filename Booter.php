<?php

namespace vajko\core;

use Exception;
use vajko\core\abstracts\aControlBase;
use vajko\core\interfaces\IContainerItem;
use vajko\core\interfaces\IErrorHandler;
use vajko\core\interfaces\IRender;
use vajko\core\interfaces\IRouter;

/**
 * Class hold whole web-application cycle.
 *
 * Class hold whole web-application cycle. On boot it builds Container, fill it with elemental components and loads other from config.
 * It also build vajko\core\Request object and is responsive for correct controller selection.
 * <br/>
 *
 * <br/>
 * <sup>
 *  This class/script is a part of Vajko framework - very simple and minimal MVC application framework for educational purposes.
 * </sup>
 *
 * @author Matej Meško <meshosk@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/GNU CC BY-NC-SA 4.0
 *
 * @package vajko\core
 *
 */
class Booter
{

    /**
     * @var string Root dir of scipts namespaces
     */
    public $scriptDir = "";

    /**
     * @var Container Object containing all application objects
     */
    public $container = null;

    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var ScriptFinder
     */
    private $scriptFinder;

    /**
     * @var bool Definition of current mode - if show debug info or not
     */
    public $debugMode = false;

    public function __construct()
    {
        //get actual direcotry of this script
        $dirFragments = explode(DIRECTORY_SEPARATOR, __DIR__);

        //remove last two direcotry from array
        array_pop($dirFragments);
        array_pop($dirFragments);

        //put all remaining dir
        $this->scriptDir = implode(DIRECTORY_SEPARATOR, $dirFragments).DIRECTORY_SEPARATOR;

    }

    /**
     * Method which register function for PHP spl autoloading register. This will load script if it is needed - on demand - lazy loading
     */
    public function initScriptLazyAutoloading(){
        //register callback method for on demand class loading
        spl_autoload_register(array($this, 'lazyLoadScript'));
    }

    /**
     * Callback function for on demand loading
     * @param $className
     * @throws \Exception If script is not found.
     * @internal param $classFullName
     */
    public function lazyLoadScript($className){
        $script = $this->scriptDir . str_replace("\\", DIRECTORY_SEPARATOR, $className) . ".php";
        if (file_exists($script)){
            include_once $script;
        } else {
            $message = "Following script was not found: '" . $script. "' when app was searching for class with name '" . $className."'.".
                " Please check namespace, possition of script in directories and class name.";
            throw new \Exception( $message, 500);
        }
    }

    /**
     * Corrects escape characters for of namespace delimiters char so string can be used in regex search
     * @param $string
     * @return mixed
     */
    private function correctEscapeChars($string){
        return preg_replace("/\\\\/", "\\\\\\", $string);
    }

    /**
     *
     *
     * @param string $namespacePart Whole namespace or it part or just name of class
     * @return mixed namespace if matching file is found
     */
    public function getProbableFullClassName($namespacePart){

        //correct backslashes in namespace to dir ones
        $namespacePart = str_replace("\\", DIRECTORY_SEPARATOR, $namespacePart);
        //correct backslashes in namespace that are no longer escape characters
        $namespacePartCorrected = $this->correctEscapeChars($namespacePart);

        $namespace = (!empty($namespacePartCorrected) ? $namespacePartCorrected : $namespacePart );

        $namespace = str_replace("/", '\/', $namespace);
        //first do search in loaded scripts
        $possibleScripts = $this->scriptFinder->findScript("/$namespace/i");

        $fullPathToScript = "";
        //load the fist occurrence of name in scripts
        //file name must contains only class name and use postfix ".php" only, so it contains only one "dot" char
        foreach ($possibleScripts as $possibleScript) {
            $pathParts = explode(DIRECTORY_SEPARATOR, $possibleScript);
            if (substr_count(end($pathParts),".") == 1) {
                $fullPathToScript = $possibleScript;
                break;
            }
        }

        // remove actual path prefix
        $namespace = str_replace($this->scriptDir,"",$fullPathToScript);
        // remove filename
        $filename = basename($namespace);
        $namespace = str_replace($filename,"",$namespace);
        $filenameParts = explode(".",$filename);

        // change directory separator to php namespace separator
        return str_replace(DIRECTORY_SEPARATOR, "\\", $namespace).reset($filenameParts);
    }




    /**
     * Run application
     *
     * This method at first initialize needed components and then starts processing request nd returns response. It handles
     * whole application cycle. Also it catches exeptions and show them.
     *
     * @throws \Exception
     */
    public function run(){
        try {
            //run web app life cycle
            $this->boot();
            //do app life cycle
            $this->processRequest();
        } catch (\Exception $exception){

            // first create a new default vajko render
            // TODO: Loads template from config? Or force to use render from framework
            $defaultRender = new Render();
            // add to render what in needs and initialize it
            $defaultRender->containerInitialization($this->container);
            //get name of class designed for errors handle
            $errorHandlerClass = $this->configurator->get("www.errorClass");

            try {
                /** @var  IErrorHandler $errorHandlerInstance */
                $errorHandlerInstance = new $errorHandlerClass(); //get instance
                $errorHandlerInstance->setDebugMode($this->configurator->get("www.debugMode")); //set debug mode
                $errorHandlerInstance->handleException($exception); // pass exception

            } catch (\Exception $exception) {
                // show error message if there is problem to process the exception
                echo "ERROR: There cannot be created instance of class for error handling.<br/>";
                echo "- Value from configuration: '$errorHandlerClass' <br/>";
                echo "- Please check if namespace correspond with direcotries structure or typo. If is this value empty it is possible, that this value is not set in configiration.<br/>";
            }

        }
    }

    /**
     * Method that creates base instances needed for application
     *
     * @throws \Exception
     */
    private function boot(){
        //start the magic - auto loading
        $this->initScriptLazyAutoloading();

        // !!!! - Below is initialization of core element that cannot be changed

            // Create object for script finding
            $this->scriptFinder = new ScriptFinder($this->scriptDir);

            //application init
            //first step - init container
            $this->container = new Container();

            //add script finder
            $this->container->add($this->scriptFinder, EnumCoreElements::scriptFinder);
            //create configurator
            $this->configurator = new Configurator($this->scriptDir."config.json"); //loading config file is needed to be here
            $this->container->add($this->configurator, EnumCoreElements::config);

        // !!!! - End of initialization of core element that cannot be changed

        //get classes from config, these element can be changed by developer, that is why are loaded from configuration file
        $classesToLoad = (array) $this->configurator->get("www.bootSequence");
        //start autoloading
        foreach ($classesToLoad as $classIdentificator => $classToLoad) {
            //get class name and crete instance
            /** @var IContainerItem $classInstance */
            $classInstance = new $classToLoad();
            //put instance into container
            $this->container->add($classInstance, $classIdentificator);
        }

        //run post fulling method, so components in container can fully initialize
        $this->container->initializeCall();

    }


    /**
     * This method runs aplication life cycle and process request and returns output/response
     *
     * @throws Exception
     */
    private function processRequest(){

        //get routring object
        /** @var IRouter $router */
        $router = $this->container->get(EnumCoreElements::router);
        //check if IRouter interface was used
        $this->checkIfImplementsInterface($router,IRouter::class);


        //get right namespace form for regex search
        //$controlName = preg_replace("/\\\\/", "\\\\\\\\", $request->control);
        $controlName = $router->getControllerName();
        //add namespace to class name
        $controlName = $this->getProbableFullClassName($controlName);

        if (empty($controlName))
            throw new \Exception("Controller '".$router->getControllerName()."' does not exists.",500);

        /** @var aControlBase $actualControl */
        //create instance
        $actualControl = new $controlName();

        //do base control initializations
        $actualControl->setContainer($this->container);

        //get template object
        /** @var IRender $render */
        $render = $this->container->get(EnumCoreElements::render);

        //set template to control for short cut
        $actualControl->setRender($render);
        //build method name for request
        $actionMethod = $router->getControllerAction()."Action";

        if ( method_exists ($actualControl, $actionMethod)){
            $actualControl->$actionMethod();
        } else {
            throw new \Exception("Control '$controlName' do not have method '$actionMethod()'", 500);
        }

        //get default filename or overriden
        $templateFileName = (empty($render->templateFile) ? $this->configurator->get("www.defaultTemplate"): $render->templateFile);
        //start rendering
        $html = $render->renderTemplate($templateFileName, $render->getData());

        //show output
        echo $html;
    }


    /**
     *
     * Method check if object used interface
     *
     * @param mixed $object Object to check
     * @param string $interface interface to check
     * @return bool true if object use interface
     * @throws Exception Throws exception if object do not use interface
     */
    private function checkIfImplementsInterface($object, $interface){
        // get list of interfaces implemented on object
        $implements = class_implements($object, false);
        // correct escape characters for of namespace delimiters char for regex search
        $interface = $this->correctEscapeChars($interface);
        //do search
        if (preg_grep ( "/$interface/i", $implements)){
            return true;
        }
        throw new Exception("Class '".get_class($object)."' do not implements interface '".$interface."'",500);
    }
}