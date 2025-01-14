<?php

namespace Modules\Settings\Entities;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Settings\Http\Traits\MediaTrait;
use Modules\Settings\Http\Traits\HasTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use HasTranslate,HasFactory,MediaTrait,InteractsWithMedia;
    #==============================================================================#
    protected $fillable = ['site_name_ar', 'site_name_en', 'description_ar', 'description_en', 'phone','email',
        'fax', 'mail_configuration','social', 'apps_ar_hint', 'apps_en_hint','address_ar','address_en'
    ];
    #==============================================================================#
     protected $casts = [
        'mail_configuration' => 'array',
        'social'             => 'array',
    ];
    #==============================================================================#
    public function registerMediaConversions(Media $media = null): void{
        $this->addMediaConversion('site')
            ->format('webp')
            ->width(200)
            ->height(120);

        $this->addMediaConversion('app')
            ->format('webp')
            ->width(90)
            ->height(90);
    }
    #==============================================================================#
    public function getLogoAttribute(){
        $model = __CLASS__;
        $photo = $model::getFirstMediaUrl('logo','site');
        return $photo;
    }
    #==============================================================================#
    protected static function newFactory()
    {
        return \Modules\Settings\Database\factories\SettingsFactory::new();
    }
}