<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('backorgo')) {
    function backorgo($url): RedirectResponse
    {
        // request()->fullUrl() reorders query parameters, therefore it is not reliable to get the correct comparison
        // Url must be compared manually


        if (request()->method() == 'GET') {
            $back = parse_url(back()->getTargetUrl());
            $here = parse_url(request()->fullUrl());
            parse_str($back['query'] ?? null, $back['query']);
            parse_str($here['query'] ?? null, $here['query']);

            if ($back == $here) {
                return new RedirectResponse($url);
            }
        }

        return back();
    }
}

if (!function_exists('backorhome')) {
    function backorhome(): RedirectResponse
    {
        return backorgo('/');
    }
}

if (!function_exists('user_icon_color')) {
    function user_icon_color(\App\Models\User $user)
    {
        if (is_null($user)) {
            return 'text-gray-800 dark:text-gray-200';
        }

        if ($user->admin) {
            $class = 'text-red-600 dark:text-red-500';
        } else {
            $class = 'text-green-600 dark:text-green-500';
        }

        if ($user->id == Auth::user()->id) {
            $class .= ' bg-yellow-300 dark:bg-yellow-900';
        }

        if (!$user->active || is_null($user->email_verified_at)) {
            $class .= ' opacity-25';
        }

        return $class;
    }
}

if (!function_exists('from_array')) {
    function from_array($needle, $haystack, $default = null)
    {
        if (is_array($haystack)) {
            if (isset($haystack[$needle])) {
                return $haystack[$needle];
            }
            if (in_array($needle, $haystack)) {
                return $needle;
            }
        } else {
            if ($needle == $haystack) {
                return $needle;
            }
        }

        return $default;
    }
}

if (!function_exists('is_user')) {
    function is_user(User $user = null)
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        if (is_null($user)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(User $user = null)
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        if (is_null($user)) {
            return false;
        }

        if (!$user->admin) {
            return false;
        }

        return true;
    }
}

if (!function_exists('is_last_admin')) {
    function is_last_admin(User $user = null)
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        if (is_null($user)) {
            return false;
        }

        if (!$user->admin) {
            return false;
        }

        if (User::where('admin', true)->whereNot('id', $user->id)->count() == 0) {
            return true;
        }

        return false;
    }
}

if (!function_exists('is_elevated')) {
    function is_elevated(User $user = null)
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        if (is_null($user)) {
            return false;
        }

        if (!$user->admin) {
            return false;
        }

        if ($user->elevated) {
            return true;
        }

        return false;
    }
}

if (!function_exists('random_float')) {

    function random_float($min, $max)
    {
        return random_int($min * 10000, $max * 10000) / 10000;
    }
}

if (!function_exists('HSVtoRGB')) {

    /**
     * Convert HSV color to RGB
     *
     * This function converts colors in HSV format to RGB format. The calculation
     * is described at https://en.wikipedia.org/wiki/HSL_and_HSV
     *
     * @param int $iH The Hue of the color in the range from 0 to 360.
     *                Instead of a single value an array holding all parameters
     *                at once can be used.
     * @param int $iS The Saturation of the color in the range from 0 to 100
     * @param int $iV The Value of the color in the range from 0 to 100
     *
     * @return array Returns an array of the red, green and blue component of the
     *               color. The range is from 0 to 255.
     *
     */

    function HSVtoRGB($iH, $iS = 0, $iV = 0)
    {
        if (is_array($iH)) {
            $iS = $iH[1] ?? 0;
            $iV = $iH[2] ?? 0;
            $iH = $iH[0];
        }

        if ($iH < 0) {
            $iH = 0;
        } // Hue:
        if ($iH > 360) {
            $iH = 360;
        } //   0-360
        if ($iS < 0) {
            $iS = 0;
        } // Saturation:
        if ($iS > 100) {
            $iS = 100;
        } //   0-100
        if ($iV < 0) {
            $iV = 0;
        } // Lightness:
        if ($iV > 100) {
            $iV = 100;
        } //   0-100

        $dS = $iS / 100.0; // Saturation: 0.0-1.0
        $dV = $iV / 100.0; // Lightness:  0.0-1.0

        $dC = $dV * $dS; // Chroma:     0.0-1.0
        $dH = $iH / 60.0; // H-Prime:    0.0-6.0

        $dX = $dC * (1 - abs(fmod($dH, 2) - 1));

        if ($dH < 0) {
            $dR = 0.0;
            $dG = 0.0;
            $dB = 0.0;
        } elseif ($dH < 1) {
            $dR = $dC;
            $dG = $dX;
            $dB = 0.0;
        } elseif ($dH < 2) {
            $dR = $dX;
            $dG = $dC;
            $dB = 0.0;
        } elseif ($dH < 3) {
            $dR = 0.0;
            $dG = $dC;
            $dB = $dX;
        } elseif ($dH < 4) {
            $dR = 0.0;
            $dG = $dX;
            $dB = $dC;
        } elseif ($dH < 5) {
            $dR = $dX;
            $dG = 0.0;
            $dB = $dC;
        } elseif ($dH < 6) {
            $dR = $dC;
            $dG = 0.0;
            $dB = $dX;
        } else {
            $dR = 0.0;
            $dG = 0.0;
            $dB = 0.0;
        }

        $dM = $dV - $dC;
        $dR += $dM;
        $dG += $dM;
        $dB += $dM;
        $dR *= 255;
        $dG *= 255;
        $dB *= 255;

        return [round($dR), round($dG), round($dB)];
    }
}


