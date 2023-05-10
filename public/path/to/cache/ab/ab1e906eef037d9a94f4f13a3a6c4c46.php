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

/* partials/_navigation.php */
class __TwigTemplate_e80a3d2a8f4b3060752aea7c65840879 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<header class=\"mb-5\">
  <nav class=\"navbar navbar-expand-lg navbar-dark bg-primary\">
    <div class=\"container-fluid\">
      <a class=\"navbar-brand\" href=\"";
        // line 4
        echo twig_escape_filter($this->env, $this->env->getRuntime('Slim\Views\TwigRuntimeExtension')->urlFor("home"), "html", null, true);
        echo "\">Login system</a>
      <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarColor01\" aria-controls=\"navbarColor01\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
        <span class=\"navbar-toggler-icon\"></span>
      </button>
      
      <div class=\"collapse navbar-collapse\" id=\"navbarColor01\">
        <ul class=\"navbar-nav ms-auto\">
          <li class=\"nav-item\">
            <a class=\"nav-link active\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getRuntime('Slim\Views\TwigRuntimeExtension')->urlFor("home"), "html", null, true);
        echo "\">Home
              <span class=\"visually-hidden\">(current)</span>
            </a>
          </li>



          ";
        // line 19
        if (twig_get_attribute($this->env, $this->source, ($context["session"] ?? null), "user_id", [], "any", false, false, false, 19)) {
            // line 20
            echo "
            <li class=\"nav-item\">
              <a class=\"nav-link\" href=\"\">Username</a>
            </li>  

          ";
        } else {
            // line 26
            echo "
            <li class=\"nav-item\">
              <a class=\"nav-link\" href=\"";
            // line 28
            echo twig_escape_filter($this->env, $this->env->getRuntime('Slim\Views\TwigRuntimeExtension')->urlFor("login"), "html", null, true);
            echo "\">Login</a>
            </li>

            <li class=\"nav-item\">
              <a class=\"nav-link\" href=\"";
            // line 32
            echo twig_escape_filter($this->env, $this->env->getRuntime('Slim\Views\TwigRuntimeExtension')->urlFor("login"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (($__internal_compile_0 = ($context["session"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["username"] ?? null) : null), "html", null, true);
            echo "</a>
            </li>

            <li class=\"nav-item\">
              <a class=\"nav-link\" href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getRuntime('Slim\Views\TwigRuntimeExtension')->urlFor("register"), "html", null, true);
            echo "\">Register</a>
            </li>

          ";
        }
        // line 40
        echo "
        </ul>
      </div>
    </div>
  </nav>
</header>";
    }

    public function getTemplateName()
    {
        return "partials/_navigation.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  100 => 40,  93 => 36,  84 => 32,  77 => 28,  73 => 26,  65 => 20,  63 => 19,  53 => 12,  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "partials/_navigation.php", "/opt/lampp/htdocs/authentication/app/views/templates/partials/_navigation.php");
    }
}
