<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* templates/default.php */
class __TwigTemplate_e53e01576491b65d89be08edeaca9b26 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'style' => [$this, 'block_style'],
            'content' => [$this, 'block_content'],
            'script' => [$this, 'block_script'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
  <meta charset=\"UTF-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  <title>Webiste | ";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
  <!-- Bootswatch Materia -->
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/materia/bootstrap.min.css\" integrity=\"sha512-BHK6ttFyaq4IZg5NXAhqbkjOGo5fA4HMhOK9UA9MsYCGWp7QCxv1zZKNrNNzezh0jeQxk+FRC5TeKMxUrrTzrQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />
  <!-- Css Alertify-->
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css\" integrity=\"sha512-IXuoq1aFd2wXs4NqGskwX2Vb+I8UJ+tGJEu/Dc0zwLNKeQ7CW3Sr6v0yU3z5OQWe3eScVIkER4J9L7byrgR/fA==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css\" integrity=\"sha512-RgUjDpwjEDzAb7nkShizCCJ+QTSLIiJO1ldtuxzs0UIBRH4QpOjUU9w47AF9ZlviqV/dOFGWF6o7l3lttEFb6g==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />
  ";
        // line 13
        $this->displayBlock('style', $context, $blocks);
        // line 14
        echo "</head>
<body>
  ";
        // line 16
        $this->loadTemplate("partials/_message.php", "templates/default.php", 16)->display($context);
        // line 17
        echo "  ";
        $this->loadTemplate("partials/_navigation.php", "templates/default.php", 17)->display($context);
        // line 18
        echo "  ";
        $this->displayBlock('content', $context, $blocks);
        // line 19
        echo "
  <!-- Js Bootstrap -->
  <script src=\"https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js\" integrity=\"sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>
  <!-- Js Alertify -->
  <script src=\"https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js\" integrity=\"sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>
  ";
        // line 24
        if (twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "global", [], "any", false, false, false, 24)) {
            // line 25
            echo "    <script>
      let msg = document.getElementsByClassName('global').item(0).innerHTML;
      var notification = alertify.notify(msg, '', 5);
    </script>
  ";
        }
        // line 30
        echo "  ";
        if (twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "success", [], "any", false, false, false, 30)) {
            // line 31
            echo "    <script>
      msg = document.getElementsByClassName('success').item(0).innerHTML;
      var notification = alertify.notify(msg, 'success', 5);
    </script>
  ";
        }
        // line 36
        echo "  ";
        if (twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "error", [], "any", false, false, false, 36)) {
            // line 37
            echo "    <script>
      msg = document.getElementsByClassName('error').item(0).innerHTML;
      var notification = alertify.notify(msg, 'error', 5);
    </script>
  ";
        }
        // line 42
        echo "  
  ";
        // line 43
        $this->displayBlock('script', $context, $blocks);
        // line 44
        echo "</body>
</html>";
    }

    // line 7
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
    }

    // line 13
    public function block_style($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
    }

    // line 18
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
    }

    // line 43
    public function block_script($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
    }

    public function getTemplateName()
    {
        return "templates/default.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  139 => 43,  132 => 18,  125 => 13,  118 => 7,  113 => 44,  111 => 43,  108 => 42,  101 => 37,  98 => 36,  91 => 31,  88 => 30,  81 => 25,  79 => 24,  72 => 19,  69 => 18,  66 => 17,  64 => 16,  60 => 14,  58 => 13,  49 => 7,  41 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "templates/default.php", "/opt/lampp/htdocs/authentication/app/views/templates/default.php");
    }
}
