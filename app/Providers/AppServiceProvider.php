<?php

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();


        Blueprint::macro(
            'extendedtimestamps',
            function () {
                /** @var Blueprint $this */
                $this->timestamp('created_at')->nullable();
                $this->ulid('created_by')->nullable();
                $this->timestamp('updated_at')->nullable();
                $this->ulid('updated_by')->nullable();
                $this->timestamp('deleted_at')->nullable();
                $this->ulid('deleted_by')->nullable();
            }
        );

        Builder::macro('search', function ($fields, $string) {
            /** @var Builder $this */
            $qb = $this;

            if ($string == '') {
                return $qb;
            }

            if (!is_array($fields)) {
                $fields = [$fields];
            }

            $qb->where(function (Builder $query) use ($fields, $string) {
                foreach ($fields as $field) {
                    $fa = explode('.', $field);
                    if (count($fa) == 1) {
                        $query->orWhere($fa[0], 'like', '%' . $string . '%');
                    }
                    if (count($fa) == 2) {
                        $query->orWhereHas($fa[0], function (Builder $query) use ($fa, $string) {
                            $query->where($fa[1], 'like', '%' . $string . '%');
                        });
                    }
                }
            });

            return $qb;
        });

        Collection::macro('paginate', function ($perPage, $columns = null, $pageName = 'page', $page = null) {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            /** @var Illuminate\Database\Eloquent\Collection $this */
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Lang::handleMissingKeysUsing(function (string $key, array $replacements, string $locale) {
            info("Missing translation key [$key] detected.");

            $locale = App::currentLocale();

            if (preg_match('/^([a-z0-9]+)\\.([a-z0-9\.]+)$/', $key, $m2)) {
                $group = $m2[1];
                $key = $m2[2];
            } else {
                $group = '_json';
            }

            if (!Translation::where('key', $key)->where('locale', $locale)->exists()) {
                $entry = new Translation();
                $entry->locale = $locale;
                $entry->group = $group;
                $entry->key = $key;
                $entry->save();
            }

            if (session('translate_mode', false)) {
                return '[[[' . $key . ']]]';
            } else {
                return $key;
            }
        });
    }
}
