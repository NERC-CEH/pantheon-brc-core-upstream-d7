<?php

/**
 * @file
 * Hooks provided by the Chaos Tool Suite.
 *
 * This file is divided into static hooks (hooks with string literal names) and
 * dynamic hooks (hooks with pattern-derived string names).
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Inform CTools about plugin types.
 *
 * @return array
 *  An array of plugin types, keyed by the type name.
 *  See the advanced help topic 'plugins-creating' for details of the array
 *  properties.
 */
function hook_ctools_plugin_type() {
  $plugins['my_type'] = array(
    'load themes' => TRUE,
  );

  return $plugins;
}

/**
 * Tells CTools where to find module-defined plugins.
 *
 * This hook is used to inform the CTools plugin system about the location of a
 * directory that should be searched for files containing plugins of a
 * particular type. CTools invokes this same hook for all plugins, using the
 * two passed parameters to indicate the specific type of plugin for which it
 * is searching.
 *
 * The $plugin_type parameter is self-explanatory - it is the string name of the
 * plugin type (e.g., Panels' 'layouts' or 'styles'). The $owner parameter is
 * necessary because CTools internally namespaces plugins by the module that
 * owns them. This is an extension of Drupal best practices on avoiding global
 * namespace pollution by prepending your module name to all its functions.
 * Consequently, it is possible for two different modules to create a plugin
 * type with exactly the same name and have them operate in harmony. In fact,
 * this system renders it impossible for modules to encroach on other modules'
 * plugin namespaces.
 *
 * Given this namespacing, it is important that implementations of this hook
 * check BOTH the $owner and $plugin_type parameters before returning a path.
 * If your module does not implement plugins for the requested module/plugin
 * combination, it is safe to return nothing at all (or NULL). As a convenience,
 * it is also safe to return a path that does not exist for plugins your module
 * does not implement - see form 2 for a use case.
 *
 * Note that modules implementing a plugin also must implement this hook to
 * instruct CTools as to the location of the plugins. See form 3 for a use case.
 *
 * The conventional structure to return is "plugins/$plugin_type" - that is, a
 * 'plugins' subdirectory in your main module directory, with individual
 * directories contained therein named for the plugin type they contain.
 *
 * @param string $owner
 *   The system name of the module owning the plugin type for which a base
 *   directory location is being requested.
 * @param string $plugin_type
 *   The name of the plugin type for which a base directory is being requested.
 * @return string
 *   The path where CTools' plugin system should search for plugin files,
 *   relative to your module's root. Omit leading and trailing slashes.
 */
function hook_ctools_plugin_directory($owner, $plugin_type) {
  // Form 1 - for a module implementing only the 'content_types' plugin owned
  // by CTools, this would cause the plugin system to search the
  // <moduleroot>/plugins/content_types directory for .inc plugin files.
  if ($owner == 'ctools' && $plugin_type == 'content_types') {
    return 'plugins/content_types';
  }

  // Form 2 - if your module implements only Panels plugins, and has 'layouts'
  // and 'styles' plugins but no 'cache' or 'display_renderers', it is OK to be
  // lazy and return a directory for a plugin you don't actually implement (so
  // long as that directory doesn't exist). This lets you avoid ugly in_array()
  // logic in your conditional, and also makes it easy to add plugins of those
  // types later without having to change this hook implementation.
  if ($owner == 'panels') {
    return "plugins/$plugin_type";
  }

  // Form 3 - CTools makes no assumptions about where your plugins are located,
  // so you still have to implement this hook even for plugins created by your
  // own module.
  if ($owner == 'mymodule') {
    // Yes, this is exactly like Form 2 - just a different reasoning for it.
    return "plugins/$plugin_type";
  }
  // Finally, if nothing matches, it's safe to return nothing at all (or NULL).
}

/**
 * Alter a plugin before it has been processed.
 *
 * This hook is useful for altering flags or other information that will be
 * used or possibly overriden by the process hook if defined.
 *
 * @param array $plugin
 *   An associative array defining a plugin.
 * @param array $info
 *   An associative array of plugin type info.
 */
function hook_ctools_plugin_pre_alter(array &$plugin, array &$info) {
  // Override a function defined by the plugin.
  if ($info['type'] == 'my_type') {
    $plugin['my_flag'] = 'new_value';
  }
}

/**
 * Alter a plugin after it has been processed.
 *
 * This hook is useful for overriding the final values for a plugin after it
 * has been processed.
 *
 * @param array $plugin
 *   An associative array defining a plugin.
 * @param array $info
 *   An associative array of plugin type info.
 */
function hook_ctools_plugin_post_alter(array &$plugin, array &$info) {
  // Override a function defined by the plugin.
  if ($info['type'] == 'my_type') {
    $plugin['my_function'] = 'new_function';
  }
}

/**
 * Alter the list of modules/themes which implement a certain api.
 *
 * The hook named here is just an example, as the real existing hooks are named
 * for example 'hook_views_api_alter'.
 *
 * @param array $list
 *   An array of informations about the implementors of a certain api.
 *   The key of this array are the module names/theme names.
 */
function hook_ctools_api_hook_alter(array &$list) {
  // Alter the path of the node implementation.
  $list['node']['path'] = drupal_get_path('module', 'node');
}

/**
 * Alter the available functions to be used in ctools math expression api.
 *
 * One use case would be to create your own function in your module and
 * allow to use it in the math expression api.
 *
<<<<<<< HEAD
 * @param array $functions
 *   An array which has the functions as value.
 * @param array $context
 *   An array containing an item 'final' whose value is a reference to the
 *   definitions for multiple-arg functions. Use this to add in functions that
 *   require more than one arg.
=======
 * @param $functions
 *    An array which has the functions as value.
>>>>>>> 840cf2e0e (Upload modules,themes and libraries)
 */