if (!function_exists('RGBtoHEX')) {

    /**
     * Prints a RGB number as hex value valid for HTML
     *
     * @param int|array $iR The red value in the range from 0 to 255. Instead of a
     *                      single value an array holding all parameters at once can
     *                      be used.
     * @param int $iG The green value in the range from 0 to 255.
     * @param int $iB The blue value in the range from 0 to 255.
     * @param string $sP Optional. The Prefix. Default: #
     *
     * @return string
     */

    function RGBtoHEX($iR, $iG = 0, $iB = 0, $sP = '#')
    {
        if (is_array($iR)) {
            $iG = $iR[1] ?? 0;
            $iB = $iR[2] ?? 0;
            $sP = $iR[3] ?? '#';
            $iR = $iR[0];
        }

        if ($iR < 0) {
            $iR = 0;
        }
        if ($iR > 255) {
            $iR = 255;
        }
        if ($iG < 0) {
            $iG = 0;
        }
        if ($iG > 255) {
            $iG = 255;
        }
        if ($iB < 0) {
            $iB = 0;
        }
        if ($iB > 255) {
            $iB = 255;
        }

        $sR = str_pad(dechex($iR), 2, '0', STR_PAD_LEFT);
        $sG = str_pad(dechex($iG), 2, '0', STR_PAD_LEFT);
        $sB = str_pad(dechex($iB), 2, '0', STR_PAD_LEFT);

        return $sP . $sR . $sG . $sB;
    }
}

if (!function_exists('replace_umlaut')) {
    function replace_umlaut($text)
    {
        return str_replace([
            'ä',
            'ö',
            'ü',
            'ß',
            'Ä',
            'Ö',
            'Ü'
        ], [
            'ae',
            'oe',
            'ue',
            'ss',
            'Ae',
            'Oe',
            'Ue'
        ], $text ?? '');
    }
}

if (!function_exists('check_rights')) {
    function check_rights($rights, $write, Project $project = null, User $user = null)
    {
        if (is_null($user)) {
            /** @var $user \App\Models\User */
            $user = Auth::user();
        }
        if (is_null($user)) {
            return false;
        }

        if (!is_array($rights)) {
            $rights = [$rights];
        }

        if ($write) {
            return $user->can_write($rights, $project);
        } else {
            return $user->can_read($rights, $project);
        }
    }
}

if (!function_exists('check_read')) {
    function check_read($rights, Project $project = null, User $user = null)
    {
        return check_rights($rights, false, $project, $user);
    }
}

if (!function_exists('check_write')) {
    function check_write($rights, Project $project = null, User $user = null)
    {
        return check_rights($rights, true, $project, $user);
    }
}

if (!function_exists('has_project')) {
    function has_project(Project $project = null, User $user = null)
    {
        if (is_null($user)) {
            /** @var $user \App\Models\User */
            $user = Auth::user();
        }
        if (is_null($user)) {
            return false;
        }

        return $user->has_project($project);
    }
}

if (!function_exists('text_format')) {
    function text_format($text)
    {
        $text = e($text);
        $text = Str::inlineMarkdown($text);
        $text = preg_replace_callback('/\[([^\|\]]*)\|?(.*)\]/', fn ($m) => '<a href="' . $m[1] . '">' . (empty($m[2]) ? $m[1] : $m[2]) . '</a>', $text);
        $text = str_replace('<a href=', '<a class="inline-flex items-center underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 text-gray-600 dark:text-gray-400" href=', $text);
        $text = trim($text);
        $text = nl2br($text);

        return $text;
    }
}

