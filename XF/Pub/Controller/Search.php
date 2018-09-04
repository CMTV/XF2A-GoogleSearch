<?php

namespace GoogleSearch\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Search extends XFCP_Search
{
    protected $GoogleSearch_keywords = '';

    public function actionSearch()
    {
        $searchType = $this->filter('search_type', 'str');

        if ($searchType === 'google-search')
        {
            $searchQuery = trim($this->filter('search_query', 'str'));

            if (!$searchQuery)
            {
                return $this->error(\XF::phrase('GoogleSearch_specify_search_query'));
            }

            return $this->redirect($this->buildLink('google-search', null, ['q' => $searchQuery]));
        }

        $this->GoogleSearch_keywords = $this->filter('keywords', 'str');

        return parent::actionSearch();
    }

    public function error($error, $code = 200)
    {
        if (($searchQuery = $this->GoogleSearch_keywords) && $this->isTryAnywaysEnabled())
        {
            return $this->tryAnyways('error', implode($error), $searchQuery);
        }

        return parent::error($error, $code);
    }

    public function actionResults(ParameterBag $params)
    {
        $return = parent::actionResults($params);

        if (method_exists($return, 'getMessage'))
        {
            if ($return->getMessage() == $this->message(\XF::phrase('no_results_found'))->getMessage() && $this->isTryAnywaysEnabled())
            {
                return $this->tryAnyways('no_results', '', $params);
            }
        }

        return $return;
    }

    protected function runSearch(\XF\Search\Query\Query $query, array $constraints, $allowCached = true)
    {
        $return = parent::runSearch($query, $constraints, $allowCached);

        if ($return->getMessage() == $this->message(\XF::phrase('no_results_found'))->getMessage() && $this->isTryAnywaysEnabled())
        {
            return $this->tryAnyways('no_results', '', $query->getKeywords());
        }

        return $return;
    }

    protected function isTryAnywaysEnabled()
    {
        return \XF::options()->GoogleSearch_tryAnyways;
    }

    protected function tryAnyways($failedType, $anywaysExplain, $searchQuery)
    {
        return $this->redirect($this->buildLink('google-search', null, [
            'q' => $searchQuery,
            'failedType' => $failedType,
            'explain' => $anywaysExplain
        ]));
    }
}