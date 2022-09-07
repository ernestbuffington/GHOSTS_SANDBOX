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

/* ucp_profile_autologin_keys.html */
class __TwigTemplate_3bca743f29fea2231a97e3c1d0e8147fdb45f9c3b91b74da32b019d617994fb4 extends \Twig\Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_header.html", "ucp_profile_autologin_keys.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo ($context["S_UCP_ACTION"] ?? null);
        echo "\"";
        echo ($context["S_FORM_ENCTYPE"] ?? null);
        echo ">

<h2>";
        // line 5
        echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">
\t\t<p>";
        // line 8
        echo $this->extensions['phpbb\template\twig\extension']->lang("PROFILE_AUTOLOGIN_KEYS");
        echo "</p>
\t\t";
        // line 9
        if (($context["ERROR"] ?? null)) {
            echo "<p class=\"error\">";
            echo ($context["ERROR"] ?? null);
            echo "</p>";
        }
        // line 10
        echo "\t\t";
        $value = 4;
        $context['definition']->set('COLSPAN', $value);
        // line 11
        echo "\t\t<table class=\"table1\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t";
        // line 14
        // line 15
        echo "\t\t\t\t\t<th class=\"name\">";
        echo $this->extensions['phpbb\template\twig\extension']->lang("LOGIN_KEY");
        echo "</th>
\t\t\t\t\t";
        // line 16
        // line 17
        echo "\t\t\t\t\t<th class=\"center\">";
        echo $this->extensions['phpbb\template\twig\extension']->lang("IP");
        echo "</th>
\t\t\t\t\t<th class=\"center\">";
        // line 18
        echo $this->extensions['phpbb\template\twig\extension']->lang("LOGIN_TIME");
        echo "</th>
\t\t\t\t\t";
        // line 19
        // line 20
        echo "\t\t\t\t\t<th class=\"center mark\">";
        echo $this->extensions['phpbb\template\twig\extension']->lang("MARK");
        echo "</th>
\t\t\t\t\t";
        // line 21
        // line 22
        echo "\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "sessions", [], "any", false, false, false, 25));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["sessions"]) {
            // line 26
            echo "\t\t\t\t";
            if ((twig_get_attribute($this->env, $this->source, $context["sessions"], "S_ROW_COUNT", [], "any", false, false, false, 26) % 2 == 0)) {
                echo "<tr class=\"bg1\">";
            } else {
                echo "<tr class=\"bg2\">";
            }
            // line 27
            echo "\t\t\t\t\t";
            // line 28
            echo "\t\t\t\t\t<td><label for=\"";
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "KEY", [], "any", false, false, false, 28);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "KEY", [], "any", false, false, false, 28);
            echo "</label></td>
\t\t\t\t\t";
            // line 29
            // line 30
            echo "\t\t\t\t\t<td class=\"center\">";
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "IP", [], "any", false, false, false, 30);
            echo "</td>
\t\t\t\t\t<td class=\"center\">";
            // line 31
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "LOGIN_TIME", [], "any", false, false, false, 31);
            echo "</td>
\t\t\t\t\t";
            // line 32
            // line 33
            echo "\t\t\t\t\t<td class=\"center mark\"><input type=\"checkbox\" name=\"keys[]\" value=\"";
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "KEY", [], "any", false, false, false, 33);
            echo "\" id=\"";
            echo twig_get_attribute($this->env, $this->source, $context["sessions"], "KEY", [], "any", false, false, false, 33);
            echo "\" /></td>
\t\t\t\t\t";
            // line 34
            // line 35
            echo "\t\t\t\t</tr>
\t\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 37
            echo "\t\t\t\t<tr><td colspan=\"";
            echo twig_get_attribute($this->env, $this->source, ($context["definition"] ?? null), "COLSPAN", [], "any", false, false, false, 37);
            echo "\" class=\"bg1\" style=\"text-align: center\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("PROFILE_NO_AUTOLOGIN_KEYS");
            echo "</td></tr>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sessions'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "\t\t\t</tbody>
\t\t</table>
\t</div>
</div>

";
        // line 44
        if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "sessions", [], "any", false, false, false, 44))) {
            // line 45
            echo "\t<fieldset class=\"display-actions\">
\t\t";
            // line 46
            echo ($context["S_HIDDEN_FIELDS"] ?? null);
            echo "<input type=\"submit\" name=\"submit\" value=\"";
            echo $this->extensions['phpbb\template\twig\extension']->lang("DELETE_MARKED");
            echo "\" class=\"button2\" />
\t\t<div><a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', true); return false;\">";
            // line 47
            echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', false); return false;\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("UNMARK_ALL");
            echo "</a></div>
\t\t";
            // line 48
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</fieldset>
";
        }
        // line 51
        echo "
</form>

";
        // line 54
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_footer.html", "ucp_profile_autologin_keys.html", 54)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_profile_autologin_keys.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  204 => 54,  199 => 51,  193 => 48,  187 => 47,  181 => 46,  178 => 45,  176 => 44,  169 => 39,  158 => 37,  152 => 35,  151 => 34,  144 => 33,  143 => 32,  139 => 31,  134 => 30,  133 => 29,  126 => 28,  124 => 27,  117 => 26,  112 => 25,  107 => 22,  106 => 21,  101 => 20,  100 => 19,  96 => 18,  91 => 17,  90 => 16,  85 => 15,  84 => 14,  79 => 11,  75 => 10,  69 => 9,  65 => 8,  59 => 5,  52 => 3,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "ucp_profile_autologin_keys.html", "");
    }
}
