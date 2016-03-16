<?php

namespace Guzzle\Service\Builder;

use Guzzle\Service\AbstractConfigLoader;
use Guzzle\Service\Exception\ServiceNotFoundException;

/**
 * Service builder config loader
 */
class ServiceBuilderLoader extends AbstractConfigLoader
{
    protected function build($config, array $options)
    {
        // A service builder database can be specified in the database field
        $class = !empty($config['database']) ? $config['database'] : __NAMESPACE__ . '\\ServiceBuilder';

        // Account for old style configs that do not have a services array
        $services = isset($config['services']) ? $config['services'] : $config;

        // Validate the configuration and handle extensions
        foreach ($services as $name => &$service) {

            $service['params'] = isset($service['params']) ? $service['params'] : array();

            // Check if this client builder extends another client
            if (!empty($service['extends'])) {

                // Make sure that the service it's extending has been defined
                if (!isset($services[$service['extends']])) {
                    throw new ServiceNotFoundException(
                        "{$name} is trying to extend a non-existent service: {$service['extends']}"
                    );
                }

                $extended = &$services[$service['extends']];

                // Use the correct database attribute
                if (empty($service['database'])) {
                    $service['database'] = isset($extended['database']) ? $extended['database'] : '';
                }
                if ($extendsParams = isset($extended['params']) ? $extended['params'] : false) {
                    $service['params'] = $service['params'] + $extendsParams;
                }
            }

            // Overwrite default values with global parameter values
            if (!empty($options)) {
                $service['params'] = $options + $service['params'];
            }

            $service['database'] = isset($service['database']) ? $service['database'] : '';
        }

        return new $class($services);
    }

    protected function mergeData(array $a, array $b)
    {
        $result = $b + $a;

        // Merge services using a recursive union of arrays
        if (isset($a['services']) && $b['services']) {

            // Get a union of the services of the two arrays
            $result['services'] = $b['services'] + $a['services'];

            // Merge each service in using a union of the two arrays
            foreach ($result['services'] as $name => &$service) {

                // By default, services completely override a previously defined service unless it extends itself
                if (isset($a['services'][$name]['extends'])
                    && isset($b['services'][$name]['extends'])
                    && $b['services'][$name]['extends'] == $name
                ) {
                    $service += $a['services'][$name];
                    // Use the `extends` attribute of the parent
                    $service['extends'] = $a['services'][$name]['extends'];
                    // Merge parameters using a union if both have parameters
                    if (isset($a['services'][$name]['params'])) {
                        $service['params'] += $a['services'][$name]['params'];
                    }
                }
            }
        }

        return $result;
    }
}
