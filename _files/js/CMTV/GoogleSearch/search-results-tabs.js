var CMTV_GoogleSearch = window.CMTV_GoogleSearch || {};

!function ($, window, document, _undefined)
{
    "use strict";

    CMTV_GoogleSearch.ResultsTabs = XF.Element.newHandler({

        $forumResultsTab: null,
        $googleResultsTab: null,

        $forumResults: null,
        $googleResults: null,

        $forumResultsPageNav: null,

        init: function ()
        {
            this.$forumResultsTab =     this.$target.find('.forum-results-tab');
            this.$googleResultsTab =    this.$target.find('.google-results-tab');

            this.$forumResults =    $('ol.block-body');
            this.$googleResults =   $('.google-results');

            this.$forumResultsPageNav = $('.pageNavWrapper');

            this.showForumResults();

            this.$forumResultsTab.click(XF.proxy(this, 'showForumResults'));
            this.$googleResultsTab.click(XF.proxy(this, 'showGoogleResults'));
        },

        showForumResults: function ()
        {
            this.$googleResultsTab.removeClass('is-active');
            this.$googleResults.hide();

            this.$forumResultsTab.addClass('is-active');
            this.$forumResults.show();

            this.$forumResultsPageNav.show();
        },

        showGoogleResults: function ()
        {
            this.$googleResultsTab.addClass('is-active');
            this.$googleResults.show();

            this.$forumResultsTab.removeClass('is-active');
            this.$forumResults.hide();

            this.$forumResultsPageNav.hide();
        }
    });

    XF.Element.register('results-tabs', 'CMTV_GoogleSearch.ResultsTabs');
}
(jQuery, window, document);