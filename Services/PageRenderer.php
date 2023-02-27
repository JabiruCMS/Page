<?php

namespace Modules\Page\Services;

use Modules\Page\Entities\Page;
use Illuminate\Support\Collection;

class PageRenderer
{
    /**
     * @var string
     */
    private $startTag = '<div class="dd">';
    /**
     * @var string
     */
    private $endTag = '</div>';
    /**
     * @var string
     */
    private $html = '';

    /**
     * @param Collection<Page> $pages
     */
    public function render($pages): string
    {
        $this->html .= $this->startTag;
        $this->generateHtmlFor($pages);
        $this->html .= $this->endTag;

        return $this->html;
    }

    /**
     * Generate the html for the given items
     * @param Page[] $pages
     */
    private function generateHtmlFor($pages)
    {
        $this->html .= '<ol class="dd-list">';
        foreach ($pages as $page) {
            $this->html .= "<li class=\"dd-item\" data-id=\"{$page->id}\">";
            $editLink = route('admin.page.page.edit', [$page->id]);
            $blocksLink = route('admin.bocian.blocks.edit', [$page->id, get_class($page)]);
            $style = 'inline';
            $this->html .= <<<HTML
<div class="btn-group" role="group" aria-label="Action buttons" style="display: {$style}">
    <a class="btn btn-sm btn-info" style="float:left;" href="{$editLink}">
        <i class="fa fa-pencil"></i>
    </a>
HTML;
                $this->html .= <<<HTML
<a class="btn btn-sm btn-primary" style="float:left; margin-right: 15px;" href="{$blocksLink}">
        <i class="fa fa-file-text-o"></i>
    </a>
HTML;

            $this->html .= <<<HTML
</div>
HTML;
            $this->html .= "<div class=\"dd-handle\">{$page->title}</div>";

            if ($this->hasChildren($page)) {
                $this->generateHtmlFor($page->children);
            }

            $this->html .= '</li>';
        }
        $this->html .= '</ol>';
    }

    private function hasChildren(Page $page): bool
    {
        return $page->children->isNotEmpty();
    }
}
