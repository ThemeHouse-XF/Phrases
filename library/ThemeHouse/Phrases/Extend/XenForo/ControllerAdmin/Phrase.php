<?php
if (false) {

    class XFCP_ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Phrase extends XenForo_ControllerAdmin_Phrase
    {
    }
}

class ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Phrase extends XFCP_ThemeHouse_Phrases_Extend_XenForo_ControllerAdmin_Phrase
{

    /**
     *
     * @see XenForo_ControllerAdmin_Phrase::_getPhraseAddEditResponse()
     */
    protected function _getPhraseAddEditResponse(array $phrase, $inputLanguageId, $inputPhraseId = 0)
    {
        $response = parent::_getPhraseAddEditResponse($phrase, $inputLanguageId, $inputPhraseId = 0);

        if ($response instanceof XenForo_ControllerResponse_View) {
            $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

            if (!empty($GLOBALS['XenForo_Route_PrefixAdmin_Phrases']) && !$addOnId) {
                $addOnId = XenForo_Helper_Cookie::getCookie('edit_addon_id');
            }

            if ($addOnId && empty($phrase['addon_id'])) {
                $template['addon_id'] = $addOnId;
                $response->params['addOnSelected'] = $addOnId;
            }

            $phrase = $response->params['phrase'];

            if (empty($phrase['title'])) {
                $title = $this->_input->filterSingle('title', XenForo_Input::STRING);
                $response->params['phrase']['title'] = $title;
            }

            if (empty($response->params['redirect'])) {
                $response->params['redirect'] = $this->getDynamicRedirect();
            }
        }

        return $response;
    }

    /**
     *
     * @see XenForo_ControllerAdmin_Phrase::actionSave()
     */
    public function actionSave()
    {
        $response = parent::actionSave();

        if ($response instanceof XenForo_ControllerResponse_Redirect) {
            $addOnId = $this->_input->filterSingle('addon_id', XenForo_Input::STRING);

            if ($addOnId) {
                XenForo_Helper_Cookie::setCookie('edit_addon_id', $addOnId);
            }

            $redirect = $this->_input->filterSingle('redirect', XenForo_Input::STRING);

            if ($redirect) {
                $response->redirectTarget = $redirect;
            }
        }

        return $response;
    }

    /**
     * Deletes a phrase.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionDelete()
    {
        $response = parent::actionDelete();

        if ($response instanceof XenForo_ControllerResponse_View) {
            if (empty($response->params['redirect'])) {
                $response->params['redirect'] = $this->getDynamicRedirect();
            }
        } elseif ($response instanceof XenForo_ControllerResponse_Redirect) {
            $redirect = $this->_input->filterSingle('redirect', XenForo_Input::STRING);

            if ($redirect) {
                $response->redirectTarget = $redirect;
            }
        }

        return $response;
    }

    /**
     * Missing phrase index.
     * This is a list of missing phrases, so redirect this to a
     * language-specific list.
     *
     * @return XenForo_ControllerResponse_Redirect
     */
    public function actionMissing()
    {
        $languageModel = $this->_getLanguageModel();

        $languageId = $languageModel->getLanguageIdFromCookie();

        $language = $languageModel->getLanguageById($languageId, true);
        if (!$language || !$this->_getPhraseModel()->canModifyPhraseInLanguage($languageId)) {
            $language = $languageModel->getLanguageById(XenForo_Application::get('options')->defaultLanguageId);
        }

        return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
            XenForo_Link::buildAdminLink('languages/missing-phrases', $language));
    }

    /**
     * Missing admin phrase index.
     * This is a list of missing admin phrases, so redirect this to a
     * language-specific list.
     *
     * @return XenForo_ControllerResponse_Redirect
     */
    public function actionMissingAdmin()
    {
        $languageModel = $this->_getLanguageModel();

        $languageId = $languageModel->getLanguageIdFromCookie();

        $language = $languageModel->getLanguageById($languageId, true);
        if (!$language || !$this->_getPhraseModel()->canModifyPhraseInLanguage($languageId)) {
            $language = $languageModel->getLanguageById(XenForo_Application::get('options')->defaultLanguageId);
        }

        return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
            XenForo_Link::buildAdminLink('languages/missing-admin-phrases', $language));
    }

    /**
     * Orpan phrase index.
     * This is a list of orphan phrases, so redirect this to a language-specific
     * list.
     *
     * @return XenForo_ControllerResponse_Redirect
     */
    public function actionOrphan()
    {
        $languageModel = $this->_getLanguageModel();

        $languageId = $languageModel->getLanguageIdFromCookie();

        $language = $languageModel->getLanguageById($languageId, true);
        if (!$language || !$this->_getPhraseModel()->canModifyPhraseInLanguage($languageId)) {
            $language = $languageModel->getLanguageById(XenForo_Application::get('options')->defaultLanguageId);
        }

        return $this->responseRedirect(XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
            XenForo_Link::buildAdminLink('languages/orphan-phrases', $language));
    }
}