function hook_ctools_math_expression_functions_alter(array &$functions, array $context) {
  // Allow to convert from degrees to radians.
  $functions[] = 'deg2rad';

  $multiarg = $context['final'];
  $multiarg['pow'] = array(
    'function' => 'pow',
    'arguments' => 2,
  );
}

/**
 * Alter the available functions to be used in ctools math expression api.
 *
 * One usecase would be to create your own function in your module and
 * allow to use it in the math expression api.
 *
 * @param array $constants
 *   An array of name:value pairs, one for each named constant. Values added
 *   to this array become read-only variables with the value assigned here.
 */
function hook_ctools_math_expression_constants_alter(array &$constants) {
  // Add the speed of light as constant 'c':
  $constants['c'] = 299792458;
}

/**
 * Alter everything.
 *
 * @param array $info
 *   An associative array containing the following keys:
 *   - content: The rendered content.
 *   - title: The content's title.
 *   - no_blocks: A boolean to decide if blocks should be displayed.
 * @param bool $page
 *   If TRUE then this renderer owns the page and can use theme('page')
 *   for no blocks; if false, output is returned regardless of any no
 *   blocks settings.
 * @param array $context
 *   An associative array containing the following keys:
 *   - args: The raw arguments behind the contexts.
 *   - contexts: The context objects in use.
 *   - task: The task object in use.
 *   - subtask: The subtask object in use.
 *   - handler: The handler object in use.
 */
function hook_ctools_render_alter(array &$info, &$page, array &$context) {
  if ($context['handler']->name == 'my_handler') {
    ctools_add_css('my_module.theme', 'my_module');
  }
}

/**
 * Alter a content plugin subtype.
 *
 * While content types can be altered via hook_ctools_plugin_pre_alter() or
 * hook_ctools_plugin_post_alter(), the subtypes that content types rely on
 * are special and require their own hook.
 *
 * This hook can be used to add things like 'render last' or change icons
 * or categories or to rename content on specific sites.
 */
function hook_ctools_content_subtype_alter($subtype, $plugin) {
  // Force a particular subtype of a particular plugin to render last.
  if ($plugin['module'] == 'some_plugin_module' && $plugin['name'] == 'some_plugin_name' && $subtype['subtype_id'] == 'my_subtype_id') {
    $subtype['render last'] = TRUE;
  }
}

/**
 * Alter the definition of an entity context plugin.
 *
 * @param array $plugin
 *   An associative array defining a plugin.
 * @param array $entity
 *   The entity info array of a specific entity type.
 * @param string $plugin_id
 *   The plugin ID, in the format NAME:KEY.
 */
function hook_ctools_entity_context_alter(array &$plugin, array &$entity, $plugin_id) {
  ctools_include('context');
  switch ($plugin_id) {
    case 'entity_id:taxonomy_term':
      $plugin['no ui'] = TRUE;
    case 'entity:user':
      $plugin = ctools_get_context('user');
      unset($plugin['no ui']);
      unset($plugin['no required context ui']);
      break;
  }
}

/**
<<<<<<< HEAD
 * Alter the conversion of context items by ctools context plugin convert()s.
 *
 * @param ctools_context $context
 *   The current context plugin object. If this implemented a 'convert'
 *   function, the value passed in has been processed by that function.
 * @param string $converter
 *   A string associated with the plugin type, identifying the operation.
 * @param string $value
 *   The value being converted; this is the only return from the function.
 * @param array $converter_options
 *   Array of key-value pairs to pass to a converter function from higher
 *   levels.
 *
 * @see ctools_context_convert_context()
 */
function hook_ctools_context_converter_alter(ctools_context $context, $converter, &$value, array $converter_options) {
  if ($converter === 'mystring') {
    $value = 'fixed';
  }
}

/**
=======
>>>>>>> 840cf2e0e (Upload modules,themes and libraries)
 * Alter the definition of entity context plugins.
 *
 * @param array $plugins
 *   An associative array of plugin definitions, keyed by plugin ID.
 *
 * @see hook_ctools_entity_context_alter()
 */
function hook_ctools_entity_contexts_alter(array &$plugins) {
  $plugins['entity_id:taxonomy_term']['no ui'] = TRUE;
}

/**
 * Change cleanstring settings.
 *
 * @param array $settings
 *   An associative array of cleanstring settings.
 *
 * @see ctools_cleanstring()
 */
function hook_ctools_cleanstring_alter(array &$settings) {
  // Convert all strings to lower case.
  $settings['lower case'] = TRUE;
}

/**
 * Change cleanstring settings for a specific clean ID.
 *
 * @param array $settings
 *   An associative array of cleanstring settings.
 *
 * @see ctools_cleanstring()
 */
function hook_ctools_cleanstring_CLEAN_ID_alter(array &$settings) {
  // Convert all strings to lower case.
  $settings['lower case'] = TRUE;
}

/**
<<<<<<< HEAD
 * Let other modules modify the context handler before it is rendered.
 *
 * @param object $handler
 *   A handler for a current task and subtask.
 * @param array $contexts
 *   An associative array of contexts.
 * @param array $args
 *   An array for current args.
 *
 * @see ctools_context_handler_pre_render()
 */
function ctools_context_handler_pre_render($handler, array $contexts, array $args) {
  $handler->conf['css_id'] = 'my-id';
}

/**
=======
>>>>>>> 840cf2e0e (Upload modules,themes and libraries)
 * @} End of "addtogroup hooks".
 */
