<?php

class ThemeHouse_Phrases_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_Phrases' => array(
                'controller' => array(
                    'XenForo_ControllerAdmin_Language',
                    'XenForo_ControllerAdmin_Phrase'
                ),
                'model' => array(
                    'XenForo_Model_Phrase'
                ),
                'route_prefix' => array(
                    'XenForo_Route_PrefixAdmin_AddOns',
                    'XenForo_Route_PrefixAdmin_Languages'
                ),
            ),
        );
    }

    public static function loadClassController($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_Phrases_Listener_LoadClass', $class, $extend, 'controller');
    }

    public static function loadClassModel($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_Phrases_Listener_LoadClass', $class, $extend, 'model');
    }

    public static function loadClassRoutePrefix($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_Phrases_Listener_LoadClass', $class, $extend, 'route_prefix');
    }
}