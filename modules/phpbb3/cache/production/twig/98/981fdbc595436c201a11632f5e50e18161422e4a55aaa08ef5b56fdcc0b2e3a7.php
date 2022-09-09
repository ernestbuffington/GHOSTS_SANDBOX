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

/* acp_words.html */
class __TwigTemplate_4d25afa680b499f618ee827236269a2200b3fe77d9d203bf4f99f39ef8a1f042 extends \Twig\Template
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
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "acp_words.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<a id=\"maincontent\"></a>

";
        // line 5
        if (($context["S_EDIT_WORD"] ?? null)) {
            // line 6
            echo "
\t<a href=\"";
            // line 7
            echo ($context["U_BACK"] ?? null);
            echo "\" style=\"float: ";
            echo ($context["S_CONTENT_FLOW_END"] ?? null);
            echo ";\">&laquo; ";
            echo $this->extensions['phpbb\template\twig\extension']->lang("BACK");
            echo "</a>

\t<h1>";
            // line 9
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_WORDS");
            echo "</h1>

\t<p>";
            // line 11
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_WORDS_EXPLAIN");
            echo "</p>

\t<form id=\"acp_words\" method=\"post\" action=\"";
            // line 13
            echo ($context["U_ACTION"] ?? null);
            echo "\">

\t<fieldset>
\t\t<legend>";
            // line 16
            echo $this->extensions['phpbb\template\twig\extension']->lang("EDIT_WORD");
            echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"word\">";
            // line 18
            echo $this->extensions['phpbb\template\twig\extension']->lang("WORD");
            echo "</label></dt>
\t\t\t<dd><input id=\"word\" type=\"text\" name=\"word\" value=\"";
            // line 19
            echo ($context["WORD"] ?? null);
            echo "\" maxlength=\"255\" /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"replacement\">";
            // line 22
            echo $this->extensions['phpbb\template\twig\extension']->lang("REPLACEMENT");
            echo "</label></dt>
\t\t\t<dd><input id=\"replacement\" type=\"text\" name=\"replacement\" value=\"";
            // line 23
            echo ($context["REPLACEMENT"] ?? null);
            echo "\" maxlength=\"255\" /></dd>
\t\t</dl>
\t\t";
            // line 25
            echo ($context["S_HIDDEN_FIELDS"] ?? null);
            echo "

\t<p class=\"submit-buttons\">
\t\t<input class=\"button1\" type=\"submit\" id=\"submit\" name=\"save\" value=\"";
            // line 28
            echo $this->extensions['phpbb\template\twig\extension']->lang("SUBMIT");
            echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" id=\"reset\" name=\"reset\" value=\"";
            // line 29
            echo $this->extensions['phpbb\template\twig\extension']->lang("RESET");
            echo "\" />
\t\t";
            // line 30
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</p>
\t</fieldset>
\t</form>

";
        } else {
            // line 36
            echo "
\t<h1>";
            // line 37
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_WORDS");
            echo "</h1>

\t<p>";
            // line 39
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_WORDS_EXPLAIN");
            echo "</p>

\t<form id=\"acp_words\" method=\"post\" action=\"";
            // line 41
            echo ($context["U_ACTION"] ?? null);
            echo "\">

\t<fieldset class=\"tabulated\">
\t<legend>";
            // line 44
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_WORDS");
            echo "</legend>
\t<p class=\"quick\">
\t\t";
            // line 46
            echo ($context["S_HIDDEN_FIELDS"] ?? null);
            echo "
\t\t<input class=\"button2\" name=\"add\" type=\"submit\" value=\"";
            // line 47
            echo $this->extensions['phpbb\template\twig\extension']->lang("ADD_WORD");
            echo "\" />
\t</p>

\t<table class=\"table1 zebra-table\">
\t<thead>
\t<tr>
\t\t<th>";
            // line 53
            echo $this->extensions['phpbb\template\twig\extension']->lang("WORD");
            echo "</th>
\t\t<th>";
            // line 54
            echo $this->extensions['phpbb\template\twig\extension']->lang("REPLACEMENT");
            echo "</th>
\t\t<th>";
            // line 55
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACTION");
            echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
            // line 59
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "words", [], "any", false, false, false, 59));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["words"]) {
                // line 60
                echo "\t<tr>
\t\t<td style=\"text-align: center;\">";
                // line 61
                echo twig_get_attribute($this->env, $this->source, $context["words"], "WORD", [], "any", false, false, false, 61);
                echo "</td>
\t\t<td style=\"text-align: center;\">";
                // line 62
                echo twig_get_attribute($this->env, $this->source, $context["words"], "REPLACEMENT", [], "any", false, false, false, 62);
                echo "</td>
\t\t<td>&nbsp;<a href=\"";
                // line 63
                echo twig_get_attribute($this->env, $this->source, $context["words"], "U_EDIT", [], "any", false, false, false, 63);
                echo "\">";
                echo ($context["ICON_EDIT"] ?? null);
                echo "</a>&nbsp;&nbsp;<a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["words"], "U_DELETE", [], "any", false, false, false, 63);
                echo "\" data-ajax=\"row_delete\">";
                echo ($context["ICON_DELETE"] ?? null);
                echo "</a>&nbsp;</td>
\t</tr>
\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 66
                echo "\t<tr class=\"row3\">
\t\t<td colspan=\"3\">";
                // line 67
                echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_NO_ITEMS");
                echo "</td>
\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['words'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 70
            echo "\t</tbody>
\t</table>
\t";
            // line 72
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</fieldset>
\t</form>
";
        }
        // line 76
        echo "
";
        // line 77
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "acp_words.html", 77)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "acp_words.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  239 => 77,  236 => 76,  229 => 72,  225 => 70,  216 => 67,  213 => 66,  199 => 63,  195 => 62,  191 => 61,  188 => 60,  183 => 59,  176 => 55,  172 => 54,  168 => 53,  159 => 47,  155 => 46,  150 => 44,  144 => 41,  139 => 39,  134 => 37,  131 => 36,  122 => 30,  118 => 29,  114 => 28,  108 => 25,  103 => 23,  99 => 22,  93 => 19,  89 => 18,  84 => 16,  78 => 13,  73 => 11,  68 => 9,  59 => 7,  56 => 6,  54 => 5,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "acp_words.html", "");
    }
}
