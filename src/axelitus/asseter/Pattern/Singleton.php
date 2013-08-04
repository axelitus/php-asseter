<?php
/**
 * Part of the axelitus\asseter package.
 *
 * @package     axelitus\asseter
 * @version     0.1
 * @author      Axel Pardemann (axelitusdev@gmail.com)
 * @license     MIT License
 * @copyright   2013 - Axel Pardemann
 * @link        http://axelitus.mx/projects/asseter
 * @source      https://github.com/axelitusdev/asseter
 */

namespace axelitus\asseter;

/**
 * Singleton Pattern class.
 *
 * An abstract class to inherit from which enforces the behavior of the Singleton Pattern.
 *
 * @internal
 * @since       0.1     introduced Pattern_Singleton
 */
abstract class Pattern_Singleton
{
    //region Constants

    /**
     * @since   0.1     introduced INIT_METHOD_NAME
     * @type    string      The initialization method name (declaration of this method is optional)
     */
    const INIT_METHOD_NAME = 'init';

    //endregion


    //region Static Attributes

    /**
     * @static
     * @since       0.1     introduced $instance
     * @type    mixed       The singleton's instance
     **/
    protected static $instance = null;

    //endregion


    //region Constructors

    /**
     * Prevent this class from being instantiated (but allow sub-classes to create new instances).
     *
     * @since       0.1     introduced final protected function __construct()
     */
    final protected function __construct()
    {
    }

    //endregion


    //region Static Methods/Functions

    /**
     * Forges a new instance of the singleton or returns the existing one.
     *
     * Forges a new instance of the singleton or returns the existing one. The parameters are passed along to the
     * initialization method if exists to auto-initialize (configure) the newly created singleton instance.
     *
     * @since       0.1     introduced new method instance($params = null)
     * @static
     * @param   mixed $params,...     The singleton's initialization parameters
     * @return  mixed       The newly created singleton's instance
     */
    public static function instance($params = null)
    {
        if (static::$instance == null or !static::$instance instanceof static) {
            static::$instance = new static();

            if (method_exists(static::$instance, static::INIT_METHOD_NAME) and is_callable(
                    array(
                        static::$instance,
                        static::INIT_METHOD_NAME
                    )
                )
            ) {
                call_user_func_array(array(static::$instance, static::INIT_METHOD_NAME), func_get_args());
            }
        }

        return static::$instance;
    }

    /**
     * Kills the singleton's instance.
     *
     * @static
     * @since       0.1     introduced new method kill()
     */
    public static function kill()
    {
        static::$instance = null;
        unset(static::$instance);
    }

    /**
     * Forges a new instance of the singleton and replaces the existing one.
     *
     * Forges a new instance of the singleton and replaces the existing one. The parameters are passed along to
     * the initialization method if exists to auto-initialize (configure) the singleton.
     *
     * @static
     * @since       0.1     introduced new method renew($params = null)
     * @param mixed $params,...     The singleton's initialization parameters
     * @return mixed    The newly created singleton's instance
     */
    public static function renew($params = null)
    {
        static::kill();
        return call_user_func_array(get_called_class() . '::instance', func_get_args());
    }

    //endregion


    //region Singleton Pattern Enforcement

    /**
     * No serialization allowed
     *
     * @final
     * @since       0.1     introduced final public function __sleep()
     */
    final public function __sleep()
    {
        trigger_error("No serialization allowed!", E_USER_ERROR);
    }

    /**
     * No unserialization allowed
     *
     * @final
     * @since       0.1     introduced final public function __wakeup()
     */
    final public function __wakeup()
    {
        trigger_error("No unserialization allowed!", E_USER_ERROR);
    }

    /**
     * No cloning allowed
     *
     * @final
     * @since       0.1     introduced final public function __clone()
     */
    final public function __clone()
    {
        trigger_error("No cloning allowed!", E_USER_ERROR);
    }

    //endregion
}