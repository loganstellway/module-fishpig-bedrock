<?php

namespace LoganStellway\FishPigBedrock\Model;

/**
 * Menu
 */
class Menu extends \FishPig\WordPress\Model\Menu
{
    /**
     * Draw menu item
     */
    public function drawItem(\FishPig\WordPress\Model\Menu\Item $item, array $args)
    {
        $html = '';
        $args['current_depth'] = isset($args['current_depth']) ? $args['current_depth']+1 : 1;

        // Prepare attributes
        $classes = explode(' ', $item->getCssClass());
        $classes[] = 'menu__item menu-item menu-item-type-' . $item->getItemType() . ' menu-item-object-' . $item->getObjectType() . ' menu-item-' . $item->getId();
        if ($item->isItemActive()) {
            $classes[] = 'active';
        }
        if ($args['item_class']) {
            $classes[] = $args['item_class'];
        }

        // Check for child menu items
        $children = true;
        if (is_int($args['depth']) && $args['depth'] > 0 && $args['current_depth'] >= $args['depth']) {
            $children = false;
        }

        // Add children class
        if ($children && $item->getChildrenItems()->getSize()) {
            $classes[] = 'menu-item-has-children';
        }

        $class = 'class="' . implode(' ', $classes) . '"';
        $title = $item->getTitle() ? 'title="' . $item->getTitle() . '"':'';
        $target = $item->getTarget() ? 'target="' . $item->getTarget() . '"':'';
        $href = 'href="' . $item->getUrl() . '"';
        $linkClass = isset($args['link_class']) ? 'class="' . $args['link_class'] . '"':'';

        // Draw HTML
        $html .= sprintf('<li id="menu-item-%1$s" %2$s>', $item->getId(), $class);
        $html .= sprintf('<a %1$s %2$s %3$s %4$s>%5$s</a>', $href, $target, $title, $linkClass, $item->getLabel());

        // Get children HTML
        if ($children && $item->getChildrenItems()->getSize()) {
            $html .= '<ul class="sub-menu">';
            foreach ($item->getChildrenItems() as $child) {
                $html .= $this->drawItem($child, $args);
            }
            $html .= '</ul>';
        }

        // Close item
        $html .= "</li>";

        return $html;
    }

    /**
     * Draw Menu
     * 
     * @param  array  $args
     * 
     * @return string
     */
    public function getHtml(array $args = [])
    {
        // Initialize HTML
        $nav_menu = '';

        // Default Arguments
        $defaults = ['container' => 'div', 'container_class' => '', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '', 'echo' => true, 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', 'item_spacing' => 'preserve', 'depth' => 0, 'item_class' => ''];

        // Parse Arguments
        $args = array_merge($defaults, $args);

        // Prepare attributes
        $menu_id = $args['menu_id'] ?: 'menu-' . $this->getSlug();
        $menu_class = $args['menu_class'] ?: 'nav__menu nav__menu--' . $this->getSlug();

        // Loop through items
        $link_items = '';
        foreach ($this->getMenuItems() as $item) {
            $link_items .= $this->drawItem($item, $args);
        }

        $nav_menu = sprintf($args['items_wrap'], $menu_id, $menu_class, $link_items);

        // Container
        if ($args['container']) {
            // Prepare attributes
            if (!in_array($args['container'], ['div', 'nav'])) {
                $args['container'] = $defaults['container'];
            }
            $container_id = $args['container_id'] ? 'id="' . $args['container_id'] . '"':'';
            $container_class = $args['container_class'] ?:'menu-' . $this->getSlug() . '-container';

            $nav_menu = sprintf('<%1$s %2$s class="%3$s">%4$s</%1$s>', $args['container'], $container_id, $container_class, $nav_menu);
        }

        // Return value
        if ($args['echo']) {
            echo $nav_menu;
        }
        return $nav_menu;
    }
}
