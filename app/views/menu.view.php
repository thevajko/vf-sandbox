<?php
/**
 *
 * This
 *
 * @author Gregory Beaver <cellog@php.net>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 *
 * @var mixed $data - Data containing object
 * @var \vajko\core\Render $render
 * @var mixed $control
 * @var mixed $action
 */
?>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Vajko framework</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav"><?php

                $actualPageAction = $control.$action;

                ?>
                <li class="<?php echo ($actualPageAction == "PageControlDefault" ? 'active' : '');?>">  <a href="<?php echo $render->baseUrl?>">Home</a></li>
                <li class="<?php echo ($actualPageAction == "PageControlModel" ? 'active' : '');?>">    <a href="<?php echo $render->baseUrl?>/page/model">Some model</a></li>
                <li class="<?php echo ($actualPageAction == "JsonControlDefault" ? 'active' : '');?>">  <a href="<?php echo $render->baseUrl?>/json/model-in-json">Some model JSON</a></li>
        </div><!--/.nav-collapse -->
    </div>
</nav>