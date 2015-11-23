<?php
if (false) {

    class XFCP_ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_AddOns extends XenForo_Route_PrefixAdmin_AddOns
    {
    }
}

class ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_AddOns extends XFCP_ThemeHouse_Phrases_Extend_XenForo_Route_PrefixAdmin_AddOns
{

    /**
     *
     * @see XenForo_Route_PrefixAdmin_AddOns::match()
     */
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        $parts = explode('/', $routePath, 3);

        switch ($parts[0]) {
            case 'languages':
                $parts = array_slice($parts, 1);
                $routePath = implode('/', $parts);
                $action = $router->resolveActionWithIntegerParam($routePath, $request, 'language_id');
                return $router->getRouteMatch('XenForo_ControllerAdmin_Language', $action, 'languages');
            case 'phrases':
                $parts = array_slice($parts, 1);
                $routePath = implode('/', $parts);
                return $router->getRouteMatch('XenForo_ControllerAdmin_Phrase', $routePath, 'phrases');
        }

        if (count($parts) > 1) {
            switch ($parts[1]) {
                case 'languages':
                    $action = $router->resolveActionWithStringParam($routePath, $request, 'addon_id');
                    $parts = array_slice($parts, 2);
                    $routePath = implode('/', $parts);
                    $action = $router->resolveActionWithIntegerParam($routePath, $request, 'language_id');
                    return $router->getRouteMatch('XenForo_ControllerAdmin_Language', $action, 'languages');
                case 'phrases':
                    $action = $router->resolveActionWithStringParam($routePath, $request, 'addon_id');
                    $parts = array_slice($parts, 2);
                    $routePath = implode('/', $parts);
                    return $router->getRouteMatch('XenForo_ControllerAdmin_Phrase', $routePath, 'phrases');
            }
        }

        return parent::match($routePath, $request, $router);
    }

    /**
     *
     * @see XenForo_Route_PrefixAdmin_AddOns::buildLink()
     */
    public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
    {
        $parts = explode('/', $action, 2);

        if (count($parts) > 1) {
            if ($parts[0] == 'languages') {
                if (empty($data['addon_id'])) {
                    $link = $outputPrefix . '/';
                } else {
                    $link = XenForo_Link::buildBasicLinkWithStringParam($outputPrefix, '', $extension, $data,
                        'addon_id');
                }
                $link = $link . XenForo_Link::buildBasicLinkWithIntegerParam('languages', $parts[1], $extension,
                    $extraParams, 'language_id', 'title');
                unset($extraParams['language_id'], $extraParams['title']);
                return $link;
            }
        }

        return parent::buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, $extraParams);
    }
}