<?php

class ThemeHouse_Phrases_Listener_FileHealthCheck
{

    public static function fileHealthCheck(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes = array_merge($hashes,
            array(
                'library/ThemeHouse/Phrases/Extend/XenForo/ControllerAdmin/Language.php' => 'fd7a7bf32363b3454d407bb8f9c9090c',
                'library/ThemeHouse/Phrases/Extend/XenForo/ControllerAdmin/Phrase.php' => '750af9ea1badbb9fad661d29c15a9fba',
                'library/ThemeHouse/Phrases/Extend/XenForo/Model/Phrase.php' => 'c542231a718ef08cf99c743a11dca69a',
                'library/ThemeHouse/Phrases/Extend/XenForo/Route/PrefixAdmin/AddOns.php' => '5a28e3e4f9b4eaad863e3c0d6750b7ed',
                'library/ThemeHouse/Phrases/Extend/XenForo/Route/PrefixAdmin/Languages.php' => '3c3e19177ab1250bdc71ba8c19201c40',
                'library/ThemeHouse/Phrases/Install/Controller.php' => '3f506a00beff19896cef6ee9225d4545',
                'library/ThemeHouse/Phrases/Listener/LoadClass.php' => '40bdece4909634cbecaa1ab54abef27e',
                'library/ThemeHouse/Install.php' => '18f1441e00e3742460174ab197bec0b7',
                'library/ThemeHouse/Install/20151109.php' => '2e3f16d685652ea2fa82ba11b69204f4',
                'library/ThemeHouse/Deferred.php' => 'ebab3e432fe2f42520de0e36f7f45d88',
                'library/ThemeHouse/Deferred/20150106.php' => 'a311d9aa6f9a0412eeba878417ba7ede',
                'library/ThemeHouse/Listener/ControllerPreDispatch.php' => 'fdebb2d5347398d3974a6f27eb11a3cd',
                'library/ThemeHouse/Listener/ControllerPreDispatch/20150911.php' => 'f2aadc0bd188ad127e363f417b4d23a9',
                'library/ThemeHouse/Listener/InitDependencies.php' => '8f59aaa8ffe56231c4aa47cf2c65f2b0',
                'library/ThemeHouse/Listener/InitDependencies/20150212.php' => 'f04c9dc8fa289895c06c1bcba5d27293',
                'library/ThemeHouse/Listener/LoadClass.php' => '5cad77e1862641ddc2dd693b1aa68a50',
                'library/ThemeHouse/Listener/LoadClass/20150518.php' => 'f4d0d30ba5e5dc51cda07141c39939e3',
            ));
    }
}