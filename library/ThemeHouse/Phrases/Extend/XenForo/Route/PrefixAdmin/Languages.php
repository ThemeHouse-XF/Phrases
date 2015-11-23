<?php
if (false) {

    class XFCP_ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_Languages extends XenForo_Route_PrefixAdmin_Languages
    {
    }
}

class ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_Languages extends XFCP_ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_Languages
{

    /**
     *
     * @see XenForo_Route_PrefixAdmin_Templates::match()
     */
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        $xenOptions = XenForo_Application::get('options');
    
        $GLOBALS['XenForo_Route_PrefixAdmin_Languages'] = $this;
    
        return parent::match($routePath, $request, $router);
    }
}