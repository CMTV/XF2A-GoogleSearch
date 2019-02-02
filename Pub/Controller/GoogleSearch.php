<?php
/**
 * Google Search xF2 addon by CMTV
 * You can do whatever you want with this code
 * Enjoy!
 */

namespace CMTV\GoogleSearch\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use CMTV\GoogleSearch\Constants as C;

class GoogleSearch extends AbstractController
{
    public function actionIndex()
    {
        if (!\XF::options()->offsetGet(C::option('GSEID')))
        {
            return $this->error(\XF::phrase(C::phrase('GSEID_not_specified')));
        }

        $query = $this->filter('q', 'str');

        if (!$query && ($keywords = $this->filter('keywords', 'str')))
        {
            return $this->redirect($this->buildLink('google-search', null, ['q' => $keywords]));
        }

        $viewParams = [
            'query' => $query
        ];

        if ($tryAnyways = $this->filter('tryAnyways', 'bool'))
        {
            $viewParams['tryAnyways'] = $tryAnyways;
            $viewParams['header'] = $this->filter('h', 'str');
            $viewParams['body'] = $this->filter('b', 'str');
        }

        return $this->view(
            C::mvc('GoogleSearch\Results'),
            C::template('search_results_page'),
            $viewParams
        );
    }
}