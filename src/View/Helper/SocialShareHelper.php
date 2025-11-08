<?php

namespace SocialShare\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;
use drmonkeyninja\SocialShareUrl\SocialShareUrl;

class SocialShareHelper extends Helper
{

    public $helpers = ['Html'];

    /**
     * Helper default settings.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'target' => '_blank',
        'default_fa' => 'fa-share-alt'
    ];

    /**
     * An array mapping services to their Font Awesome icons.
     *
     * @var array
     */
    protected $_fa = [
        'delicious' => 'fa-fw fa-brands fa-delicious',
        'digg' => 'fa-fw fa-brands fa-digg',
        'email' => 'fa-fw fa-solid fa-envelope',
        'facebook' => 'fa-fw fa-brands fa-facebook',
        'google' => 'fa-fw fa-brands fa-google',
        'gplus' => 'fa-fw fa-brands fa-google-plus',
        'linkedin' => 'fa-fw fa-brands fa-linkedin',
        'pinterest' => 'fa-fw fa-brands fa-pinterest',
        'pocket' => 'fa-fw fa-brands fa-get-pocket',
        'reddit' => 'fa-fw fa-brands fa-reddit',
        'stumbleupon' => 'fa-fw fa-brands fa-stumbleupon',
        'tumblr' => 'fa-fw fa-brands fa-tumblr',
        'twitter' => 'fa-fw fa-brands fa-twitter',
        'x' => 'fa-fw fa-brands fa-x-twitter',
        'whatsapp' => 'fa-fw fa-brands fa-whatsapp'
    ];
    /**
     * Returns the list of available services
     *
     * @return array
     */
    public function services()
    {
        return (new SocialShareUrl())->getServices();
    }

    /**
     * Creates a share URL.
     *
     * ### Options
     *
     * - `text` Text to be passed to service relating to the shared content(e.g. page title).
     * - `image` URL of image for sharing (used by Pinterest).
     *
     * For other options see HtmlHelper::link().
     *
     * @param string $service Social Media service to create share link for.
     * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
     * @param array $options Array of options.
     * @return string|null URL.
     */
    public function href($service, $url = null, array $options = [])
    {
        // Get the URL, get the current full path if a URL hasn't been specified.
        $url = Router::url($url, true);
        $SocialShareUrl = new SocialShareUrl();

        return $SocialShareUrl->getUrl($service, $url, $options);
    }

    /**
     * Creates an HTML link to share a URL.
     *
     * @param string $service Social Media service to create share link for.
     * @param string $text The content to be wrapped by <a> tags.
     * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
     * @param array $attributes Array of options and HTML attributes.
     * @return string An `<a />` element.
     */
    public function link($service, $text, $url = null, array $attributes = [])
    {
        $defaults = [
            'target' => $this->_config['target']
        ];
        $attributes += $defaults;

        $options = [];

        if (!empty($attributes['text'])) {
            $options['text'] = $attributes['text'];
            unset($attributes['text']);
        }

        if (!empty($attributes['image'])) {
            $options['image'] = $attributes['image'];
            unset($attributes['image']);
        }

        return $this->Html->link(
            $text,
            $this->href($service, $url, $options),
            $attributes
        );
    }

    /**
     * Creates an HTML link to share a URL using a Font Awesome icon.
     *
     * ### Options
     *
     * - `icon_class` Class name of icon for overriding defaults.
     *
     * See HtmlHelper::link().
     *
     * @param string $service Social Media service to create share link for.
     * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
     * @param array $options Array of options.
     * @return string URL.
     */
    public function fa($service, $url = null, array $options = [])
    {
        $defaults = [
            'target' => $this->_config['target']
        ];
        $options += $defaults;

        $options['escape'] = false;

        $icon = $this->icon($service, $options);
        unset($options['icon_class']);

        $attributes = $options;
        // unset($attributes['text']);
        // unset($attributes['image']);

        return $this->Html->link(
            $icon,
            $this->href($service, $url, $options),
            $attributes
        );
    }

    /**
     * Creates an icon
     *
     * ### Options
     *
     * - `icon_class` Class name of icon for overriding defaults.
     *
     * @param string $service Social Media service to create icon for
     * @param array $options Icon options
     * @return string
     */
    public function icon($service, array $options = [])
    {
        $class = 'social-share-icon fa ' . (!empty($this->_fa[$service]) ? $this->_fa[$service] : $this->_config['default_fa']);
        if (!empty($options['icon_class'])) {
            $class = $options['icon_class'];
        }

        return '<i class="' . $class . '"></i>';
    }
}
