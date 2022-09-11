<?php

class HTMLPurifier_HTMLModuleManager
{

    /**
     * Instance of HTMLPurifier_DoctypeRegistry
     */
    public $doctypes;

    /**
     * Instance of current doctype
     */
    public $doctype;

    /**
     * Instance of HTMLPurifier_AttrTypes
     */
    public $attrTypes;

    /**
     * Active instances of modules for the specified doctype are
     * indexed, by name, in this array.
     */
    public $nuke_modules = array();

    /**
     * Array of recognized HTMLPurifier_Module instances, indexed by
     * module's class name. This array is usually lazy loaded, but a
     * user can overload a module by pre-emptively registering it.
     */
    public $registeredModules = array();

    /**
     * List of extra modules that were added by the user using addModule().
     * These get unconditionally merged into the current doctype, whatever
     * it may be.
     */
    public $userModules = array();

    /**
     * Associative array of element name to list of modules that have
     * definitions for the element; this array is dynamically filled.
     */
    public $elementLookup = array();

    /** List of prefixes we should use for registering small names */
    public $prefixes = array('HTMLPurifier_HTMLModule_');

    public $contentSets;     /**< Instance of HTMLPurifier_ContentSets */
    public $attrCollections; /**< Instance of HTMLPurifier_AttrCollections */

    /** If set to true, unsafe elements and attributes will be allowed */
    public $trusted = false;

