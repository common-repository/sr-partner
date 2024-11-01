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
        renderPortfolio : function() {
            srPartnerPortfolio.mainTemplateContainer = $('.sr-partner-portfolio');
            // append

            if (srPartnerPortfolio.mainTemplateContainer.length > 0) {
                srPartnerPortfolio.mainTemplateContainer.each(function(key, el) {
                    var $el = $(el);

                    settings = {
                        mockupTitleColor: $el.data('titleColor') || srPartnerPortfolio.defaultMockupTitleColor,
                        primaryButtonColor: $el.data('buttonPrimary') || srPartnerPortfolio.defaultPrimaryButtonColor,
                        secondaryButtonColor: $el.data('buttonSecondary') || srPartnerPortfolio.defaultSecondaryButtonColor,
                        showMockupMenu: $el.data('showMockupMenu'),
                        mockupPerRow: parseInt($el.data('cardsPerRow')) || srPartnerPortfolio.defaultMockupPerRow
                    };

                    var template = srPartnerPortfolio.getPortfolioSkeletonBodyTemplate(settings, $el);

                    $el.html(template);
                    srPartnerPortfolio.initPortfolioEvents($el, settings);

                    setTimeout(function() {
                        var mockups = srPartnerPortfolio.getMockupData();
                        var categories = srPartnerPortfolio.getCategoriesData();

                        var mockupsTemplate = srPartnerPortfolio.getMockupTemplate(settings, mockups, false, 0);
                        var categoriesTemplate = (settings.showMockupMenu) ? srPartnerPortfolio.getCategoriesTemplate(settings, categories, false): '';

                        var content = '';
                        content += categoriesTemplate;
                        content += mockupsTemplate;
                        $el.find('.sr-partner-portfolio-main-container').html(content);
                    }, 100);
                });
            }
        },
        getPortfolioSkeletonBodyTemplate: function(settings, $el) {
            var content = '';
            var isSkeleton = true;
            var categories = (settings.showMockupMenu) ? srPartnerPortfolio.getCategoriesTemplate(settings, {}, isSkeleton) : '';
            var mockups = srPartnerPortfolio.getMockupTemplate(settings, [], isSkeleton, srPartnerPortfolio.defaultNumberOfItems);
            var $portfolioBodyTemplate = $('<div><h4 class="sr-partner-portfolio-logo-phrase">Web Design &amp; Development Portfolio</h4><div class="sr-partner-flex-row sr-partner-portfolio-main-container"></div></div>');

            content += categories;
            content += mockups;

            $portfolioBodyTemplate.find('.sr-partner-portfolio-main-container').html(content);

            return $portfolioBodyTemplate.html();
        },

        initPortfolioEvents: function($el, settings) {
            $el.on('click', '.sr-partner-portfolio-view-screenshot', function(e) {
                e.preventDefault();
            });

            $el.on('click', '.sr-partner-portfolio-categories-list ul li a', function(e) {
                e.preventDefault();
            });

            $('body').on('click', '.sr-partner-close-modal', function() {
                srPartnerPortfolio.modal('hide');
            });
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

                var defaultTemp = '<div class="sr-partner-modal" id="sr-partner-proposal-mockup-modal">' +
                    '<div class="sr-partner-modal-upper">' +
                    '<button class="sr-partner-portfolio-primary-button sr-partner-pull-right" target="_blank" data-download-link="{{downloadLink}}" style="{{primaryStyle}}">Download</button>' +
                    '<button class="sr-partner-portfolio-secondary-button sr-partner-pull-right sr-partner-m-r-10 sr-partner-close-modal" style="{{secondaryStyle}}">Close</button>' +
                    '</div>' +

                    '<div class="sr-partner-modal-dialog">' +
                    '<div class="sr-partner-modal-dialog-content">' +
                    '<img class="sr-partner-portfolio-screenshot" src="{{screenshot}}" oncontextmenu="return false;">' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                var template = srPartnerPortfolio.replaceMacro(defaultTemp, Object.assign(params, {
                    primaryStyle: primaryStyle,
                    secondaryStyle: secondaryStyle
                }));


                $body.addClass('sr-partner-modal-open');
                $body.append(template);
            }
        },

        getMockupData: function() {
            return [
                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/68/579048/Architect-thumb.jpg',
                    screenshot: 'https://d1nhcjbgm7sxtv.cloudfront.net/68/579048/Architect.jpg',
                    mockupName: 'Architect',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                },
                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/64/579038/Ashikaga-thumb.jpg',
                    screenshot: 'https://d1nhcjbgm7sxtv.cloudfront.net/68/579048/Ashikaga.jpg',
                    mockupName: 'Ashikaga',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                },
                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/65/579045/Calgary-thumb.jpg',
                    screenshot: 'https://d1nhcjbgm7sxtv.cloudfront.net/65/579045/Calgary.jpg',
                    mockupName: 'Calgary',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                },

                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/69/579051/Ebony-thumb.jpg',
                    screenshot:'https://d1nhcjbgm7sxtv.cloudfront.net/69/579051/Ebony.jpg',
                    mockupName: 'Ebony',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                },
                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/67/579047/Escapade-thumb.jpg',
                    screenshot: 'https://d1nhcjbgm7sxtv.cloudfront.net/67/579047/Escapade.jpg',
                    mockupName: 'Escapade',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                },
                {
                    thumbnail: 'https://d1nhcjbgm7sxtv.cloudfront.net/61/512075/Iceland-thumb.jpg',
                    screenshot: 'https://d1nhcjbgm7sxtv.cloudfront.net/61/512075/Iceland.jpg',
                    mockupName: 'Iceland',
                    mockupId: false,
                    mockupUrl: '',
                    isNew: true
                }
            ];
        },

        getCategoriesData: function() {
            return [
                {count:5, categoryId: 1, categoryName: 'All Mockups'},
                {count:5, categoryId: 1, categoryName: 'Art, Design &amp; Media'},
                {count:5, categoryId: 1, categoryName: 'Automotive &amp; Local Transportation'},
                {count:5, categoryId: 1, categoryName: 'Business Services &amp; Finance'},
                {count:5, categoryId: 1, categoryName: 'Construction &amp; Home Services'},
                {count:5, categoryId: 1, categoryName: 'Fashion &amp; Lifestyle'},
                {count:5, categoryId: 1, categoryName: 'Government, Non Profit &amp; Community'},
                {count:5, categoryId: 1, categoryName: 'Medicine, Health &amp; Wellness'},
                {count:5, categoryId: 1, categoryName: 'Outdoors, Plants &amp; Pets'},
                {count:5, categoryId: 1, categoryName: 'Real Estate'},
                {count:5, categoryId: 1, categoryName: 'Travel &amp; Hospitality'}
            ];
        },

        getMockupTemplate: function(settings,  mockups, isSkeleton, numberItem) {
            var $mockupTemplate      = $('<div class="sr-partner-portfolio-mockups"><div class="sr-partner-portfolio-mockups-list sr-partner-flex-row"></div></div>');
            var mockupContainerWidth = (settings.showMockupMenu)? '75%' : '100%';
            var items                = srPartnerPortfolio.getMockupItemsTemplate(settings, mockups, isSkeleton, numberItem);

            $mockupTemplate.css('width', mockupContainerWidth);
            $mockupTemplate.find('.sr-partner-portfolio-mockups-list').html(items);

            return $mockupTemplate.wrap('<p/>').parent().html();
        },

        getMockupItemsTemplate: function(settings, mockups, isSkeleton, numberItem) {
            numberItem = numberItem || srPartnerPortfolio.defaultNumberOfItems;

            var itemTemp       = '';
            var titleColor     = (settings && settings.mockupTitleColor) ? settings.mockupTitleColor : srPartnerPortfolio.defaultMockupTitleColor;
            var primaryColor   = (settings && settings.primaryButtonColor) ? settings.primaryButtonColor : srPartnerPortfolio.defaultPrimaryButtonColor;
            var secondaryColor = (settings && settings.secondaryButtonColor) ? settings.secondaryButtonColor : srPartnerPortfolio.defaultSecondaryButtonColor;
            var mockupPerRow   = (settings && settings.mockupPerRow) ? parseInt(settings.mockupPerRow) : srPartnerPortfolio.defaultMockupPerRow;
            var itemWidth      = (100 / mockupPerRow);

            var mockupTitleStyle   = 'color: ' + titleColor;
            var screenShotStyle    = 'background-color: ' + primaryColor + ';color: ' + secondaryColor + ';border: 1px solid ' + primaryColor + ';';
            var liveDemoStyle      = 'background-color: ' + secondaryColor + ';color: ' + primaryColor + ';border: 1px solid ' + secondaryColor + ';';
            var itemContainerStyle = 'width: ' + itemWidth + '%;';

            if (mockups.length > 0) {
                $.each(mockups, function(key, value) {
                    var isNew = (value.isNew) ?'<div class="sr-partner-ribbon"></div>' : '';
                    var itemDefault = '<div class="" style="{{itemContainerStyle}}">' +
                        '<div class="sr-partner-mockup-item" style="background-image: url({{thumbnail}});">{{isNew}}' +
                        '<div class="sr-partner-portfolio-mockup-card-overlay sr-partner-text-center">' +
                        '<div class="sr-partner-portfolio-mockup-title" style="{{mockupTitleStyle}}">{{mockupName}}</div>' +
                        '<div class="sr-partner-portfolio-btn-wrapper" align="center">' +
                        '<button style="{{screenShotStyle}}" data-screenshot-url="{{screenshot}}" data-mockup-name="{{mockupName}}" data-download-link="{{downloadLink}}" data-mockup-id="{{mockupId}}" class="sr-partner-portfolio-view-screenshot sr-partner-portfolio-primary-button sr-partner-portfolio-block">Screenshot</button>' +
                        '<a style="{{liveDemoStyle}}" class="sr-partner-portfolio-live-demo sr-partner-portfolio-secondary-button" href="#">Live Demo</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    itemTemp += srPartnerPortfolio.replaceMacro(itemDefault, Object.assign(value, {
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

        getCategoriesTemplate: function(settings, categories, isSkeleton)
        {
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
        }
    };
    var srPartnerPortfolioPreview = {
        init: function () {
            var $body = $('body');
            $body.on('change', '#sr_partner_web_portfolio_no_cards_per_row', function (e) {
                e.preventDefault();
                var $el = $(this);
                srPartnerPortfolioPreview.reRenderPreview({
                    mockupPerRow: parseInt($el.val())
                });
            });

            $body.on('change', '#switch-portfolio-show-mockup-menu', function (e) {
                var $el = $(this);
                srPartnerPortfolioPreview.reRenderPreview({
                    showMockupMenu: $el.prop('checked')
                });
            });

            $body.on('onDragEnd', '#sr_partner_web_portfolio_mockup_title_color', function () {
                var $el = $(this);
                var val = $el.val();
                var $titleEl = $('.sr-partner-portfolio-mockup-title');

                $('.sr-partner-portfolio').data('titleColor', val);
                $titleEl.css('color', val);
            });

            $body.on('onDragEnd', '#sr_partner_web_portfolio_button_primary_color', function () {
                var $el = $(this);
                var val = $el.val();
                var $screenShot = $('.sr-partner-portfolio-view-screenshot');
                var $liveDemo   = $('.sr-partner-portfolio-live-demo');

                $('.sr-partner-portfolio').data('buttonPrimary', val);
                $screenShot.css({
                    'background-color': val,
                    'border': '1px solid ' + val,
                });

                $liveDemo.css('color', val);
            });

            $body.on('onDragEnd', '#sr_partner_web_portfolio_button_secondary_color', function () {
                var $el = $(this);
                var val = $el.val();
                var $screenShot = $('.sr-partner-portfolio-view-screenshot');
                var $liveDemo   = $('.sr-partner-portfolio-live-demo');

                $('.sr-partner-portfolio').data('buttonSecondary', val);
                $screenShot.css('color', val);

                $liveDemo.css({
                    'background-color': val,
                    'border': '1px solid ' + val,
                });
            });
        },
        reRenderPreview: function (settings) {
            var $previews = $('.sr-partner-portfolio');

            if ($previews.length > 0) {
                $previews.each(function (key, el) {
                    var $el = $(el);

                    if (Object.keys(settings).length > 0) {
                        $.each(settings, function (settingKey, settingVal) {
                            switch (settingKey) {
                                case 'mockupPerRow': $el.data('cardsPerRow', settingVal);break;
                                case 'showMockupMenu': $el.data('showMockupMenu', settingVal);break;
                                case 'secondaryButtonColor': $el.data('buttonSecondary', settingVal);break;
                                case 'primaryButtonColor': $el.data('buttonPrimary', settingVal);break;
                                case 'mockupTitleColor': $el.data('titleColor', settingVal);break;
                            }
                        });

                        srPartnerPortfolio.renderPortfolio();
                    }


                });
            }
        }
    };

    $(document).ready(function() {
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            if ($('.sr-partner-portfolio').length > 0) {
                srPartnerPortfolio.renderPortfolio();
                srPartnerPortfolioPreview.init();
                myStopFunction();
            }
        }

        function myStopFunction() {
            clearInterval(myVar);
        }

    });

})(pp_js);