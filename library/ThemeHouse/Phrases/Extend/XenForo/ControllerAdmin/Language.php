<?php
if (false) {

    class XFCP_ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Language extends XenForo_ControllerAdmin_Language
    {
    }
}

class ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Language extends XFCP_ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Language
{

    /**
     *
     * @see XenForo_ControllerAdmin_Language::actionPhrases()
     */
    public function actionPhrases()
    {
        $languageId = $this->_input->filterSingle('language_id', XenForo_Input::UINT);
        $language = $this->_getLanguageOrError($languageId, true);

        $languageModel = $this->_getLanguageModel();
        $phraseModel = $this->_getPhraseModel();

        if (!$phraseModel->canModifyPhraseInLanguage($languageId)) {
            return $this->responseError(new XenForo_Phrase('phrases_in_this_language_can_not_be_modified'));
        }

        $addOns = $this->_getAddOnModel()->getAllAddOns();

        $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

        if (!empty($GLOBALS['XenForo_Route_PrefixAdmin_Languages']) && !$addOnId) {
            $addOnId = XenForo_Helper_Cookie::getCookie('edit_addon_id');
        } else {
            XenForo_Helper_Cookie::setCookie('edit_addon_id', $addOnId);
        }

        if ($addOnId) {
            $addOn = $addOns[$addOnId];
        } else {
            $addOn = array(
                'addon_id' => ''
            );
        }

        $this->canonicalizeRequestUrl(XenForo_Link::buildAdminLink('add-ons/languages/phrases', $addOn, $language));

        if (!$addOnId) {
            $response = parent::actionPhrases();

            $response->params['addOns'] = $addOns;

            return $response;
        }

        // set an edit_language_id cookie so we can switch to another area and
        // maintain the current style selection
        XenForo_Helper_Cookie::setCookie('edit_language_id', $languageId);

        $page = $this->_input->filterSingle('page', XenForo_Input::UINT);
        $perPage = 100;

        $conditions = array(
            'addon_id' => $addOnId
        );

        $filter = $this->_input->filterSingle('_filter', XenForo_Input::ARRAY_SIMPLE);
        if ($filter && isset($filter['value'])) {
            $conditions['title'] = array(
                $filter['value'],
                empty($filter['prefix']) ? 'lr' : 'r'
            );
            $filterView = true;
        } else {
            $filterView = false;
        }

        $fetchOptions = array(
            'page' => $page,
            'perPage' => $perPage
        );

        $totalPhrases = $phraseModel->countEffectivePhrasesInLanguage($languageId, $conditions, $fetchOptions);

        $viewParams = array(
            'addOns' => $addOns,
            'addOnSelected' => $addOnId,

            'phrases' => $phraseModel->getEffectivePhraseListForLanguage($languageId, $conditions, $fetchOptions),
            'languages' => $languageModel->getAllLanguagesAsFlattenedTree($languageModel->showMasterLanguage() ? 1 : 0),
            'masterLanguage' => $languageModel->showMasterLanguage() ? $languageModel->getLanguageById(0, true) : array(),
            'language' => $languageModel->getLanguageById($languageId, true),

            'page' => $page,
            'perPage' => $perPage,
            'totalPhrases' => $totalPhrases,

            'filterView' => $filterView,
            'filterMore' => ($filterView && $totalPhrases > $perPage)
        );

        return $this->responseView('XenForo_ViewAdmin_Phrase_List', 'phrase_list', $viewParams);
    }