    public function __construct() {

        // editable internal objects
        $this->attrTypes = new HTMLPurifier_AttrTypes();
        $this->doctypes  = new HTMLPurifier_DoctypeRegistry();

        // setup basic modules
        $common = array(
            'CommonAttributes', 'Text', 'Hypertext', 'List',
            'Presentation', 'Edit', 'Bdo', 'Tables', 'Image',
            'StyleAttribute',
            // Unsafe:
            'Scripting', 'Object',  'Forms',
            // Sorta legacy, but present in strict:
            'Name',
        );
        $transitional = array('Legacy', 'Target');
        $xml = array('XMLCommonAttributes');
        $non_xml = array('NonXMLCommonAttributes');

        // setup basic doctypes
        $this->doctypes->register(
            'HTML 4.01 Transitional', false,
            array_merge($common, $transitional, $non_xml),
            array('Tidy_Transitional', 'Tidy_Proprietary'),
            array(),
            '-//W3C//DTD HTML 4.01 Transitional//EN',
            'http://www.w3.org/TR/html4/loose.dtd'
        );

        $this->doctypes->register(
            'HTML 4.01 Strict', false,
            array_merge($common, $non_xml),
            array('Tidy_Strict', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD HTML 4.01//EN',
            'http://www.w3.org/TR/html4/strict.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.0 Transitional', true,
            array_merge($common, $transitional, $xml, $non_xml),
            array('Tidy_Transitional', 'Tidy_XHTML', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD XHTML 1.0 Transitional//EN',
            'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.0 Strict', true,
            array_merge($common, $xml, $non_xml),
            array('Tidy_Strict', 'Tidy_XHTML', 'Tidy_Strict', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD XHTML 1.0 Strict//EN',
            'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.1', true,
            array_merge($common, $xml, array('Ruby')),
            array('Tidy_Strict', 'Tidy_XHTML', 'Tidy_Proprietary', 'Tidy_Strict', 'Tidy_Name'), // Tidy_XHTML1_1
            array(),
            '-//W3C//DTD XHTML 1.1//EN',
            'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'
        );

    }

    /**
     * Registers a module to the recognized module list, useful for
     * overloading pre-existing modules.
     * @param $nuke_module Mixed: string module name, with or without
     *                HTMLPurifier_HTMLModule prefix, or instance of
     *                subclass of HTMLPurifier_HTMLModule.
     * @param $overload Boolean whether or not to overload previous modules.
     *                  If this is not set, and you do overload a module,
     *                  HTML Purifier will complain with a warning.
     * @note This function will not call autoload, you must instantiate
     *       (and thus invoke) autoload outside the method.
     * @note If a string is passed as a module name, different variants
     *       will be tested in this order:
     *          - Check for HTMLPurifier_HTMLModule_$name
     *          - Check all prefixes with $name in order they were added
     *          - Check for literal object name
     *          - Throw fatal error
     *       If your object name collides with an internal class, specify
     *       your module manually. All modules must have been included
     *       externally: registerModule will not perform inclusions for you!
     */
    public function registerModule($nuke_module, $overload = false) {
        if (is_string($nuke_module)) {
            // attempt to load the module
            $original_module = $nuke_module;
            $ok = false;
            foreach ($this->prefixes as $prefix) {
                $nuke_module = $prefix . $original_module;
                if (class_exists($nuke_module)) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                $nuke_module = $original_module;
                if (!class_exists($nuke_module)) {
                    trigger_error($original_module . ' module does not exist',
                        E_USER_ERROR);
                    return;
                }
            }
            $nuke_module = new $nuke_module();
        }
        if (empty($nuke_module->name)) {
            trigger_error('Module instance of ' . get_class($nuke_module) . ' must have name');
            return;
        }
        if (!$overload && isset($this->registeredModules[$nuke_module->name])) {
            trigger_error('Overloading ' . $nuke_module->name . ' without explicit overload parameter', E_USER_WARNING);
        }
        $this->registeredModules[$nuke_module->name] = $nuke_module;
    }

    /**
     * Adds a module to the current doctype by first registering it,
     * and then tacking it on to the active doctype
     */
    public function addModule($nuke_module) {
        $this->registerModule($nuke_module);
        if (is_object($nuke_module)) $nuke_module = $nuke_module->name;
        $this->userModules[] = $nuke_module;
    }

    /**
     * Adds a class prefix that registerModule() will use to resolve a
     * string name to a concrete class
     */
    public function addPrefix($prefix) {
        $this->prefixes[] = $prefix;
    }

    /**
     * Performs processing on modules, after being called you may
     * use getElement() and getElements()
     * @param $config Instance of HTMLPurifier_Config
     */
    public function setup($config) {

        $this->trusted = $config->get('HTML.Trusted');

        // generate
        $this->doctype = $this->doctypes->make($config);
        $nuke_modules = $this->doctype->modules;

        // take out the default modules that aren't allowed
        $lookup = $config->get('HTML.AllowedModules');
        $special_cases = $config->get('HTML.CoreModules');

        if (is_array($lookup)) {
            foreach ($nuke_modules as $k => $m) {
                if (isset($special_cases[$m])) continue;
                if (!isset($lookup[$m])) unset($nuke_modules[$k]);
            }
        }

        // add proprietary module (this gets special treatment because
        // it is completely removed from doctypes, etc.)
        if ($config->get('HTML.Proprietary')) {
            $nuke_modules[] = 'Proprietary';
        }

        // add SafeObject/Safeembed modules
        if ($config->get('HTML.SafeObject')) {
            $nuke_modules[] = 'SafeObject';
        }
        if ($config->get('HTML.SafeEmbed')) {
            $nuke_modules[] = 'SafeEmbed';
        }

        // merge in custom modules
        $nuke_modules = array_merge($nuke_modules, $this->userModules);

        foreach ($nuke_modules as $nuke_module) {
            $this->processModule($nuke_module);
            $this->modules[$nuke_module]->setup($config);
        }

        foreach ($this->doctype->tidyModules as $nuke_module) {
            $this->processModule($nuke_module);
            $this->modules[$nuke_module]->setup($config);
        }

        // prepare any injectors
        foreach ($this->modules as $nuke_module) {
            $n = array();
            foreach ($nuke_module->info_injector as $i => $injector) {
                if (!is_object($injector)) {
                    $class = "HTMLPurifier_Injector_$injector";
                    $injector = new $class;
                }
                $n[$injector->name] = $injector;
            }
            $nuke_module->info_injector = $n;
        }

        // setup lookup table based on all valid modules
        foreach ($this->modules as $nuke_module) {
            foreach ($nuke_module->info as $name => $def) {
                if (!isset($this->elementLookup[$name])) {
                    $this->elementLookup[$name] = array();
                }
                $this->elementLookup[$name][] = $nuke_module->name;
            }
        }

        // note the different choice
        $this->contentSets = new HTMLPurifier_ContentSets(
            // content set assembly deals with all possible modules,
            // not just ones deemed to be "safe"
            $this->modules
        );
        $this->attrCollections = new HTMLPurifier_AttrCollections(
            $this->attrTypes,
            // there is no way to directly disable a global attribute,
            // but using AllowedAttributes or simply not including
            // the module in your custom doctype should be sufficient
            $this->modules
        );
    }

    /**
     * Takes a module and adds it to the active module collection,
     * registering it if necessary.
     */
    public function processModule($nuke_module) {
        if (!isset($this->registeredModules[$nuke_module]) || is_object($nuke_module)) {
            $this->registerModule($nuke_module);
        }
        $this->modules[$nuke_module] = $this->registeredModules[$nuke_module];
    }

    /**
     * Retrieves merged element definitions.
     * @return Array of HTMLPurifier_ElementDef
     */
    public function getElements() {

        $elements = array();
        foreach ($this->modules as $nuke_module) {
            if (!$this->trusted && !$nuke_module->safe) continue;
            foreach ($nuke_module->info as $name => $v) {
                if (isset($elements[$name])) continue;
                $elements[$name] = $this->getElement($name);
            }
        }

        // remove dud elements, this happens when an element that
        // appeared to be safe actually wasn't
        foreach ($elements as $n => $v) {
            if ($v === false) unset($elements[$n]);
        }

        return $elements;

    }

    /**
     * Retrieves a single merged element definition
     * @param $name Name of element
     * @param $trusted Boolean trusted overriding parameter: set to true
     *                 if you want the full version of an element
     * @return Merged HTMLPurifier_ElementDef
     * @note You may notice that modules are getting iterated over twice (once
     *       in getElements() and once here). This
     *       is because
     */
    public function getElement($name, $trusted = null) {

        if (!isset($this->elementLookup[$name])) {
            return false;
        }

        // setup global state variables
        $def = false;
        if ($trusted === null) $trusted = $this->trusted;

        // iterate through each module that has registered itself to this
        // element
        foreach($this->elementLookup[$name] as $nuke_module_name) {

            $nuke_module = $this->modules[$nuke_module_name];

            // refuse to create/merge from a module that is deemed unsafe--
            // pretend the module doesn't exist--when trusted mode is not on.
            if (!$trusted && !$nuke_module->safe) {
                continue;
            }

            // clone is used because, ideally speaking, the original
            // definition should not be modified. Usually, this will
            // make no difference, but for consistency's sake
            $new_def = clone $nuke_module->info[$name];

            if (!$def && $new_def->standalone) {
                $def = $new_def;
            } elseif ($def) {
                // This will occur even if $new_def is standalone. In practice,
                // this will usually result in a full replacement.
                $def->mergeIn($new_def);
            } else {
                // :TODO:
                // non-standalone definitions that don't have a standalone
                // to merge into could be deferred to the end
                continue;
            }

            // attribute value expansions
            $this->attrCollections->performInclusions($def->attr);
            $this->attrCollections->expandIdentifiers($def->attr, $this->attrTypes);

            // descendants_are_inline, for ChildDef_Chameleon
            if (is_string($def->content_model) &&
                strpos($def->content_model, 'Inline') !== false) {
                if ($name != 'del' && $name != 'ins') {
                    // this is for you, ins/del
                    $def->descendants_are_inline = true;
                }
            }

            $this->contentSets->generateChildDef($def, $nuke_module);
        }

        // This can occur if there is a blank definition, but no base to
        // mix it in with
        if (!$def) return false;

        // add information on required attributes
        foreach ($def->attr as $attr_name => $attr_def) {
            if ($attr_def->required) {
                $def->required_attr[] = $attr_name;
            }
        }

        return $def;

    }

}

// vim: et sw=4 sts=4
