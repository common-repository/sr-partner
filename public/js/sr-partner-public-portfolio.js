var pp_js = jQuery.noConflict();
(function(pp_js) {
    var $ = pp_js;
    var srPartnerPortfolio = {
        mainTemplateContainer: $('.sr-partner-portfolio'),
        mockupModalId: '#sr-partner-proposal-mockup-modal',
        defaultMockupTitleColor: '#FFF',
        defaultPrimaryButtonColor: '#0A89C7',
        defaultSecondaryButtonColor: '#FFF',
        defaultMockupPerRow: 4,
        defaultNumberOfItems: 7,
        defaultLimitNumber: 12,
        renderPortfolio : function() {
            // append
            if (srPartnerPortfolio.mainTemplateContainer.length > 0) {
                srPartnerPortfolio.mainTemplateContainer.each(function(key, el) {
                    var $el = $(el);

                    settings = {
                        mockupTitleColor: $el.data('titleColor') || srPartnerPortfolio.defaultMockupTitleColor,
                        primaryButtonColor: $el.data('buttonPrimary') || srPartnerPortfolio.defaultPrimaryButtonColor,
                        secondaryButtonColor: $el.data('buttonSecondary') || srPartnerPortfolio.defaultSecondaryButtonColor,
                        showMockupMenu: $el.data('showMockupMenu'),
                        mockupPerRow: parseInt($el.data('cardsPerRow')) || srPartnerPortfolio.defaultMockupPerRow,
                        allMockupCount: parseInt($el.data('categoryCount')) || 0,
                        companyName: $el.data('companyName') || ''
                    };

                    var template = srPartnerPortfolio.getPortfolioSkeletonBodyTemplate(settings, $el);

                    $el.html(template);
                    srPartnerPortfolio.initPortfolioEvents($el, settings);

                    if (settings.showMockupMenu) {
                        srPartnerPortfolio.getCategoriesData()
                            .done(function (categoriesResponse) {
                                var categories = srPartnerPortfolio.parseCategoriesData(categoriesResponse);
                                var categoriesTemplate = (settings.showMockupMenu) ? srPartnerPortfolio.getCategoriesTemplate(settings, categories, false) : '';

                                var catTemp = $(categoriesTemplate).find('.sr-partner-portfolio-categories-list').html();

                                $el.find('.sr-partner-portfolio-categories-list').html(catTemp);
                            });
                    }

                    srPartnerPortfolio.getMockupData({
                        'page': 1
                    })
                        .done(function (mockupResponse) {
                            var mockups         = srPartnerPortfolio.parseMockupData(mockupResponse);
                            var mockupsTemplate = srPartnerPortfolio.getMockupTemplate(settings, mockups, false, 0, mockupResponse.nextPage);
                            var mockupTemp      = $(mockupsTemplate).html();

                            $el.find('.sr-partner-portfolio-mockups').html(mockupTemp);
                        });

                });
            }
        },
        getPortfolioSkeletonBodyTemplate: function(settings, $el) {
            var content = '';
            var isSkeleton = true;
            var categories = (settings.showMockupMenu) ? srPartnerPortfolio.getCategoriesTemplate(settings, {}, isSkeleton) : '';
            var mockups = srPartnerPortfolio.getMockupTemplate(settings, [], isSkeleton, settings.allMockupCount);
            var $portfolioBodyTemplate = $('<div><h4 class="sr-partner-portfolio-logo-phrase">Web Design &amp; Development Portfolio</h4><div class="sr-partner-flex-row sr-partner-portfolio-main-container"></div></div>');

            content += categories;
            content += mockups;

            $portfolioBodyTemplate.find('.sr-partner-portfolio-main-container').html(content);

            return $portfolioBodyTemplate.html();
        },
        initPortfolioEvents: function($el, settings) {
            var $body = $('body');
            $el.on('click', '.sr-partner-portfolio-view-screenshot', function(e) {
                e.preventDefault();
                var $el = $(this);

                srPartnerPortfolio.modal('show', {
                    'downloadLink' : $el.data('downloadLink'),
                    'screenshot' : $el.data('screenshotUrl'),
                }, settings);
            });

            $el.on('click', '.sr-partner-portfolio-categories-list ul li a', function(e) {
                e.preventDefault();
                var $el = $(this);
                var numberItem = $el.data('count') || srPartnerPortfolio.defaultNumberOfItems;
                var $parentPortfolio = $el.parents('.sr-partner-portfolio');
                var primaryColor = (settings && settings.primaryButtonColor) ? settings.primaryButtonColor : srPartnerPortfolio.defaultPrimaryButtonColor;
                var $currentActive = $parentPortfolio.find('.sr-partner-portfolio-categories-list ul li a.active');

                // skeleton
                var getMockupItemsTemplate = srPartnerPortfolio.getMockupItemsTemplate(settings, {}, true, numberItem);
                $parentPortfolio.find('.sr-partner-portfolio-mockups-list').html(getMockupItemsTemplate);
                var categoryId = $el.data('categoryId') || 0;

                $currentActive.removeClass('active').removeAttr('style');
                $('.sr-partner-portfolio-load-more').addClass('hide');
                $el.addClass('active').css('border-left-color', primaryColor);
                srPartnerPortfolio.getMockupData({
                    'page': 1,
                    categoryId: categoryId
                })
                    .done(function (mockupResponse) {
                        $('.sr-partner-portfolio-load-more').removeClass('hide');
                        var mockups         = srPartnerPortfolio.parseMockupData(mockupResponse);
                        var mockupsTemplate = srPartnerPortfolio.getMockupTemplate(settings, mockups, false, 0, mockupResponse.nextPage, categoryId);
                        var mockupTemp      = $(mockupsTemplate).html();

                        $parentPortfolio.find('.sr-partner-portfolio-mockups').html(mockupTemp);
                    });
            });

            $body.on('click', '.sr-partner-download-mockup', function () {
                var $el = $(this);

                var win = window.open($el.data('downloadLink'), '_blank');
                win.focus();
            });

            $body.on('click', '.sr-partner-close-modal', function() {
                srPartnerPortfolio.modal('hide');
            });

            $body.on('click', '.sr-partner-portfolio-load-more', function (e) {
                e.preventDefault();
                var $el = $(this);
                var $parentPortfolio = $el.parents('.sr-partner-portfolio');
                var nextPage = $el.data('nextPage');
                var categoryId = ($el.data('categoryId') && typeof $el.data('categoryId' )!== 'undefined') ? $el.data('categoryId') : false;
                var param = {page: nextPage};

                if (categoryId) {
                    param['categoryId'] = categoryId;
                }

                $el.prop('disabled', true);
                srPartnerPortfolio.getMockupData(param).done(function (mockupResponse) {
                    var mockups         = srPartnerPortfolio.parseMockupData(mockupResponse);
                    var mockupsTemplateItem = srPartnerPortfolio.getMockupItemsTemplate(settings, mockups, false, 0);
                    // var mockupTemp      = $(mockupsTemplateItem).html();
                    var nextPage        = (mockupResponse && mockupResponse.nextPage) ? mockupResponse.nextPage : null;

                    $parentPortfolio.find('.sr-partner-portfolio-mockups-list').append(mockupsTemplateItem);
                    if (nextPage) {
                        $parentPortfolio.find('.sr-partner-portfolio-load-more').data('nextPage', nextPage);
                    } else {
                        $parentPortfolio.find('.sr-partner-portfolio-load-more').remove();
                    }

                }).always(function () {
                    $el.prop('disabled', false);
                });

            })
        },

        renderWatermark: function(settings) {
            var temp = '<div class="sr-partner-portfolio-watermark-wrapper" align="center">{{items}}</div>';
            var items = '';
            var companyName = (settings && settings.companyName) ? settings.companyName : '';

            for (var i = 0;i <= 40; i++) {
                items += srPartnerPortfolio.replaceMacro(
                    '<div class="sr-partner-portfolio-watermark-box" style="height:210px"><div class="sr-partner-portfolio-watermark" style="margin-top:45px">{{companyName}}</div></div>',
                    {
                        'companyName': companyName
                    }
                );
            }

            return srPartnerPortfolio.replaceMacro(temp, {
                items: items
            })
        },

        modal: function(stat, params, settings) {
            var $modal = $(srPartnerPortfolio.mockupModalId);
            var $body = $('body');

            if (stat == 'hide') {
                $body.removeClass('sr-partner-modal-open');
                $body.find(srPartnerPortfolio.mockupModalId).remove();
            } else if ($modal.length === 0) {
                var primaryColor = (settings && settings.primaryButtonColor) ? settings.primaryButtonColor : srPartnerPortfolio.defaultPrimaryButtonColor;
                var secondaryColor = (settings && settings.secondaryButtonColor) ? settings.secondaryButtonColor : srPartnerPortfolio.defaultSecondaryButtonColor;

                var primaryStyle = 'background-color: ' + primaryColor + ';color: ' + secondaryColor + ';border: 1px solid ' + primaryColor + ';';
                var secondaryStyle = 'background-color: ' + secondaryColor + ';color: ' + primaryColor + ';border: 1px solid ' + primaryColor + ';';
                var watermark = srPartnerPortfolio.renderWatermark(settings);

                var defaultTemp = '<div class="sr-partner-modal" id="sr-partner-proposal-mockup-modal">' +
                    '<div class="sr-partner-modal-upper">' +
                    '<button class="sr-partner-portfolio-primary-button sr-partner-pull-right sr-partner-download-mockup" target="_blank" data-download-link="{{downloadLink}}" style="{{primaryStyle}}">Download</button>' +
                    '<button class="sr-partner-portfolio-secondary-button sr-partner-pull-right sr-partner-m-r-10 sr-partner-close-modal" style="{{secondaryStyle}}">Close</button>' +
                    '</div>' +

                    '<div class="sr-partner-modal-dialog">' +
                    '<div class="sr-partner-modal-dialog-content">' + watermark +
                    '<img class="sr-partner-portfolio-screenshot" src="{{screenshot}}" oncontextmenu="return false;">' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                var template = srPartnerPortfolio.replaceMacro(defaultTemp, Object.assign(params, {
                    primaryStyle: primaryStyle,
                    secondaryStyle: secondaryStyle,
                    watermark: watermark
                }));

                $body.addClass('sr-partner-modal-open');
                $body.append(template);
            }
        },

        getMockupData: function(data) {
            return $.ajax({
                url: sr_partner_ajax_portfolio_object.ajax_portfolio_url, // this is the object instantiated in wp_localize_script function
                type: 'GET',
                dataType: 'json',
                data: Object.assign({
                    action: 'sr_partner_ajax',
                    m: 'getMockupData',
                    limit: srPartnerPortfolio.defaultLimitNumber
                }, data),
            });
        },

        parseMockupData: function(mockUpResponse) {
            var parsed = [];
            var mockups = (mockUpResponse && typeof mockUpResponse.mockups === 'object')?  mockUpResponse.mockups : [];

            if (typeof mockups === 'object' && mockups.length > 0) {
                $.each(mockups, function (key, val) {
                    var thumbUrl = val.mockupScreenshotThumbnail || '';
                    var screenshotUrl = val.mockupScreenshot || '';

                    parsed.push(Object.assign(val, {
                        thumbnail: thumbUrl,
                        screenshot: screenshotUrl
                    }));
                })
            }

            return parsed;
        },

        parseCategoriesData: function(categoriesResponse) {
            var parsed = [];
            if (typeof categoriesResponse === 'object' && categoriesResponse.length > 0) {
                $.each(categoriesResponse, function (key, val) {
                    parsed.push({
                        count: (val.id != '') ? val.mockupCount : srPartnerPortfolio.defaultLimitNumber,
                        categoryId: val.id,
                        categoryName: val.categoryName
                    });
                });
            }

            return parsed;
        },

        getCategoriesData: function(data) {
            return $.ajax({
                url: sr_partner_ajax_portfolio_object.ajax_portfolio_url, // this is the object instantiated in wp_localize_script function
                type: 'GET',
                dataType: 'json',
                data: Object.assign({
                    action: 'sr_partner_ajax',
                    m: 'mockupCategoryList'
                }, data),
            });
        },

        getMockupTemplate: function(settings,  mockups, isSkeleton, numberItem, nextPage, categoryId) {
            var items = srPartnerPortfolio.getMockupItemsTemplate(settings, mockups, isSkeleton, numberItem);
            var loadMore = (nextPage) ? srPartnerPortfolio.getLoadMoreTemplate(settings, nextPage, categoryId) : '';

            var temp = srPartnerPortfolio.replaceMacro('<div class="sr-partner-portfolio-mockups"><div class="sr-partner-portfolio-mockups-list sr-partner-flex-row">{{items}}</div>{{loadMore}}</div>', {
                items: items,
                loadMore: loadMore,
            });
            var $mockupTemplate = $(temp);
            var mockupContainerWidth = (settings.showMockupMenu)? '75%' : '100%';

            $mockupTemplate.css('width', mockupContainerWidth);

            return $mockupTemplate.wrap('<p/>').parent().html();
        },

        getMockupItemsTemplate: function(settings, mockups, isSkeleton, numberItem) {
            numberItem = numberItem || srPartnerPortfolio.defaultNumberOfItems;

            var itemTemp = '';
            var titleColor = (settings && settings.mockupTitleColor) ? settings.mockupTitleColor : srPartnerPortfolio.defaultMockupTitleColor;
            var primaryColor = (settings && settings.primaryButtonColor) ? settings.primaryButtonColor : srPartnerPortfolio.defaultPrimaryButtonColor;
            var secondaryColor = (settings && settings.secondaryButtonColor) ? settings.secondaryButtonColor : srPartnerPortfolio.defaultSecondaryButtonColor;
            var mockupPerRow = (settings && settings.mockupPerRow) ? parseInt(settings.mockupPerRow) : srPartnerPortfolio.defaultMockupPerRow;
            var itemWidth  = (100 / mockupPerRow);

            var mockupTitleStyle = 'color: ' + titleColor;
            var screenShotStyle = 'background-color: ' + primaryColor + ';color: ' + secondaryColor + ';border: 1px solid ' + primaryColor + ';';
            var liveDemoStyle = 'background-color: ' + secondaryColor + ';color: ' + primaryColor + ';border: 1px solid ' + secondaryColor + ';';
            var itemContainerStyle = 'width: ' + itemWidth + '%;';

            if (mockups.length > 0) {
                $.each(mockups, function(key, value) {
                    var isNew = (value.isNew) ?'<div class="sr-partner-ribbon"></div>' : '';
                    var itemDefault = '<div class="" style="{{itemContainerStyle}}">' +
                        '<div class="sr-partner-mockup-item" style="background-image: url({{thumbnail}});">{{isNew}}' +
                        '<div class="sr-partner-portfolio-mockup-card-overlay sr-partner-text-center">' +
                        '<div class="sr-partner-portfolio-mockup-title" style="{{mockupTitleStyle}}">{{mockupName}}</div>' +
                        '<div class="sr-partner-portfolio-btn-wrapper" align="center">' +
                        '<button style="{{screenShotStyle}}" data-screenshot-url="{{screenshot}}" data-mockup-name="{{mockupName}}" data-download-link="{{downloadLink}}"  data-mockup-id="{{mockupId}}" class="sr-partner-portfolio-view-screenshot sr-partner-portfolio-primary-button sr-partner-portfolio-block">Screenshot</button>' +
                        '<a style="{{liveDemoStyle}}" class="sr-partner-portfolio-live-demo sr-partner-portfolio-secondary-button" href="{{mockupUrl}}" target="_blank">Live Demo</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    itemTemp += srPartnerPortfolio.replaceMacro(itemDefault, Object.assign(value, {
                        'thumbnail': (settings.mockupPerRow == 2 || settings.mockupPerRow == 3) ? value.screenshot: value.thumbnail,
                        'itemContainerStyle' : itemContainerStyle,
                        'isNew' : isNew,
                        'mockupTitleStyle' : mockupTitleStyle,
                        'screenShotStyle' : screenShotStyle,
                        'liveDemoStyle' : liveDemoStyle,
                    }));
                });
            } else if (isSkeleton) {
                for (var loop = 1; loop <= numberItem; loop++) {
                    itemTemp += srPartnerPortfolio.replaceMacro(srPartnerPortfolio.getMockupItemSkeleton(settings), {
                        'itemContainerStyle' : itemContainerStyle
                    });
                }
            }

            return itemTemp;
        },

        getMockupItemSkeleton: function(settings) {
            return '<div style="{{itemContainerStyle}}"><div class="sr-partner-mockup-item" ><div class="sr-partner-skeleton-loader"></div></div></div>';
        },

        getCategoriesSkeleton: function(settings) {
            return '<div class="sr-partner-portfolio-categories-list"><ul><li class="sr-partner-skeleton-loader" style="height: 500px;"><a href="javascript:void(0)">&nbsp;</a></li></ul></div>';
        },

        replaceMacro: function(template, macro) {

            if (Object.keys(macro).length > 0) {
                $.each(macro, function(key, value) {
                    template = template.replace(new RegExp('{{' + key + '}}', 'g'), value);
                });
            }

            return template;
        },

        getCategoriesTemplate: function(settings, categories, isSkeleton) {
            var $categoriesTemplate = $('<div class="sr-partner-portfolio-categories"></div>');
            var categoryList = '';

            if (categories.length > 0) {
                categoryList = '<ul>';
                categories.forEach(function(category, key) {
                    categoryList += srPartnerPortfolio.replaceMacro(
                        '<li><a href="javascript:void(0)" data-category-id="{{categoryId}}" data-count={{count}}>{{categoryName}}</a></li>',
                        category
                    );
                });
                categoryList += '</ul>';
            }

            var categoryListContainer = (isSkeleton)
                ? srPartnerPortfolio.getCategoriesSkeleton(settings)
                : '<div class="sr-partner-portfolio-categories-list">' + categoryList + '<div>';

            // append categories
            $categoriesTemplate.css('width', '25%');
            $categoriesTemplate.html(categoryListContainer);

            return $categoriesTemplate.wrap('<p/>').parent().html();
        },

        getLoadMoreTemplate: function (settings, nextPage, categoryId) {
            var primaryColor = (settings && settings.primaryButtonColor) ? settings.primaryButtonColor : srPartnerPortfolio.defaultPrimaryButtonColor;
            var secondaryColor = (settings && settings.secondaryButtonColor) ? settings.secondaryButtonColor : srPartnerPortfolio.defaultSecondaryButtonColor;
            var style = 'background-color: ' + secondaryColor + ';color: ' + primaryColor + ';border: 1px solid ' + secondaryColor + ';';

            categoryId = (categoryId) ? categoryId: '';

            return srPartnerPortfolio.replaceMacro('<div class="sr-partner-portfolio-load-more-container"><button class="sr-partner-portfolio-live-demo sr-partner-portfolio-secondary-button sr-partner-portfolio-load-more hide" data-next-page="{{nextPage}}" data-category-id="{{categoryId}}" style="{{style}}">Load more</button></div>', {
                'style' : style,
                'nextPage': nextPage,
                'categoryId' : categoryId
            });
        }
    };

    srPartnerPortfolio.renderPortfolio();

})(pp_js);