if (!function_exists('text_code_hint')) {
    function text_code_hint()
    {
        $hint = '<p>';
        $hint .= e(__('Add an ingredient by using:'));
        $hint .= ' <span class="font-black">[' . e(__('REFERENCE')) . ']</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add an amount by using:'));
        $hint .= ' <span class="font-black">[123]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[0.25]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[1/4]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add a fixed number by using:'));
        $hint .= ' <span class="font-black">[123!]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[0.25!]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[1/4!]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add temperature by using:'));
        $hint .= ' <span class="font-black">[T180]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add an icon by using:'));
        $hint .= ' <span class="font-black">:icon:</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add a Thermomix instruction by using:'));
        $hint .= ' <span class="font-black">~time/temp/speed~</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Change color by using:'));
        $hint .= ' <span class="font-black">{' . e(__('color')) . '}</span>';
        $hint .= '</p><p>';
        $hint .= Blade::render('<x-link route="textcode" target="_blank">' . e(__('For more information click here')) . '</x-link>');
        $hint .= '</p>';


        return $hint;
    }
}

if (!function_exists('text_code_icons')) {
    function text_code_icons()
    {
        $icons = [
            // Thermomix
            'tm5',
            'tm6',
            'tm-dough',
            'tm-stir',
            'tm-rev',
            // Icons
            'arrow-circle-down',
            'arrow-circle-left',
            'arrow-circle-right',
            'arrow-circle-up',
            'arrow-down',
            'arrow-left',
            'arrow-right',
            'arrow-up',
            'asterisk',
            'at',
            'chat-dots',
            'check',
            'check-circle',
            'eye',
            'info',
            'pause',
            'play',
            'smiley',
            'smiley-meh',
            'smiley-sad',
            'smiley-wink',
            'star',
            'thumbs-up',
            'thumbs-down',
            'warning',
            // Food
            'beer-bottle',
            'bone',
            'bowl-food',
            'brandy',
            'cactus',
            'cake',
            'carrot',
            'champagne',
            'coffee',
            'cookie',
            'drop',
            'egg',
            'fish',
            'flask',
            'flower',
            'grains',
            'grains-slash',
            'hamburger',
            'ice-cream',
            'leaf',
            'martini',
            'orange-slice',
            'pepper',
            'pill',
            'pizza',
            'plant',
            'popcorn',
            'shrimp',
            'tree',
            'tree-evergreen',
            'wine',
            // Tools
            'alarm',
            'app-window',
            'bell',
            'bell-ringing',
            'bell-slash',
            'bluetooth',
            'calculator',
            'camera',
            'circles-four',
            'clock',
            'cooking-pot',
            'eyedropper',
            'fan',
            'fork-knife',
            'funnel',
            'gear',
            'hand',
            'hourglass',
            'knife',
            'needle',
            'paperclip',
            'pencil',
            'scales',
            'snowflake',
            'speaker-high',
            'speaker-x',
            'spiral',
            'syringe',
            'thermometer',
            'timer',
            'toilet-paper',
            'trash',
            'watch',
            'waves',
            // Environment
            'airplane',
            'anchor',
            'armchair',
            'baby',
            'bird',
            'bug',
            'butterfly',
            'campfire',
            'car',
            'cat',
            'dog',
            'eyeglasses',
            'face-mask',
            'fingerprint',
            'fire',
            'first-aid',
            'hand-soap',
            'house',
            'key',
            'lightbulb',
            'lightning',
            'person',
            'power',
            'recycle',
            // Shopping
            'address-book',
            'archive',
            'article',
            'bag',
            'barcode',
            'basket',
            'book',
            'book-open',
            'book-open-text',
            'calendar',
            'gift',
            'shopping-cart',
            'storefront',
            'wallet',
        ];

        return $icons;
    }
}

if (!function_exists('text_code_colors')) {
    function text_code_colors()
    {
        $colors = [
            'gray' => 'text-gray-500',
            'red' => 'text-red-500',
            'orange' => 'text-orange-500',
            'amber' => 'text-amber-500',
            'yellow' => 'text-yellow-500',
            'lime' => 'text-lime-500',
            'green' => 'text-green-500',
            'emerald' => 'text-emerald-500',
            'teal' => 'text-teal-500',
            'cyan' => 'text-cyan-500',
            'sky' => 'text-sky-500',
            'blue' => 'text-blue-500',
            'indigo' => 'text-indigo-500',
            'violet' => 'text-violet-500',
            'purple' => 'text-fuchsia-500',
            'pink' => 'text-pink-500',
            'rose' => 'text-rose-500',
        ];

        return $colors;
    }
}

