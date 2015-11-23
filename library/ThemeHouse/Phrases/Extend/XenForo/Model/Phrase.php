<?php
if (false) {

    class XFCP_ThemeHouse_Phrases_Extend_XenForo_Model_Phrase extends XenForo_Model_Phrase
    {
    }
}

class ThemeHouse_Phrases_Extend_XenForo_Model_Phrase extends XFCP_ThemeHouse_Phrases_Extend_XenForo_Model_Phrase
{

    /**
     *
     * @see XenForo_Model_Phrase::preparePhraseConditions()
     */
    public function preparePhraseConditions(array $conditions, array &$fetchOptions)
    {
        $whereClause = parent::preparePhraseConditions($conditions, $fetchOptions);

        $db = $this->_getDb();
        $sqlConditions = array();

        if (!empty($conditions['addon_id'])) {
            if (is_array($conditions['addon_id'])) {
                $sqlConditions[] = 'phrase.addon_id IN (' . $db->quote($conditions['addon_id']) . ')';
            } else {
                $sqlConditions[] = 'phrase.addon_id = ' . $db->quote($conditions['addon_id']);
            }
        }

        if (empty($sqlConditions)) {
            return $whereClause;
        }

        if ($whereClause != '1==1') {
            array_unshift($sqlConditions, $whereClause);
        }

        return $this->getConditionsForClause($sqlConditions);
    }

    public function getMissingPhraseListForLanguage($languageId, $addOnId = '', array $fetchOptions = array())
    {
        $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

        return $this->_getDb()->fetchAll(
            $this->limitQueryResults(
                '
                SELECT template_phrase.phrase_title AS title,
                    template.title AS template_title,
                    template.template_id AS template_id,
                    template.addon_id AS addon_id,
                    template.style_id AS style_id
                FROM xf_template_phrase AS template_phrase
                LEFT JOIN xf_phrase AS phrase ON
                    (template_phrase.phrase_title = phrase.title AND phrase.language_id = ?)
                LEFT JOIN xf_template_map AS template_map ON
                    (template_phrase.template_map_id = template_map.template_map_id)
                LEFT JOIN xf_template AS template ON
                    (template_map.template_id = template.template_id)
                WHERE phrase.title IS NULL
                    ' . ($addOnId ? ' AND template.addon_id = ' . $this->_getDb()
                    ->quote($addOnId) : '') . '
				ORDER BY CONVERT(template_phrase.phrase_title USING utf8)
			', $limitOptions['limit'], $limitOptions['offset']), $languageId);
    }

    public function countMissingPhrasesInLanguage($languageId)
    {
        return $this->_getDb()->fetchOne(
            '
			SELECT COUNT(*)
            FROM xf_template_phrase AS template_phrase
            LEFT JOIN xf_phrase AS phrase ON
                (template_phrase.phrase_title = phrase.title AND phrase.language_id = ?)
            WHERE phrase.title IS NULL
		', $languageId);
    }

    public function getMissingAdminPhraseListForLanguage($languageId, $addOnId = '', array $fetchOptions = array())
    {
        $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

        return $this->_getDb()->fetchAll(
            $this->limitQueryResults(
                '
                SELECT admin_template_phrase.phrase_title AS title,
                    admin_template.title AS template_title,
                    admin_template.template_id AS template_id,
                    admin_template.addon_id AS addon_id
                FROM xf_admin_template_phrase AS admin_template_phrase
                LEFT JOIN xf_phrase AS phrase ON
                    (admin_template_phrase.phrase_title = phrase.title AND phrase.language_id = ?)
                LEFT JOIN xf_admin_template AS admin_template ON
                    (admin_template_phrase.template_id = admin_template.template_id)
                WHERE phrase.title IS NULL
                    ' . ($addOnId ? ' AND admin_template.addon_id = ' . $this->_getDb()
                    ->quote($addOnId) : '') . '
				ORDER BY CONVERT(admin_template_phrase.phrase_title USING utf8)
			', $limitOptions['limit'], $limitOptions['offset']), $languageId);
    }

    public function countMissingAdminPhrasesInLanguage($languageId)
    {
        return $this->_getDb()->fetchOne(
            '
			SELECT COUNT(*)
            FROM xf_admin_template_phrase AS admin_template_phrase
            LEFT JOIN xf_phrase AS phrase ON
                (admin_template_phrase.phrase_title = phrase.title AND phrase.language_id = ?)
            WHERE phrase.title IS NULL
		', $languageId);
    }

    public function getOrphanPhraseListForLanguage($languageId, array $conditions = array(), array $fetchOptions = array())
    {
        $whereClause = $this->preparePhraseConditions($conditions, $fetchOptions);
        $limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

        return $this->_getDb()->fetchAll(
            $this->limitQueryResults(
                '
				SELECT phrase.phrase_id,
					phrase.title,
					IF(phrase.language_id = 0, \'default\', \'custom\') AS phrase_state,
					1 AS canDelete,
					addon.addon_id, addon.title AS addonTitle
                FROM xf_phrase AS phrase
                LEFT JOIN xf_template_phrase AS template_phrase ON
                    (template_phrase.phrase_title = phrase.title)
                LEFT JOIN xf_admin_template_phrase AS admin_template_phrase ON
                    (admin_template_phrase.phrase_title = phrase.title)
                LEFT JOIN xf_addon AS addon ON
					(addon.addon_id = phrase.addon_id)
                WHERE template_phrase.phrase_title IS NULL
                    AND admin_template_phrase.phrase_title IS NULL
                    AND phrase.language_id = ?
					AND ' . $whereClause . '
                ORDER BY CONVERT(phrase.title USING utf8)
			', $limitOptions['limit'], $limitOptions['offset']), $languageId);
    }

    public function countOrphanPhrasesInLanguage($languageId, array $conditions = array())
    {
        $fetchOptions = array();
        $whereClause = $this->preparePhraseConditions($conditions, $fetchOptions);

        return $this->_getDb()->fetchOne(
            '
			SELECT COUNT(*)
            FROM xf_phrase AS phrase
            LEFT JOIN xf_template_phrase AS template_phrase ON
                (template_phrase.phrase_title = phrase.title)
            LEFT JOIN xf_admin_template_phrase AS admin_template_phrase ON
                (admin_template_phrase.phrase_title = phrase.title)
            WHERE template_phrase.phrase_title IS NULL
                AND admin_template_phrase.phrase_title IS NULL
                AND phrase.language_id = ?
				AND ' . $whereClause . '
		', $languageId);
    }
}