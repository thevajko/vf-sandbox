<?php
/**
 *
 *
 *
 * This is main template file for HTML presentation of model
 *
 * You can use this variables:
 *
 * @var mixed $data - Data containing object
 * @var \vajko\core\Render $render
 * @var mixed $control
 * @var mixed $action
 */
?>
<html>
<head>
    <!-- Current page title -->
    <title><?php echo $data->pageTitle; ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="<?php echo $render->baseUrl ?>/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $render->baseUrl ?>/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?php echo $render->baseUrl ?>/bootstrap-3.3.6-dist/bootstrap-theme.theme.css" rel="stylesheet">


</head>
<body  role="document">

    <?php
        //adding common-template for menu display
        echo $render->renderTemplate("menu.view")
    ?>

    <div class="container theme-showcase" role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h1>Hurey!</h1>
            <p>It looks like <?php echo $render->data->framework ?> is running! Feel free to modify content and build your own page!</p>
        </div>

    <?php
        //rendering custom page content
        echo $render->renderTemplate($control.".".$render->action.".view", $data->content);
    ?>
    </div>

    <div class="container footer">
        <?php
        //adding common-template for footer display
        echo $render->renderTemplate("footer.view")
        ?>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?php echo $render->baseUrl ?>/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
</body>
</html>