if (!function_exists('text_code_format')) {
    function text_code_format($text, $ingredients = [], $preview = false)
    {
        $text = e($text);

        $text = preg_replace_callback('/~(?:(\d+(?:-\d+)?M?)(?:\/(\d+|V))?\/)?(-?[0-9DS\.]+)~/', function ($matches) {
            $time = $matches[1];
            $temp = $matches[2];
            $speed = $matches[3];

            $text = [];

            if ($time == '') {
                $t1 = 0;
                $t2 = 0;
            } else {
                preg_match('/(\d+)(?:-(\d+))?(M?)/', $time, $mtime);
                $t1 = intval($mtime[1]);
                $t2 = intval($mtime[2]);
                if ($mtime[3] == 'M') {
                    $t1 *= 60;
                    $t2 *= 60;
                }
            }
            if ($t1 > $t2) {
                $tt = $t1;
                $t1 = $t2;
                $t2 = $tt;
            }
            if ($t1 == $t2) {
                $t1 = 0;
            }

            $text_hour = cache('tc_hour');
            if (is_null($text_hour)) {
                $text_hour = __('hour');
                cache(['tc_hour' => $text_hour]);
            }
            $text_min = cache('tc_min');
            if (is_null($text_min)) {
                $text_min = __('min');
                cache(['tc_min' => $text_min]);
            }
            $text_sec = cache('tc_sec');
            if (is_null($text_sec)) {
                $text_sec = __('sec');
                cache(['tc_sec' => $text_sec]);
            }

            if ($t2 != 0) {
                if ($t1 == 0) {
                    $h = intval($t2 / 3600);
                    $m = intval(($t2 / 60) % 60);
                    $s = intval($t2 % 60);
                    $t2 = [];
                    if ($h) {
                        array_push($t2, $h . ' ' . $text_hour);
                    }
                    if ($m) {
                        array_push($t2, $m . ' ' . $text_min);
                    }
                    if ($s) {
                        array_push($t2, $s . ' ' . $text_sec);
                    }
                    array_push($text, implode(' ', $t2));
                } else {
                    $h1 = intval($t1 / 3600);
                    $m1 = intval(($t1 / 60) % 60);
                    $s1 = intval($t1 % 60);
                    $h2 = intval($t2 / 3600);
                    $m2 = intval(($t2 / 60) % 60);
                    $s2 = intval($t2 % 60);
                    $p = 0;
                    $h = $h1 != 0 || $h2 != 0;
                    if ($h) {
                        $p++;
                    }
                    $m = $m1 != 0 || $m2 != 0;
                    if ($m) {
                        $p++;
                    }
                    $s = $s1 != 0 || $s2 != 0;
                    if ($s) {
                        $p++;
                    }

                    if ($p > 1) {
                        $t1 = [];
                        if ($h1) {
                            array_push($t1, $h1 . ' ' . $text_hour);
                        }
                        if ($m1) {
                            array_push($t1, $m1 . ' ' . $text_min);
                        }
                        if ($s1) {
                            array_push($t1, $s1 . ' ' . $text_sec);
                        }
                        $t2 = [];
                        if ($h2) {
                            array_push($t2, $h2 . ' ' . $text_hour);
                        }
                        if ($m2) {
                            array_push($t2, $m2 . ' ' . $text_min);
                        }
                        if ($s2) {
                            array_push($t2, $s2 . ' ' . $text_sec);
                        }
                    } else {
                        $t1 = [];
                        if ($h1) {
                            array_push($t1, $h1);
                        }
                        if ($m1) {
                            array_push($t1, $m1);
                        }
                        if ($s1) {
                            array_push($t1, $s1);
                        }
                        $t2 = [];
                        if ($h2) {
                            array_push($t2, $h2 . ' ' . $text_hour);
                        }
                        if ($m2) {
                            array_push($t2, $m2 . ' ' . $text_min);
                        }
                        if ($s2) {
                            array_push($t2, $s2 . ' ' . $text_sec);
                        }
                    }

                    array_push($text, implode(' ', $t1) . ' - ' . implode(' ', $t2));
                }
            }

            if ($temp == 'V') {
                $text_varoma = cache('tc_varoma');
                if (is_null($text_varoma)) {
                    $text_varoma = __('Varoma');
                    cache(['tc_varoma' => $text_varoma]);
                }
                array_push($text, $text_varoma);
            } else {
                $temp = intval($temp);
                if ($temp != 0) {
                    array_push($text, '[T' . $temp . ']');
                }
            }

            if ($speed != '') {
                preg_match('/(-?)([0-9DS\.]+)/', $speed, $mspeed);
                $r = $mspeed[1];
                $s = $mspeed[2];

                $text_speed = cache('tc_speed');
                if (is_null($text_speed)) {
                    $text_speed = __('speed');
                    cache(['tc_speed' => $text_speed]);
                }

                if ($r == '-') {
                    array_push($text, ':tm-rev:');
                }

                if ($s == 'D') {
                    array_push($text, ':tm-dough:');
                } elseif ($s == 'S') {
                    array_push($text, $text_speed . ' :tm-stir:');
                } else {
                    $s = floatval($s);
                    $s = $s * 2;
                    $s = round($s);
                    $s = $s / 2;
                    if ($s != 0) {
                        array_push($text, $text_speed . ' ' . $s);
                    }
                }

            }

            $text = implode('/', $text);
            if ($text != '') {
                $text = '<span class="thermomix font-bold">' . $text . '</span>';
            }
            return $text;
        }, $text);

        $text = preg_replace_callback('/\[([a-z]+\d+)\]/', function ($matches) use ($ingredients, $preview) {
            $i = $matches[1];
            $text = $i;
            if (isset($ingredients[$i])) {
                $text = $ingredients[$i];
            }

            if ($preview) {
                return '<span class="bg-green-200 dark:bg-green-800">' . $text . ' (' . $i . ')</span>';
            }
            return '<span class="ingredient transition-colors" x-orig="' . $i . '">' . $text . '</span>';
        }, $text);

        $text = trim($text);
        $text = nl2br($text);

        $colors = text_code_colors();
        $spans = 0;
        $text = preg_replace_callback('/\{([a-z]+|-)\}/', function ($matches) use ($colors, &$spans) {

            $text = '';
            if ($matches[1] == '-') {
                $text = str_repeat('</span>', $spans);
                $spans = 0;
            } else {
                if (isset($colors[$matches[1]])) {
                    $text = str_repeat('</span>', $spans);
                    $text .= '<span class="' . $colors[$matches[1]] . '">';
                    $spans = 1;
                }
            }

            return $text;

        }, $text);

        $text = preg_replace_callback('/\[T(-?\d+?)\]/', function ($matches) use ($preview) {
            $t = intval($matches[1]);

            if ($preview) {
                return $t . '°C';
            }

            return '<span x-data="{
                    temp: ' . $t . ',
                    temp_out: ' . $t . ',
                    update() {
                        if(this.temp_type == \'F\')
                            this.temp_out = calculate_round(this.temp * 9 / 5 + 32, -1) + \'°F\';
                        else
                            this.temp_out = calculate_round(this.temp, 0) + \'°C\';
                    }
                }" x-on:update_numbers.window="update()" x-html="temp_out"></span>';
        }, $text);

        $text = preg_replace_callback('/\[(\d+(?:\.\d+)?(!?))\]/', function ($matches) use ($preview) {
            $n = floatval($matches[1]);
            if ($preview) {
                return $n;
            }

            return '<span x-data="{
                    num: ' . $n . ',
                    num_out: ' . $n . ',
                    fix: ' . ($matches[2] == '!' ? 'true' : 'false') . ',
                    update() {
                        if(this.fix)
                            this.num_out = calculate_round(this.num,\'A\');
                        else
                            this.num_out = calculate_round(this.num * this.factor, \'A\');
                    }
                }" x-on:update_numbers.window="update()" x-html="num_out"></span>';
        }, $text);

        $text = preg_replace_callback('/\[(\d+)\/(\d+)(!?)\]/', function ($matches) use ($preview) {
            $n1 = intval($matches[1]);
            $n2 = intval($matches[2]);
            if ($n2 == 0) {
                return '0';
            }

            if ($preview) {
                return '<span class="diagonal-fractions">' . $n1 . '/' . $n2 . '</span>';
            }

            return '<span x-data="{
                    num: ' . ($n1 / $n2) . ',
                    num_out: ' . ($n1 / $n2) . ',
                    fix: ' . ($matches[3] == '!' ? 'true' : 'false') . ',
                    update() {
                        if(this.fix)
                            this.num_out = calculate_fraction(this.num);
                        else
                            this.num_out = calculate_fraction(this.num * this.factor);
                    }
                }" x-on:update_numbers.window="update()" x-html="num_out"></span>';
        }, $text);

        $icons = text_code_icons();
        $text = preg_replace_callback('/:([a-z-]+):/', function ($matches) use ($icons) {

            if (in_array($matches[1], $icons)) {
                $key = 'recipe-icon-cache-' . $matches[1];
                $icon = cache($key);
                if (is_null($icon)) {
                    $icon = Blade::render('<x-recipe-icon  name="' . $matches[1] . '" />');
                    cache([$key => $icon], 3600);
                }
                return $icon;
            }

            return $matches[0];
        }, $text);
        return $text;
    }
}

