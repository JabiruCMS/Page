<?php

namespace Modules\Page\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Traits\TaggableTrait;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property bool $is_home
 * @property string $template
 * @property int $parent_id
 * @property Page $parent
 * @property int $order
 * @property Collection<Page> $children
 * @property string $title
 * @property string $slug
 * @property string $status
 * @property string $body
 * @property string $meta_title
 * @property string $meta_description
 * @property string $og_title
 * @property string $og_description
 * @property string $og_image
 * @property string $og_type
 */
class Page extends Model implements TaggableInterface
{
    use Translatable, TaggableTrait, NamespacedEntity, MediaRelation;

    protected $table = 'page__pages';
    public $translatedAttributes = [
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];
    protected $fillable = [
        'is_home',
        'template',
        // Translatable fields
        'page_id',
        'parent_id',
        'order',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];
    protected $casts = [
        'is_home' => 'boolean',
    ];
    protected static $entityNamespace = 'asgardcms/page';

    public function getCanonicalUrl() : string
    {
        if ($this->is_home === true) {
            return url('/');
        }

        return route('page', $this->slug);
    }

    public function getEditUrl() : string
    {
        return route('admin.page.page.edit', $this->id);
    }

    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.page.config.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);

            return $function($this);
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }

    public function getImageAttribute()
    {
        $thumbnail = $this->files()->where('zone', 'image')->first();

        if ($thumbnail === null) {
            return '';
        }

        return $thumbnail;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('order');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }
}
