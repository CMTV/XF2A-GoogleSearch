<?php

namespace GoogleSearch\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Search extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        if (!\XF::options()->GoogleSearch_id)
        {
            return $this->error(\XF::phrase('GoogleSearch_GSE_id_is_not_specified'));
        }

        $q = $this->filter('q', 'str');

        if ($q)
        {
            return $this->rerouteController(__CLASS__, 'results', $params);
        }

        $keywords = $this->filter('keywords', 'str');

        if ($keywords)
        {
            return $this->redirect($this->buildLink('google-search/results', null, ['q' => $keywords]));
        }

        $searcher = $this->app->search();

        $viewParams = [
            'tabs' => $searcher->getSearchTypeTabs(),
            'type' => 'google-search',
            'isRelevanceSupported' => $searcher->isRelevanceSupported(),
            'formTemplateName' => 'public:GoogleSearch_search_form',
        ];

        return $this->view('GoogleSearch:Search\Form', 'search_form', $viewParams);
    }

    public function actionResults(ParameterBag $params)
    {
        $viewParams = [
            'failedType' => $this->filter('failedType', 'str'),
            'explain' => $this->filter('explain', 'str'),
            'searchQuery' => $this->filter('q', 'str')
        ];

        return $this->view('GoogleSearch:Search\Results', 'GoogleSearch_search_results', $viewParams);
    }
}