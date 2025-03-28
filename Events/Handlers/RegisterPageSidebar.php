<?php

namespace Modules\Page\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterPageSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('page::pages.pages'), function (Item $item) {
                $item->icon('fa fa-file');
                $item->weight(10);
//                $item->route('admin.page.page.index');
                $item->authorize(
                    $this->auth->hasAccess('page.pages.index')
                );

                $item->item(trans('page::pages.pages'), function (Item $item) {
                    $item->icon('fa fa-list-ul');
                    $item->weight(2);
                    $item->route('admin.page.page.index');
                    $item->authorize(
                        $this->auth->hasAccess('page.pages.index')
                    );
                });

                $item->item(trans('page::pages.tree'), function (Item $item) {
                    $item->icon('fa fa-sitemap');
                    $item->weight(3);
                    $item->route('admin.page.page.tree');
                    $item->authorize(
                        $this->auth->hasAccess('page.pages.index')
                    );
                });
            });
        });

        return $menu;
    }
}