    /**
     * Displays the list of missing phrases in the specified language.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionMissingPhrases()
    {
        $languageId = $this->_input->filterSingle('language_id', XenForo_Input::UINT);
        $language = $this->_getLanguageOrError($languageId, true);

        $languageModel = $this->_getLanguageModel();
        $phraseModel = $this->_getPhraseModel();

        if (!$phraseModel->canModifyPhraseInLanguage($languageId)) {
            return $this->responseError(new XenForo_Phrase('phrases_in_this_language_can_not_be_modified'));
        }

        $addOns = $this->_getAddOnModel()->getAllAddOns();

        $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

        if (!empty($GLOBALS['XenForo_Route_PrefixAdmin_Languages']) && !$addOnId) {
            $addOnId = XenForo_Helper_Cookie::getCookie('edit_addon_id');
        } else {
            XenForo_Helper_Cookie::setCookie('edit_addon_id', $addOnId);
        }

        if ($addOnId) {
            $addOn = $addOns[$addOnId];
        } else {
            $addOn = array(
                'addon_id' => ''
            );
        }

        $this->canonicalizeRequestUrl(
            XenForo_Link::buildAdminLink('add-ons/languages/missing-phrases', $addOn, $language));

        // set an edit_language_id cookie so we can switch to another area and
        // maintain the current style selection
        XenForo_Helper_Cookie::setCookie('edit_language_id', $languageId);

        $page = $this->_input->filterSingle('page', XenForo_Input::UINT);
        $perPage = 100;

        $filter = $this->_input->filterSingle('_filter', XenForo_Input::ARRAY_SIMPLE);
        if ($filter && isset($filter['value'])) {
            $conditions['title'] = array(
                $filter['value'],
                empty($filter['prefix']) ? 'lr' : 'r'
            );
            $filterView = true;
        } else {
            $filterView = false;
        }

        $fetchOptions = array(
            'page' => $page,
            'perPage' => $perPage
        );

        $totalPhrases = $phraseModel->countMissingPhrasesInLanguage($languageId, $fetchOptions);

        $viewParams = array(
            'addOns' => $addOns,
            'addOnSelected' => $addOnId,

            'phrases' => $phraseModel->getMissingPhraseListForLanguage($languageId, $addOnId, $fetchOptions),
            'languages' => $languageModel->getAllLanguagesAsFlattenedTree($languageModel->showMasterLanguage() ? 1 : 0),
            'masterLanguage' => $languageModel->showMasterLanguage() ? $languageModel->getLanguageById(0, true) : array(),
            'language' => $languageModel->getLanguageById($languageId, true),

            'page' => $page,
            'perPage' => $perPage,
            'totalPhrases' => $totalPhrases,

            'filterView' => $filterView,
            'filterMore' => ($filterView && $totalPhrases > $perPage)
        );

        return $this->responseView('ThemeHouse_Phrases_ViewAdmin_Phrase_MissingList',
            'th_missing_phrase_list_phrases', $viewParams);
    }

    /**
     * Displays the list of missing admin phrases in the specified language.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionMissingAdminPhrases()
    {
        $languageId = $this->_input->filterSingle('language_id', XenForo_Input::UINT);
        $language = $this->_getLanguageOrError($languageId, true);

        $languageModel = $this->_getLanguageModel();
        $phraseModel = $this->_getPhraseModel();

        if (!$phraseModel->canModifyPhraseInLanguage($languageId)) {
            return $this->responseError(new XenForo_Phrase('phrases_in_this_language_can_not_be_modified'));
        }

        $addOns = $this->_getAddOnModel()->getAllAddOns();

        $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

        if (!empty($GLOBALS['XenForo_Route_PrefixAdmin_Languages']) && !$addOnId) {
            $addOnId = XenForo_Helper_Cookie::getCookie('edit_addon_id');
        } else {
            XenForo_Helper_Cookie::setCookie('edit_addon_id', $addOnId);
        }

        if ($addOnId) {
            $addOn = $addOns[$addOnId];
        } else {
            $addOn = array(
                'addon_id' => 0
            );
        }

        $this->canonicalizeRequestUrl(
            XenForo_Link::buildAdminLink('add-ons/languages/missing-admin-phrases', $addOn, $language));

        // set an edit_language_id cookie so we can switch to another area and
        // maintain the current style selection
        XenForo_Helper_Cookie::setCookie('edit_language_id', $languageId);

        $page = $this->_input->filterSingle('page', XenForo_Input::UINT);
        $perPage = 100;

        $filter = $this->_input->filterSingle('_filter', XenForo_Input::ARRAY_SIMPLE);
        if ($filter && isset($filter['value'])) {
            $conditions['title'] = array(
                $filter['value'],
                empty($filter['prefix']) ? 'lr' : 'r'
            );
            $filterView = true;
        } else {
            $filterView = false;
        }

        $fetchOptions = array(
            'page' => $page,
            'perPage' => $perPage
        );

        $totalPhrases = $phraseModel->countMissingAdminPhrasesInLanguage($languageId, $fetchOptions);

        $viewParams = array(
            'addOns' => $addOns,
            'addOnSelected' => $addOnId,

            'phrases' => $phraseModel->getMissingAdminPhraseListForLanguage($languageId, $addOnId, $fetchOptions),
            'languages' => $languageModel->getAllLanguagesAsFlattenedTree($languageModel->showMasterLanguage() ? 1 : 0),
            'masterLanguage' => $languageModel->showMasterLanguage() ? $languageModel->getLanguageById(0, true) : array(),
            'language' => $languageModel->getLanguageById($languageId, true),

            'page' => $page,
            'perPage' => $perPage,
            'totalPhrases' => $totalPhrases,

            'filterView' => $filterView,
            'filterMore' => ($filterView && $totalPhrases > $perPage)
        );

        return $this->responseView('ThemeHouse_Phrases_ViewAdmin_Phrase_MissingAdminList',
            'th_missing_admin_phrase_list_phrases', $viewParams);
    }

    /**
     * Displays the list of orphan phrases in the specified language.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionOrphanPhrases()
    {
        $languageId = $this->_input->filterSingle('language_id', XenForo_Input::UINT);
        $language = $this->_getLanguageOrError($languageId, true);

        $languageModel = $this->_getLanguageModel();
        $phraseModel = $this->_getPhraseModel();

        if (!$phraseModel->canModifyPhraseInLanguage($languageId)) {
            return $this->responseError(new XenForo_Phrase('phrases_in_this_language_can_not_be_modified'));
        }

        $addOns = $this->_getAddOnModel()->getAllAddOns();

        $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

        if (!empty($GLOBALS['XenForo_Route_PrefixAdmin_Languages']) && !$addOnId) {
            $addOnId = XenForo_Helper_Cookie::getCookie('edit_addon_id');
        } else {
            XenForo_Helper_Cookie::setCookie('edit_addon_id', $addOnId);
        }

        if ($addOnId) {
            $addOn = $addOns[$addOnId];
        } else {
            $addOn = array(
                'addon_id' => ''
            );
        }

        $this->canonicalizeRequestUrl(XenForo_Link::buildAdminLink('add-ons/languages/orphan-phrases', $addOn, $language));

        // set an edit_language_id cookie so we can switch to another area and
        // maintain the current style selection
        XenForo_Helper_Cookie::setCookie('edit_language_id', $languageId);

        $page = $this->_input->filterSingle('page', XenForo_Input::UINT);
        $perPage = 100;

        $conditions = array(
            'addon_id' => $addOnId
        );

        $filter = $this->_input->filterSingle('_filter', XenForo_Input::ARRAY_SIMPLE);
        if ($filter && isset($filter['value'])) {
            $conditions['title'] = array(
                $filter['value'],
                empty($filter['prefix']) ? 'lr' : 'r'
            );
            $filterView = true;
        } else {
            $filterView = false;
        }

        $fetchOptions = array(
            'page' => $page,
            'perPage' => $perPage
        );

        $totalPhrases = $phraseModel->countOrphanPhrasesInLanguage($languageId, $conditions, $fetchOptions);

        $viewParams = array(
            'addOns' => $addOns,
            'addOnSelected' => $addOnId,

            'phrases' => $phraseModel->getOrphanPhraseListForLanguage($languageId, $conditions, $fetchOptions),
            'languages' => $languageModel->getAllLanguagesAsFlattenedTree($languageModel->showMasterLanguage() ? 1 : 0),
            'masterLanguage' => $languageModel->showMasterLanguage() ? $languageModel->getLanguageById(0, true) : array(),
            'language' => $languageModel->getLanguageById($languageId, true),

            'page' => $page,
            'perPage' => $perPage,
            'totalPhrases' => $totalPhrases,

            'filterView' => $filterView,
            'filterMore' => ($filterView && $totalPhrases > $perPage)
        );

        return $this->responseView('ThemeHouse_Phrases_ViewAdmin_Phrase_OrphanList', 'th_orphan_phrase_list_phrases', $viewParams);
    }

    /**
     * Get the add-on model.
     *
     * @return XenForo_Model_AddOn
     */
    protected function _getAddOnModel()
    {
        return $this->getModelFromCache('XenForo_Model_AddOn');
    }
}