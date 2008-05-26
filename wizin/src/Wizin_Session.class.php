<?php
/**
 * Wizin framework session class
 *
 * PHP Versions 4
 *
 * @package  Wizin
 * @author  Makoto Hashiguchi a.k.a. gusagi<gusagi@gusagi.com>
 * @copyright 2008 Makoto Hashiguchi
 * @license http://www.opensource.org/licenses/bsd-license.php The BSD License
 *
 */

if ( ! class_exists('Wizin_Session') ) {
    require 'Wizin.class.php';

    class Wizin_Session extends Wizin_StdClass
    {

        function &getSingleton()
        {
            static $instance;
            if ( ! isset($instance) ) {
                $instance = new Wizin_Session();
            }
            return $instance;
        }

        function overrideSessionIni( $useCookie = true )
        {
            if ( $useCookie ) {
                ini_set( 'session.use_cookies', "1" );
                ini_set( 'session.use_only_cookies', "1" );
            } else {
                ini_set( 'session.use_cookies', "0" );
                ini_set( 'session.use_only_cookies', "0" );
            }
            ini_set( 'session.use_trans_sid', "0" );
        }

        function regenerateId()
        {
            $saveHandler = ini_get( 'session.save_handler' );
            if ( $saveHandler === 'files' ) {

            }
            session_regenerate_id();
        }

    }
}