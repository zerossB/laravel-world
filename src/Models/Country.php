<?php
namespace Khsing\World\Models;

use Illuminate\Database\Eloquent\Model;
use Khsing\World\WorldTrait;

/**
 * Country
 */
class Country extends Model
{
    use WorldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'world_countries';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_division' => 'boolean',
    ];

    /**
     * append names
     *
     * @var array
     */
    protected $appends = ['local_name','local_full_name','local_alias', 'local_abbr'];

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Continent of country
     *
     * @return Continent
     */
    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    /**
     * Get next level
     *
     * @return collection
     */
    public function children()
    {
        if ($this->has_division == true) {
            return $this->divisions;
        }
        return $this->cities;
    }

    /**
     * Get up level
     *
     * @return Continent
     */
    public function parent()
    {
        return $this->continent;
    }

    public function locales()
    {
        return $this->hasMany(CountryLocale::class);
    }
    /**
     * Get country by name
     *
     * @param string $name
     * @return collection
     */
    public static function getByName($name)
    {
        $localized = CountryLocale::where('name', $name)->first();
        if (is_null($localized)) {
            return $localized;
        } else {
            return $localized->country;
        }
    }

    /**
     * Search country by name
     *
     * @param string $name
     * @return collection
     */
    public static function searchByName($name)
    {
        return CountryLocale::where('name', 'like', "%".$name."%")
            ->get()->map(function ($item) {
                return $item->country;
            });
    }
}
