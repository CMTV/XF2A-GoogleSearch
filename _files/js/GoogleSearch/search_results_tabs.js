!function($, window, document, _undefined)
{
    "use strict";

    XF.GoogleSearchAddon = XF.Element.newHandler({

        $forumSearch: null,
        $googleSearch: null,

        $forumResults: null,
        $googleResults: null,

        $pageNavWrapper: null,

        init: function()
        {
            this.$forumSearch = this.$target.find('#forum-search-results');
            this.$googleSearch = this.$target.find('#google-search-results');

            this.$forumResults = $('ol.block-body');
            this.$googleResults = $('.google-search-results');

            this.$pageNavWrapper = $('.pageNavWrapper');

            this.showForumResults();

            this.$forumSearch.click($.proxy(this.showForumResults, this));
            this.$googleSearch.click($.proxy(this.showGoogleResults, this));
        },

        showForumResults: function()
        {
            this.$googleSearch.removeClass('is-active');
            this.$googleResults.hide();

            this.$forumSearch.addClass('is-active');
            this.$forumResults.show();

            this.$pageNavWrapper.show();
        },

        showGoogleResults: function()
        {
            this.$googleSearch.addClass('is-active');
            this.$googleResults.show();

            this.$forumSearch.removeClass('is-active');
            this.$forumResults.hide();

            this.$pageNavWrapper.hide();
        }
    });

    XF.Element.register('results-tabs', 'XF.GoogleSearchAddon');

}(jQuery, window, document);