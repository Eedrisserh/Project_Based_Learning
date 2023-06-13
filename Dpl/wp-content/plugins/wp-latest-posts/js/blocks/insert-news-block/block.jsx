(function (wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
    const {__} = wp.i18n;
    const {Component, Fragment} = wpElement;
    const {registerBlockType} = wpBlocks;
    const {BlockControls} = wpEditor;
    const {TextControl, Toolbar, IconButton} = wpComponents;
    const $ = jQuery;
    const el = wpElement.createElement;
    const iconblock = el('svg', {width: 24, height: 24, className: 'dashicon'},
        el('path', {d: "M22 13h-8v-2h8v2zm0-6h-8v2h8V7zm-8 10h8v-2h-8v2zm-2-8v6c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2zm-1.5 6l-2.25-3-1.75 2.26-1.25-1.51L3.5 15h7z"})
    );

    class WplpNews extends Component {
        /**
         * Constructor
         */
        constructor() {
            super(...arguments);
            this.state = {
                isOpenList: false,
                searchValue: '',
                preview: false
            };

            this.setWrapperRef = this.setWrapperRef.bind(this);
            this.handleClickOutside = this.handleClickOutside.bind(this);
        }

        componentWillMount() {
            const {attributes} = this.props;
            const {newsID} = attributes;
            this.initLoadTheme(newsID);
        }

        componentDidMount() {
            const {attributes} = this.props;
            const {
                shortcode
            } = attributes;

            this.setState({
                searchValue: shortcode
            });


            document.addEventListener('mousedown', this.handleClickOutside);
        }

        componentDidUpdate() {
            this.initTheme();
        }

        initLoadTheme(newsID) {
            if (parseInt(newsID) !== 0) {
                const {setAttributes} = this.props;
                fetch(wplp_blocks.vars.ajaxurl + `?action=wplp_load_html&id=${newsID}`)
                    .then(res => res.json())
                    .then(
                        (result) => {
                            if (result.status) {
                                setAttributes({
                                    html: result.html,
                                    settings: result.settings
                                });

                                this.setState({
                                    preview: true
                                });
                            }
                        },
                        // errors
                        (error) => {
                        }
                    );
            }

        }

        initTheme() {
            const {attributes, clientId} = this.props;
            const {settings} = attributes;
            let $container = $(`#block-${clientId} .wp-latest-posts-block-preview`);
            let options;
            if (settings.theme === 'default' || settings.theme === 'smooth-effect') {
                if (settings.theme === 'default') {
                    options = {
                        selector: ".defaultflexslide > .parent",
                        controlNav: false,
                        directionNav: true,
                        slideshow: true,
                        animation: "slide",
                        animationLoop: true,
                        pauseOnHover: false,
                        pauseOnAction: true,
                        direction: "horizontal",
                        slideshowSpeed: 7000,
                        animationSpeed: 600,
                        touch: true,
                    };
                } else {
                    let per_page = settings.max_elts;
                    let $col;
                    if (parseInt(per_page) > 1) {
                        $col = 3;
                        if ($(window).innerWidth() < 400) {
                            $col = 1;
                        } else if ($(window).innerWidth() < 600) {
                            $col = 2;
                        }
                    } else {
                        $col = 1;
                    }

                    options = {
                        itemWidth: 250,
                        itemMargin: 10,
                        prevText: "",
                        nextText: "",
                        minItems: $col, // use function to pull in initial value
                        maxItems: $col, // use function to pull in initial value
                        touch: true
                    };
                }

                if (typeof settings.pagination != 'undefined') {
                    switch (settings.pagination) {
                        case '0':
                            options.controlNav = false;
                            options.directionNav = false;
                            break;
                        case '1':
                            options.controlNav = false;
                            options.directionNav = true;
                            break;
                        case '2':
                            options.controlNav = true;
                            options.directionNav = true;
                            break;
                        case '3':
                            options.controlNav = true;
                            options.directionNav = false;
                            break;
                    }

                }

                if (typeof settings.autoanimation != 'undefined') {
                    switch (settings.autoanimation) {
                        case '0':
                            options.slideshow = false;
                            break;
                        case '1':
                            options.slideshow = true;
                            break;
                    }
                }

                // 0 = off
                // 1 = on
                if (typeof settings.autoanimation_trans != 'undefined') {
                    switch (settings.autoanimation_trans) {
                        case '0':
                            options.animation = "fade";
                            break;
                        case '1':
                            options.animation = "slide";
                            break;
                    }
                }

                // 0 = true
                // 1 = false
                if (typeof settings.autoanim_loop != 'undefined') {
                    switch (settings.autoanim_loop) {
                        case '0':
                            options.animationLoop = false;
                            break;
                        case '1':
                            options.animationLoop = true;
                            break;
                    }
                }

                if (typeof settings.autoanim_pause_hover != 'undefined') {
                    switch (settings.autoanim_pause_hover) {
                        case '0':
                            options.pauseOnHover = false;
                            break;
                        case '1':
                            options.pauseOnHover = true;
                            break;
                    }
                }

                if (typeof settings.autoanim_pause_action != 'undefined') {
                    switch (settings.autoanim_pause_action) {
                        case '0':
                            options.pauseOnAction = false;
                            break;
                        case '1':
                            options.pauseOnAction = true;
                            break;
                    }
                }

                if (typeof settings.autoanimation_slidedir != 'undefined') {
                    switch (settings.autoanimation_slidedir) {
                        case '0':
                            options.direction = "horizontal";
                            break;
                        case '1':
                            options.direction = "vertical";
                            break;
                    }
                }
                if (typeof settings.autoanim_touch_action != 'undefined') {
                    switch (settings.autoanim_touch_action) {
                        case '0':
                            options.touch = true;
                            break;
                        case '1':
                            options.touch = false;
                            break;
                    }
                }

                settings.autoanim_slideshowspeed = parseInt(settings.autoanim_slideshowspeed);
                if (typeof settings.autoanim_slideshowspeed != 'undefined' && !isNaN(settings.autoanim_slideshowspeed)) {
                    options.slideshowSpeed = settings.autoanim_slideshowspeed;
                }

                settings.autoanim_slidespeed = parseInt(settings.autoanim_slidespeed);
                if (typeof settings.autoanim_slidespeed != 'undefined' && !isNaN(settings.autoanim_slidespeed)) {
                    options.animationSpeed = settings.autoanim_slidespeed;
                }
            }

            imagesLoaded($container, function () {
                if (settings.theme === 'default') {
                    if (jQuery().flexslider) {
                        $(`#block-${clientId} .wp-latest-posts-block-preview .wplp_widget_default`).flexslider(options);
                    }
                }

                if (settings.theme === 'masonry-category') {
                    $(`#block-${clientId} .wplp_listposts`).masonry({
                        gutter: 10,
                        itemSelector: '.masonry-category'
                    });
                }

                if (settings.theme === 'masonry') {
                    $(`#block-${clientId} .wplp_listposts`).masonry({
                        gutter: 10,
                        itemSelector: '.masonry'
                    });
                }

                if (settings.theme === 'material-vertical') {
                    $(`#block-${clientId} .wplp_listposts`).masonry({
                        gutter: 20,
                        itemSelector: '.material-vertical'
                    });
                }

                if (settings.theme === 'portfolio') {
                    let $portfolio = $(`#block-${clientId} .wplp_listposts`);
                    $portfolio.isotope({
                        itemSelector: '.portfolio'
                    });

                    var delay = 1;
                    $portfolio.find('.portfolio').each(function () {
                        jQuery(this).find('img').one("load", function () {
                            jQuery(this).parent().delay(delay).queue(function (next) {
                                jQuery(this).addClass('img-loaded', 300);
                                next();
                            });
                            delay += 200;

                        }).each(function () {
                            if (this.complete) {
                                jQuery(this).load();
                            }
                        });
                    });
                }

                if (settings.theme === 'smooth-effect') {
                    if (jQuery().flexslider) {
                        $(`#block-${clientId} .wp-latest-posts-block-preview .wplp_widget_smooth-effect`).flexslider(options);
                    }
                }
            });
        }

        /**
         * Set the wrapper ref
         */
        setWrapperRef(node) {
            this.wrapperRef = node;
        }

        /**
         * Alert if clicked on outside of element
         */
        handleClickOutside(event) {
            if (this.wrapperRef && !this.wrapperRef.contains(event.target)) {
                const {attributes, setAttributes} = this.props;
                const {
                    shortcode
                } = attributes;

                this.setState({
                    isOpenList: false,
                    searchValue: shortcode
                });
                setAttributes({
                    shortcode: shortcode
                });
            }
        }

        /**
         * Select news post
         */
        selectPost(value) {
            const {setAttributes} = this.props;
            this.setState({
                isOpenList: false,
                searchValue: '[frontpage_news widget="' + value + '"]',
                preview: false
            });

            setAttributes({
                newsID: value.toString(),
                shortcode: '[frontpage_news widget="' + value + '"]'
            });

            this.initLoadTheme(value.toString());
        }

        /**
         * DO search news post
         */
        search(value) {
            const {setAttributes} = this.props;
            let newsSearchList = wplp_blocks.vars.posts_select.filter(function (event) {
                return event.label.toLowerCase().indexOf(value.toLowerCase()) > -1
            });

            this.setState({searchValue: value});

            setAttributes({
                newsList: newsSearchList
            });
        }

        /**
         * Click to search input
         */
        handleClick() {
            const {setAttributes} = this.props;
            setAttributes({
                newsList: wplp_blocks.vars.posts_select
            });

            this.setState({
                isOpenList: true,
                searchValue: ''
            });
        }

        /**
         * Render block
         */
        render() {
            const {attributes, clientId} = this.props;
            const {
                newsList,
                newsID,
                html,
                cover
            } = attributes;

            const controls = (
                <BlockControls>
                    {newsID !== '0' && (
                        <Toolbar>
                            <IconButton
                                className="components-toolbar__control"
                                label={__('Edit', 'wp-latest-posts')}
                                icon="edit"
                                onClick={() => window.open(wplp_blocks.vars.edit_url + newsID, '_blank')}
                            />

                            <IconButton
                                className="components-toolbar__control"
                                label={__('Refresh', 'wp-latest-posts')}
                                icon="update"
                                onClick={() => this.initLoadTheme(newsID)}
                            />

                        </Toolbar>
                    )}
                </BlockControls>
            );

            return (
                <Fragment>
                    {controls}
                    {
                        typeof cover !== "undefined" && <div className="wplp-cover"><img src={cover} /></div>
                    }
                    {
                        typeof cover === "undefined" &&
                        <div className="wp-block-shortcode" ref={this.setWrapperRef}>
                            <label>{iconblock} {wplp_blocks.l18n.block_title}</label>

                            <div className="wp-latest-posts-block">
                                <TextControl
                                    value={this.state.searchValue}
                                    className="wplp_search_news"
                                    placeholder={wplp_blocks.l18n.select_label}
                                    autoComplete="off"
                                    onChange={this.search.bind(this)}
                                    onClick={this.handleClick.bind(this)}
                                />

                                {
                                    this.state.isOpenList && newsList.length &&
                                    <ul className="wp-latest-posts-list">
                                        {
                                            newsList.map((post) =>
                                                <li className={(newsID.toString() === post.value.toString()) ? 'news_post_item news_post_item_active' : 'news_post_item'}
                                                    data-id={post.value}
                                                    key={post.value}
                                                    onClick={this.selectPost.bind(this, post.value)}>{post.label}</li>
                                            )
                                        }
                                    </ul>
                                }

                                {
                                    this.state.isOpenList && !newsList.length &&
                                    <ul className="wp-latest-posts-list">
                                        <li key="0">{wplp_blocks.l18n.no_post_found}</li>
                                    </ul>
                                }
                            </div>
                        </div>
                    }

                    {
                        this.state.preview && <div className="wp-latest-posts-block-preview" dangerouslySetInnerHTML={{__html: html}}></div>
                    }

                    {
                        !this.state.preview && newsID !== '0' && <div className="wp-latest-posts-block-preview" dangerouslySetInnerHTML={{__html: `<p class="wplp_block_loading">${__('Loading...', 'wp-latest-posts')}</p>`}}></div>
                    }
                </Fragment>
            );
        }
    }

    // register block
    registerBlockType('wplp/block-news', {
        title: wplp_blocks.l18n.block_title,
        description: __('Load your content from posts, page, tags or custom post type and display them as a slider', 'wp-latest-posts'),
        icon: iconblock,
        category: 'common',
        keywords: [
            __('post', 'wp-latest-posts'),
            __('wplp', 'wp-latest-posts')
        ],
        example: {
            attributes: {
                cover: wplp_blocks.vars.block_cover
            }
        },
        attributes: {
            newsList: {
                type: 'array',
                default: wplp_blocks.vars.posts_select
            },
            newsID: {
                type: 'string',
                default: '0'
            },
            shortcode: {
                type: 'string',
                default: ''
            },
            html: {
                type: 'string',
                default: ''
            },
            settings: {
                type: 'object',
                default: {}
            },
            cover: {
                type: 'string',
                source: 'attribute',
                selector: 'img',
                attribute: 'src',
            },
        },
        edit: WplpNews,
        save: ({attributes}) => {
            const {
                shortcode
            } = attributes;
            return shortcode;
        }
    });
})(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components);