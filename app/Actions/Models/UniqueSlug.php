<?php

namespace App\Actions\Models;

use Exception;
use Illuminate\Support\Str;

trait UniqueSlug
{

    private $slug_source = null;

    public static function bootUniqueSlug()
    {
        static::saving(function ($model) {
            $model->slug = $model->generateUniqueSlug();
        });
    }

    public function generateUniqueSlug(): string
    {
        // try to find column for slug data
        if (is_null($this->slug_source)) {

            $builder = $this->getConnection()->getSchemaBuilder();
            $table = $this->getTable();

            $columns = $builder->getColumnListing($table);
            if (!in_array('slug', $columns)) {
                throw new Exception('The table contains no slug column. Unique slugs cannot be generated.');
            }

            if (in_array('name', $columns)) {
                $this->slug_source = 'name';
            } else {

                foreach ($columns as $column) {
                    if ($column == 'id' || $column == 'slug')
                        continue;
                    if (strpos($column, 'name') !== false) {
                        $this->slug_source = $column;
                        break;
                    }
                    if (is_null($this->slug_source) && strpos($builder->getColumnType($table, $column), 'char') !== false) {
                        $this->slug_source = $column;
                    }
                }
            }
        }

        if (is_null($this->slug_source)) {
            throw new Exception('The table contains no column which can be used as source for a slug. Add at least one column which contains any type of string. Recommended column: name');
        }

        $slug = Str::of($this->name)->slug();

        $tryslug = $slug;
        $number = 0;
        while ($this->isSlugUsed($tryslug)) {
            $number++;
            $tryslug = $slug . '-' . $number;
        }

        return $tryslug;
    }

    private function isSlugUsed(string $slug): bool
    {
        return $this->where('slug', '=', $slug)
            ->where('id', '!=', $this->id ?? null) // Exclude the current model's ID
            ->exists();
    }
}