if (!function_exists('calculate_time')) {
    function calculate_time($t)
    {
        $h = intval($t / 60);
        $m = intval($t % 60);
        $t2 = [];
        if ($h) {
            array_push($t2, $h . ' ' . __('hours'));
        }
        if ($m) {
            array_push($t2, $m . ' ' . __('minutes'));
        }
        return implode(' ', $t2);
    }
}

if (!function_exists('IsSlotEmpty')) {
    function IsSlotEmpty(\Illuminate\View\ComponentSlot $slot): bool
    {
        if ($slot->isEmpty()) {
            return true;
        }
        $content = $slot->toHtml();
        // Remove comments
        $content = preg_replace('/<!--.+?-->/', '', $content);
        // Trim data
        $content = trim($content);

        if ($content === '') {
            return true;
        }

        return false;
    }
}

if (!function_exists('default_colors')) {
    function default_colors()
    {
        //Based on the Web colors of CSS3
        return [
            //Gray and black
            ['name' => __('colors.Black'), 'value' => '#000000'],
            ['name' => __('colors.DarkSlateGray'), 'value' => '#2f4f4f'],
            ['name' => __('colors.DimGray'), 'value' => '#696969'],
            ['name' => __('colors.SlateGray'), 'value' => '#708090'],
            ['name' => __('colors.Gray'), 'value' => '#808080'],
            ['name' => __('colors.LightSlateGray'), 'value' => '#778899'],
            ['name' => __('colors.DarkGray'), 'value' => '#a9a9a9'],
            ['name' => __('colors.Silver'), 'value' => '#c0c0c0'],
            ['name' => __('colors.LightGray'), 'value' => '#d3d3d3'],
            ['name' => __('colors.Gainsboro'), 'value' => '#dcdcdc'],
            //White
            ['name' => __('colors.MistyRose'), 'value' => '#ffe4e1'],
            ['name' => __('colors.AntiqueWhite'), 'value' => '#faebd7'],
            ['name' => __('colors.Linen'), 'value' => '#faf0e6'],
            ['name' => __('colors.Beige'), 'value' => '#f5f5dc'],
            ['name' => __('colors.WhiteSmoke'), 'value' => '#f5f5f5'],
            ['name' => __('colors.LavenderBlush'), 'value' => '#fff0f5'],
            ['name' => __('colors.OldLace'), 'value' => '#fdf5e6'],
            ['name' => __('colors.AliceBlue'), 'value' => '#f0f8ff'],
            ['name' => __('colors.Seashell'), 'value' => '#fff5ee'],
            ['name' => __('colors.GhostWhite'), 'value' => '#f8f8ff'],
            ['name' => __('colors.Honeydew'), 'value' => '#f0fff0'],
            ['name' => __('colors.FloralWhite'), 'value' => '#fffaf0'],
            ['name' => __('colors.Azure'), 'value' => '#f0ffff'],
            ['name' => __('colors.MintCream'), 'value' => '#f5fffa'],
            ['name' => __('colors.Snow'), 'value' => '#fffafa'],
            ['name' => __('colors.Ivory'), 'value' => '#fffff0'],
            ['name' => __('colors.White'), 'value' => '#ffffff'],
            //Red
            ['name' => __('colors.DarkRed'), 'value' => '#8b0000'],
            ['name' => __('colors.Red'), 'value' => '#ff0000'],
            ['name' => __('colors.Firebrick'), 'value' => '#b22222'],
            ['name' => __('colors.Crimson'), 'value' => '#dc143c'],
            ['name' => __('colors.IndianRed'), 'value' => '#cd5c5c'],
            ['name' => __('colors.LightCoral'), 'value' => '#f08080'],
            ['name' => __('colors.Salmon'), 'value' => '#fa8072'],
            ['name' => __('colors.DarkSalmon'), 'value' => '#e9967a'],
            ['name' => __('colors.LightSalmon'), 'value' => '#ffa07a'],
            //Orange
            ['name' => __('colors.OrangeRed'), 'value' => '#ff4500'],
            ['name' => __('colors.Tomato'), 'value' => '#ff6347'],
            ['name' => __('colors.DarkOrange'), 'value' => '#ff8c00'],
            ['name' => __('colors.Coral'), 'value' => '#ff7f50'],
            ['name' => __('colors.Orange'), 'value' => '#ffa500'],
            //Yellow
            ['name' => __('colors.DarkKhaki'), 'value' => '#bdb76b'],
            ['name' => __('colors.Gold'), 'value' => '#ffd700'],
            ['name' => __('colors.Khaki'), 'value' => '#f0e68c'],
            ['name' => __('colors.PeachPuff'), 'value' => '#ffdab9'],
            ['name' => __('colors.Yellow'), 'value' => '#ffff00'],
            ['name' => __('colors.PaleGoldenrod'), 'value' => '#eee8aa'],
            ['name' => __('colors.Moccasin'), 'value' => '#ffe4b5'],
            ['name' => __('colors.PapayaWhip'), 'value' => '#ffefd5'],
            ['name' => __('colors.LightGoldenrodYellow'), 'value' => '#fafad2'],
            ['name' => __('colors.LemonChiffon'), 'value' => '#fffacd'],
            ['name' => __('colors.LightYellow'), 'value' => '#ffffe0'],
            //Brown
            ['name' => __('colors.Maroon'), 'value' => '#800000'],
            ['name' => __('colors.Brown'), 'value' => '#a52a2a'],
            ['name' => __('colors.SaddleBrown'), 'value' => '#8b4513'],
            ['name' => __('colors.Sienna'), 'value' => '#a0522d'],
            ['name' => __('colors.Chocolate'), 'value' => '#d2691e'],
            ['name' => __('colors.DarkGoldenrod'), 'value' => '#b8860b'],
            ['name' => __('colors.Peru'), 'value' => '#cd853f'],
            ['name' => __('colors.RosyBrown'), 'value' => '#bc8f8f'],
            ['name' => __('colors.Goldenrod'), 'value' => '#daa520'],
            ['name' => __('colors.SandyBrown'), 'value' => '#f4a460'],
            ['name' => __('colors.Tan'), 'value' => '#d2b48c'],
            ['name' => __('colors.Burlywood'), 'value' => '#deb887'],
            ['name' => __('colors.Wheat'), 'value' => '#f5deb3'],
            ['name' => __('colors.NavajoWhite'), 'value' => '#ffdead'],
            ['name' => __('colors.Bisque'), 'value' => '#ffe4c4'],
            ['name' => __('colors.BlanchedAlmond'), 'value' => '#ffebcd'],
            ['name' => __('colors.Cornsilk'), 'value' => '#fff8dc'],
            //Pink
            ['name' => __('colors.MediumVioletRed'), 'value' => '#c71585'],
            ['name' => __('colors.DeepPink'), 'value' => '#ff1493'],
            ['name' => __('colors.PaleVioletRed'), 'value' => '#db7093'],
            ['name' => __('colors.HotPink'), 'value' => '#ff69b4'],
            ['name' => __('colors.LightPink'), 'value' => '#ffb6c1'],
            ['name' => __('colors.Pink'), 'value' => '#ffc0cb'],
            //Purple
            ['name' => __('colors.Indigo'), 'value' => '#4b0082'],
            ['name' => __('colors.Purple'), 'value' => '#800080'],
            ['name' => __('colors.DarkMagenta'), 'value' => '#8b008b'],
            ['name' => __('colors.DarkViolet'), 'value' => '#9400d3'],
            ['name' => __('colors.DarkSlateBlue'), 'value' => '#483d8b'],
            ['name' => __('colors.BlueViolet'), 'value' => '#8a2be2'],
            ['name' => __('colors.DarkOrchid'), 'value' => '#9932cc'],
            ['name' => __('colors.Fuchsia'), 'value' => '#ff00ff'],
            ['name' => __('colors.Magenta'), 'value' => '#ff00ff'],
            ['name' => __('colors.SlateBlue'), 'value' => '#6a5acd'],
            ['name' => __('colors.MediumSlateBlue'), 'value' => '#7b68ee'],
            ['name' => __('colors.MediumOrchid'), 'value' => '#ba55d3'],
            ['name' => __('colors.MediumPurple'), 'value' => '#9370db'],
            ['name' => __('colors.Orchid'), 'value' => '#da70d6'],
            ['name' => __('colors.Violet'), 'value' => '#ee82ee'],
            ['name' => __('colors.Plum'), 'value' => '#dda0dd'],
            ['name' => __('colors.Thistle'), 'value' => '#d8bfd8'],
            ['name' => __('colors.Lavender'), 'value' => '#e6e6fa'],
            //Blue
            ['name' => __('colors.MidnightBlue'), 'value' => '#191970'],
            ['name' => __('colors.Navy'), 'value' => '#000080'],
            ['name' => __('colors.DarkBlue'), 'value' => '#00008b'],
            ['name' => __('colors.MediumBlue'), 'value' => '#0000cd'],
            ['name' => __('colors.Blue'), 'value' => '#0000ff'],
            ['name' => __('colors.RoyalBlue'), 'value' => '#4169e1'],
            ['name' => __('colors.SteelBlue'), 'value' => '#4682b4'],
            ['name' => __('colors.DodgerBlue'), 'value' => '#1e90ff'],
            ['name' => __('colors.DeepSkyBlue'), 'value' => '#00bfff'],
            ['name' => __('colors.CornflowerBlue'), 'value' => '#6495ed'],
            ['name' => __('colors.SkyBlue'), 'value' => '#87ceeb'],
            ['name' => __('colors.LightSkyBlue'), 'value' => '#87cefa'],
            ['name' => __('colors.LightSteelBlue'), 'value' => '#b0c4de'],
            ['name' => __('colors.LightBlue'), 'value' => '#add8e6'],
            ['name' => __('colors.PowderBlue'), 'value' => '#b0e0e6'],
            //Cyan
            ['name' => __('colors.Teal'), 'value' => '#008080'],
            ['name' => __('colors.DarkCyan'), 'value' => '#008b8b'],
            ['name' => __('colors.LightSeaGreen'), 'value' => '#20b2aa'],
            ['name' => __('colors.CadetBlue'), 'value' => '#5f9ea0'],
            ['name' => __('colors.DarkTurquoise'), 'value' => '#00ced1'],
            ['name' => __('colors.MediumTurquoise'), 'value' => '#48d1cc'],
            ['name' => __('colors.Turquoise'), 'value' => '#40e0d0'],
            ['name' => __('colors.Aqua'), 'value' => '#00ffff'],
            ['name' => __('colors.Cyan'), 'value' => '#00ffff'],
            ['name' => __('colors.Aquamarine'), 'value' => '#7fffd4'],
            ['name' => __('colors.PaleTurquoise'), 'value' => '#afeeee'],
            ['name' => __('colors.LightCyan'), 'value' => '#e0ffff'],
            //Green
            ['name' => __('colors.DarkGreen'), 'value' => '#006400'],
            ['name' => __('colors.Green'), 'value' => '#008000'],
            ['name' => __('colors.DarkOliveGreen'), 'value' => '#556b2f'],
            ['name' => __('colors.ForestGreen'), 'value' => '#228b22'],
            ['name' => __('colors.SeaGreen'), 'value' => '#2e8b57'],
            ['name' => __('colors.Olive'), 'value' => '#808000'],
            ['name' => __('colors.OliveDrab'), 'value' => '#6b8e23'],
            ['name' => __('colors.MediumSeaGreen'), 'value' => '#3cb371'],
            ['name' => __('colors.LimeGreen'), 'value' => '#32cd32'],
            ['name' => __('colors.Lime'), 'value' => '#00ff00'],
            ['name' => __('colors.SpringGreen'), 'value' => '#00ff7f'],
            ['name' => __('colors.MediumSpringGreen'), 'value' => '#00fa9a'],
            ['name' => __('colors.DarkSeaGreen'), 'value' => '#8fbc8f'],
            ['name' => __('colors.MediumAquamarine'), 'value' => '#66cdaa'],
            ['name' => __('colors.YellowGreen'), 'value' => '#9acd32'],
            ['name' => __('colors.LawnGreen'), 'value' => '#7cfc00'],
            ['name' => __('colors.Chartreuse'), 'value' => '#7fff00'],
            ['name' => __('colors.LightGreen'), 'value' => '#90ee90'],
            ['name' => __('colors.GreenYellow'), 'value' => '#adff2f'],
            ['name' => __('colors.PaleGreen'), 'value' => '#98fb98'],
        ];
    }
}
