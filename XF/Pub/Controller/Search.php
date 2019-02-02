<?php
/**
 * Google Search xF2 addon by CMTV
 * You can do whatever you want with this code
 * Enjoy!
 */

namespace CMTV\GoogleSearch\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Error;
use XF\Mvc\Reply\Message;
use XF\Mvc\Reply\View;
use CMTV\GoogleSearch\Constants as C;

class Search extends XFCP_Search
{
    public function actionIndex(ParameterBag $params)
    {
        $view = parent::actionIndex($params);

        if (!$view instanceof View)
        {
            return $view;
        }

        if ($view->getViewClass() !== 'XF:Search\Form')
        {
            return $view;
        }

        $viewParams = $view->getParams();

        if (array_key_exists('type', $viewParams) && array_key_exists('formTemplateName', $viewParams))
        {
            $isTypeEmpty = $viewParams['type'] == '';
            $isGoogleType = $this->filter('type', 'str') == 'google';

            if ($isTypeEmpty && $isGoogleType)
            {
                if (!\XF::options()->offsetGet(C::option('GSEID')))
                {
                    return $this->error(\XF::phrase(C::phrase('GSEID_not_specified')));
                }

                $viewParams['type'] = 'google';
                $viewParams['formTemplateName'] = 'public:' . C::template('search_form_google');
            }
        }

        $view->setParams($viewParams);

        return $view;
    }

    public function actionSearch()
    {
        if ($this->filter('google_search', 'bool'))
        {
            $query = $this->filter('query', 'str');
            $link = $this->buildLink('google-search', null, ['q' => $query]);

            return $this->redirect($link);
        }
        else
        {
            $reply = parent::actionSearch();

            $keywords = $this->filter('keywords', 'str');

            if (!$keywords)
            {
                return $reply;
            }

            if ($reply instanceof Message)
            {
                $hText = \XF::phrase(C::phrase('forum_search_problems'));
                $bText = $reply->getMessage();

                if ($bText == \XF::phrase('no_results_found'))
                {
                    $hText = $bText;
                    $bText = '';
                }

                return $this->tryAnyways($hText, $bText, $keywords) ?: $reply;
            }

            if ($reply instanceof Error)
            {
                $hText = \XF::phrase(C::option('forum_could_not_perform_search'));
                $bText = implode($reply->getErrors());

                return $this->tryAnyways($hText, $bText, $keywords) ?: $reply;
            }

            return $reply;
        }
    }

    protected function tryAnyways($hText, $bText, $query)
    {
        if (!\XF::options()->offsetGet(C::option('tryAnyways')))
        {
            return false;
        }

        return $this->redirect($this->buildLink('google-search', null, [
            'q' => $query,
            'tryAnyways' => true,
            'h' => $hText,
            'b' => $bText
        ]));
    }
}